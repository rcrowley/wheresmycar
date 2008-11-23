<?php

$GLOBALS['_gis_site'] = 'http://gispubweb.sfgov.org/website/sfviewer';
$GLOBALS['_gis_servlet'] = 'http://gispubweb.sfgov.org/servlet/com.esri.esrimap.Esrimap';

# Get the street cleaning schedule for a given address and maybe point
function gis_sweep($address) {
	debug("[gis] gis_sweep address: $address\n");

	# Geocode
	$geocode = gis_geocode_fuzzy($address);
	if (!$geocode) { return false; }
	list($x, $y) = array_values($geocode);

	# Fetch the info frame
	$ch = curl_init("{$GLOBALS['_gis_site']}/getsfinfo.asp?" .
		http_build_query(array( 'mapx' => $x, 'mapy' => $y)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$info = curl_exec($ch);
	curl_close($ch);

	# Try every known scraping pattern to get the street sweeping time
	$scrapers = array(
		'gis_scrape_additional',
		'gis_scrape_original'
	);
	$side = 'O' == gis_side($address) ? 'Left' : 'Right';
	foreach ($scrapers as $scraper) {
		$scrape = $scraper($info, $side);
		if ($scrape) { return $scrape; }
	}
	debug("[gis] gis_sweep no more scrapers\n");
	return false;

}

# Geocode in both directions down the street until an address exists
function gis_geocode_fuzzy($address) {
	debug("[gis] gis_geocode_fuzzy address: $address\n");
	$geocode = gis_geocode($address);
	if (!$geocode || 0 == $geocode['x'] || 0 == $geocode['y']) {
		if (!preg_match('!^([0-9]+)!', $address, $match)) {
			debug("[gis] gis_geocode_fuzzy doesn't like address: $address\n");
			return false;
		}
		$base = (int)$match[1];
		$fudge = 0;
		while (!$geocode || 0 == $geocode['x'] || 0 == $geocode['y']) {
			$fudge = 0 < $fudge ? -$fudge : 2 - $fudge;
			$geocode = gis_geocode(str_replace($base, $base + $fudge,
				$address));
			if (100 < abs($fudge)) {
				debug("[gis] gis_geocode_fuzzy is getting too fuzzy\n");
				return false;
			}
		}
	}
	return $geocode;
}

# Geocode an address to get whatever strange (x, y) representation they use
function gis_geocode($address) {
	debug("[gis] gis_geocode address: $address\n");
	$side = gis_side($address);

	# The POST payload
	$post = array(
		'ArcXMLRequest' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<ARCXML version=\"1.1\">
	<REQUEST>
		<GET_GEOCODE maxcandidates=\"1\" minscore=\"71\">
			<LAYER id=\"6\" />
			<ADDRESS>
				<GCTAG id=\"STREET\" value=\"$address\" />
				<GCTAG id=\"ZONE\" value=\"$side\" />
				<GCTAG id=\"CROSSSTREET\" value=\"\" />
			</ADDRESS>
		</GET_GEOCODE>
	</REQUEST>
</ARCXML>",
		'JavaScriptFunction' => 'parent.MapFrame.processXML'
	);

	# Geocode the address
	$ch = curl_init("{$GLOBALS['_gis_servlet']}?" . http_build_query(array(
		'ServiceName' => 'sfviewer',
		'CustomService' => 'Geocode',
		'Form' => 'True',
		'Encode' => 'True'
	)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	curl_setopt($ch, CURLOPT_REFERER, "{$GLOBALS['_gis_servlet']}?" .
		http_build_query(array(
			'ServiceName' => 'sfviewer',
			'Form' => 'True',
			'Encode' => 'True'
	)));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type' => 'application/x-www-form-urlencoded'
	));

	# Parse out XML and XPath the fake lat/long data
	$rsp = curl_exec($ch);
	if (!preg_match("/var XMLResponse='(.*)';/", $rsp, $match)) {
		curl_close($ch);
		debug("[gis] gis_geocode failed for address: $address\n");
		return false;
	}
	curl_close($ch);
	$xml = new SimpleXMLElement(urldecode($match[1]));
	$point = $xml->xpath(
		"//RESPONSE/GEOCODE/FEATURE/FIELD[@type='-98']/FIELDVALUE/POINT"
	);

	$out = array(
		'x' => (double)$point[0]['x'],
		'y' => (double)$point[0]['y']
	);
	debug("[gis] gis_geocode address: $address, x: {$out['x']}, y: {$out['y']}\n");
	return $out;
}

# Find out the side of the street we're on
#   Returns 'O' or 'E'
function gis_side($address) {
	preg_match('/^([0-9]+)/', $address, $match);
	return (int)$match[1] % 2 ? 'O' : 'E';
}

# New-style scraping that includes the "Additional Information" field
function gis_scrape_additional($info, $side) {
	debug("[gis] gis_scrape_additional\n");
	if (preg_match("!$side.*?Side</FONT>.*?</TD>.*?<TD>.*?<FONT.*?color=#00336.*?size=2>[0-9]+-[0-9]+</FONT>.*?</TD>.*?</TR>.*?<TR>.*?<TD.*?width=\"45%\".*?bgColor=silver><FONT.*?color=#00336.*?size=1>$side.*?Side.*?Street.*?swept</FONT>.*?</TD>.*?<TD><FONT.*?color=#00336.*?size=2>([^<]+)</FONT>.*?</TD>.*?</TR>.*?<TR>.*?<TD.*?width=\"45%\".*?bgColor=silver><FONT.*?color=#00336.*?size=1>Additional.*?Information</FONT>.*?</TD>.*?<TD><FONT.*?color=#00336.*?size=2>([^<]+)!s",
		$info, $match)) {
		return gis_date_additional($match[1], $match[2]);
	} else { return false; }
}

# Original scraping style the only knows about the "Sweeped"/"swept" field
function gis_scrape_original($info, $side) {
	debug("[gis] gis_scrape_original\n");
	if (preg_match("!$side.*?Side</FONT>.*?</TD>.*?<TD>.*?<FONT.*?color=#00336.*?size=2>[0-9]+-[0-9]+</FONT>.*?</TD>.*?</TR>.*?<TR>.*?<TD.*?width=\"45%\".*?bgColor=silver><FONT.*?color=#00336.*?size=1>$side.*?Side.*?Street.*?(?:Sweeped|swept)</FONT>.*?</TD>.*?<TD><FONT.*?color=#00336.*?size=2>([^<]+)!s",
		$info, $match)) {
		return gis_date_original($match[1]);
	} else { return false; }
}

# Augment the original date-divining algorithm with what we learn from the
# "Additional Information" field gives us
function gis_date_additional($sweep, $additional) {
	debug("[gis] gis_date_additional sweep: $sweep, additional: $additional\n");

	# The original algorithm still gives a safe conservative answer,
	# so start there
	$ts = gis_date_original($sweep);
	if (!$ts) { return false; }

	# From this point, we may loop back once to spill over into next month
	for ($i = 0; $i < 2; ++$i) {

		# In which week of the month does our current answer fall?
		#   Think of this as the Nth Xday, where X is the day of the week
		#   in the original algorithm's answer
		$week = (int)(date('j', $ts) / 7) + ((date('j', $ts) % 7) ? 1 : 0);

		# Which weeks are actually swept?
		#   The format of "Additional Information" is something like
		#   "Effective 8/25/2008, 1st & 3rd Monday only"
		if (!preg_match('!(\d)[a-z]{2} & (\d)[a-z]{2}!', $additional, $weeks)) {
			debug("[gis] gis_date_additional failed additional: $additional\n");
			return $ts;
		}
		array_shift($weeks);

		# Is this week one of the weeks that is swept?
		if (in_array($week, $weeks)) { return $ts; }

		# Is there another week this month that is swept?
		foreach ($weeks as $w) {
			if ($w > $week) {
				$diff = $w - $week;
				return strtotime("+$diff week" . (1 == $diff ? '' : 's'), $ts);
			}
		}

		# Loop around again with the initial timestamp set to the first
		# Xday of next month, making the blocks above work out the first
		# sweeping day happening next month
		$time = date('H:i:s', $ts);
		$ts = strtotime(date(
			"Y-m-d $time",
			strtotime(
				'this ' . date('l', $ts),
				strtotime(date(
					'Y-m-01',
					strtotime('+1 month', $ts)
				))
			)
		));

	}

	return false;
}

# Original date-divining algorithm for "Mon 9AM to 11AM"-style data
function gis_date_original($sweep) {
	debug("[gis] gis_date_original sweep: $sweep\n");

	# Calculate the timestamp of the beginning of the next sweeping period
	if (preg_match_all('/Sun|Mon|Tues|Wed|Thu|Fri|Sat/', $sweep, $match)) {
		$days = $match[0];

		# Stupid GIS uses 4 letters for Tuesday
		while (false !== $i = array_search('Tues', $days)) {
			$days[$i] = 'Tue';
		}

		# Figure out what day comes next
		$ts = time();
		$next_day = false;
		while (false === $next_day) {
			$ts = strtotime('+1 day', $ts);
			$day = date('D', $ts);
			if (in_array($day, $days)) { $next_day = $day; }
		}

		# Find the first time that follows the magic day
		$eligible = substr($sweep, strpos($sweep, $next_day));
		if (preg_match('/[0-9]{1,2}(?::[0-9]{2})?(?:A|P)M/', $sweep, $match)) {

			# Get a timestamp of the next day and the first time after it
			return strtotime("$next_day {$match[0]}");

		}

	}

	return false;
}
