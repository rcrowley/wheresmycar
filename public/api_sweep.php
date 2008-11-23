<?php

loadlib('gis');
loadlib('api');

# Need an address
if (!isset($_GET['address'])) {
	api('GET param "address" not found');
} else {
	$ts = gis_sweep($_GET['address']);
	if ($ts) { api(array('sweep' => date('c', $ts))); }
	else { api("couldn't geocode that address"); }
}
