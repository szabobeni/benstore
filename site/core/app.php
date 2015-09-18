<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

/**
 *
 * This is the base class of each page in the system.
 */
abstract class BaseApp {

	/**
	 *
	 * Collects the data for the template manager.
	 */
	private $params = array();

	/**
	 *
	 * Twig instance
	 */
	private $twig;

	/**
	 *
	 * List of the loaded helpers.
	 */
	private $helpers = array();
	
	/*
	 * Path to the root folder of the site
	 */
	protected $root;
	
	
	/*
	 * Marks if the site has been opened from a mobile device
	 */
	protected $mobile;
	
	/* Singleton model. Getting the APP instance */
	public static function getInstance()
	{
		static $instance = null;
		
		if ($instance === null) {
			$instance = new static();
		}
	
		return $instance;
	}
	
	/* Protected constructor to prevent creating more instances */
	protected function __construct() {
		
	}
	
	
	/**
	 *
	 * Makes all initialization.
	 */
	protected function init() {

		/* Checking if HTTPS needed */
		if ($this->isSecure() && !isset($_SERVER['HTTPS'])) {

			/* Redirecting to HTTPS */
			header('Location: https://' . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']);

			/* Further processing is not necessary */
			die();
		}

		/* Propel Initialization */
		if (defined('DS_CONFIGFILE')) {
		    require_once DIR_LIB_PROTECTED . 'propel/runtime/lib/Propel.php';
		    Propel::init(DIR_SITE . 'build/conf/' . DS_CONFIGFILE . '.php');
		    set_include_path(DIR_SITE . 'build/classes' . PATH_SEPARATOR . get_include_path());
		}

		/* Twig Initialization */
		require_once DIR_LIB_PROTECTED . 'twig/lib/Twig/Autoloader.php';
		Twig_Autoloader::register();
	}

	/**
	 *
	 * Processes the template
	 * @return generated HTML code
	 */
	protected function processTemplate($template, $params) {
		/* Loading Twig environment if necessary */
		if (!$this->twig) {
			$twigparams = array();

			/* Options in development mode */
			if (DEVMODE) {
				$twigparams['auto_reload'] = true;
				$twigparams['strict_variables'] = true;
				$twigparams['cache'] = false;
			}
			/* Options in production mode */ else {
			$twigparams['auto_reload'] = false;
			$twigparams['strict_variables'] = false;
			$twigparams['cache'] = DIR_CACHE . '/twig';
			}

			$twigloader = new Twig_Loader_Filesystem(DIR_DISPLAY);
			$twigloader->addPath(DIR_CSS);
			$this->twig = new Twig_Environment($twigloader, $twigparams);
			
			/* Loading custom filters */
			$twigfilters = new TwigFilters();
			$filterfunctions = get_class_methods($twigfilters);
			foreach ($filterfunctions as $filtername) {
				$filter = new Twig_SimpleFilter($filtername, array($twigfilters, $filtername));
				$this->twig->addFilter($filter);
			}
		}

		return $this->twig->render($template . '.html', $params);
	}
	
	/*
	 * Redirects the page 
	 */
	protected function redirect($target, $die = true) {
		if (strpos($target, 'http://') !== false || strpos($target, 'https://') !== false) {
			header ('Location: ' . $target);
		} else {
			header('Location: ' . $this->root . $target);
		}
		if ($die)
			die();
	}
	
	
	/**
	 *
	 * This function processes the page logic. Each page controller has to override it.
	 * @return a string defining the page template, null if no output needed.
	 */
	abstract protected function process();

	/**
	 *
	 * This is the entry point of all pages. Manages the whole process.
	 */
	public final function run() {

		header('Content-Type: text/html; charset=utf-8');
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		 
		/* Calculating the path to wwwroot */
		$this->root = '';
		if (isset($_SERVER['HTTPS']))
			$this->root = 'https://';
		else 
			$this->root = 'http://';
		$this->root .= $_SERVER['SERVER_NAME'] . '/';
				
		$this->setParam('_root',$this->root);
		$this->setParam('_selfurl',$_SERVER['REQUEST_URI']);
		
		/* Detecting old version of IE */
		if( isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [4-9]/',$_SERVER['HTTP_USER_AGENT'])) {
			$this->setParam('_oldie',true);
		}		
		
		/* Detecting mobile */
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android'))) {
			$this->mobile == true;
			$this->setParam('_mobile',true);
		}
		
		
		/* Adding the version */
		if (defined('VERSION'))
			$this->setParam('_version', VERSION);
		else
			$this->setParam('_version', 0);
		
		$this->setParam('_time',time());
		
		/* Adding dev or production mode */
		$this->setParam('_devmode',DEVMODE);
		
		/* Initialization */
		$this->init();

		/* Checking if there is enough right to open the page */
		$right = $this->hasRightToOpen();
		if ($right !== true) {

			/* Check if the page has to be redirected */
			if (is_string($right)) {

				/* Redirecting */
				$this->redirect($right);
				die();
			}
			/* Otherwise 403 error page has to be generated */ else {
			header('HTTP/1.0 403 Forbidden');
			require_once DIR_STATIC . 'error403.php';
			die();
			}
		}

		/* Processing the page */
		$template = $this->process();
		
		/* The the page return a template name, it has to be loaded */
		if (is_string($template)) {


			/* Generating output HTML */
			echo $this->processTemplate($template, $this->params);
		}
	}

	/**
	 *
	 * Sets a parameter for the template manager.
	 */
	protected final function setParam($name, $value) {
		$this->params[$name] = $value;
	}

	/**
	 *
	 * Defines if the page needs HTTPS protocoll.
	 * @return boolean
	 */
	protected function isSecure() {
		/* By default, pages don't need HTTPS */
		return false;
	}

	/**
	 *
	 * Defines if there is enough rights to open the page.
	 * @return boolean true if there is enough right
	 * @return string if the page needs to be redirected (typically to login page)
	 * @return null if 403 error has to be generated
	 */
	protected function hasRightToOpen() {
		/* By default, pages can be opened. */
		return true;
	}

	/**
	 *
	 * Returns a helper class.
	 * @return helper class
	 */
	protected function getHelper($helper) {
		/* If the helper has already been loaded, just return with it. */
		if (isset($this->helpers[$helper]))
			return $this->helpers[$helper];

		/* Otherwise helper has to be loaded */
		require_once DIR_APP_HELPERS . $helper . '.php';
		$classname = ucfirst($helper) . 'Helper';
		$class = new $classname;
		if (method_exists($class, 'setApp'))
			call_user_func_array(array($class, 'setApp'), array($this));
		$this->helpers[$helper] = $class;
		return $this->helpers[$helper];
	}

	/**
	 * Logs any error message
	 */
	public function log($message) {
		try {
			$file = DIR_LOG . date("Y_m_d") . '.log';
			if ($fh = fopen($file, 'a')) {
				fwrite($fh,date('Y-m-d H:i:s') . ":\n");
				fwrite($fh, "\n" . $message . "\n");
				fwrite($fh, "\n");
				fwrite($fh, "\n---\n");
				fclose($fh);
			}
		} catch (Exception $ex) {
		}
	}

	/* Shows the 404 error page */
	protected function show404() {
		if (!DEVMODE)
			ob_clean();
		header("HTTP/1.0 404 Not Found");
		require_once DIR_STATIC . 'error404.php';
		die();
	}
	
	/* Shows the 403 error page */
	protected function show403() {
		if (!DEVMODE)
			ob_clean();
		header('HTTP/1.0 403 Forbidden');
		require_once DIR_STATIC . 'error403.php';
		die();
	}
	
}

?>