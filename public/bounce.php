<?php

loadlib('user');
if (user_id()) { redirect('/home'); }
else { redirect('/start'); }
