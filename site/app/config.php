<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
 */


/* Session timeout */
ini_set('session.gc_maxlifetime',0);

/* Setting time zone */
date_default_timezone_set('Europe/Dublin');

/* Setting default module and page */
define('INDEX_MODULE', 'home');
define('INDEX_PAGE', 'home');

/* Setting admin url */
define('ADMINFOLDER','manage');

/* Setting ORM config file */
//define('DS_CONFIGFILE', '###-conf');

/* Version */
define('VERSION', 1);


?>
