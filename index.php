<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
 */

/* Defining system folders */

define('DIR_ROOT', __DIR__ . '/');
define('DIR_SITE', DIR_ROOT . 'site/');
define('DIR_APP', DIR_SITE . 'app/');
define('DIR_PAGES', DIR_APP . 'pages/');
define('DIR_CACHE', DIR_ROOT . 'data/cache/');
define('DIR_CORE', DIR_SITE . 'core/');
define('DIR_APP_HELPERS', DIR_APP . 'helpers/');
define('DIR_CORE_HELPERS', DIR_CORE . 'helpers/');
define('DIR_DATA_PUBLIC', DIR_ROOT . 'data/public');
define('DIR_DATA_PROTECTED', DIR_ROOT . 'data/protected');
define('DIR_DISPLAY', DIR_SITE . 'display/template/');
define('DIR_CSS', DIR_SITE . 'display/css/');
define('DIR_LIB_PUBLIC', DIR_SITE . 'lib/public/');
define('DIR_LIB_PROTECTED', DIR_SITE . 'lib/protected/');
define('DIR_LOG', DIR_ROOT . 'data/log/');
define('DIR_STATIC', DIR_SITE . 'display/static/');


require_once DIR_SITE . '/boot.php';
?>