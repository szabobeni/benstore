<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
*/

/* Setting developer or production mode */
define('DEVMODE', true);


/* Loading app config */
require_once DIR_APP . 'config.php';

if (DEVMODE == true) {
	/* Loading developer mode config */
	require_once DIR_APP . 'config_dev.php';
} else {
	/* Loading production mode config */
	require_once DIR_APP.'config_prod.php';
}

if (DEVMODE == true) {
	/* Booting development environment */
	require_once DIR_APP . 'boot_dev.php';
} else {
	/* Booting production environment */
	require_once DIR_APP.'boot_prod.php';
}

/* Loading core */
require_once DIR_CORE . 'loadcore.php';

/* Loading app */
require_once DIR_APP . 'loadapp.php';

/* Loading router */
require_once DIR_CORE . 'router.php';
require_once DIR_APP . 'router.php';

/* Starting the session */
session_start();

/* Loading module and page */

$router = new Router();
$router->route();


?>
