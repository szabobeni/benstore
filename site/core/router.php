<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

class BaseRouter {

	/**
	 *
	 * Routing rules
	 */
	private $rules = array();

	private $url = null;

	/**
	 *
	 * Ads routing rule
	 */
	protected function addRoutingrule($expression, $module, $page, $params = null, $staticparams = null) {
		$item['expression'] = $expression;
		$item['module'] = $module;
		$item['page'] = $page;
		$item['params'] = $params;
		$item['staticparams'] = $staticparams;
		$this->rules[] = $item;
	}

	/**
	 *
	 * Defines routing rules. Should be overriden in app.
	 */
	protected function defineRules() {
			
	}

	/**
	 *
	 * Processes default page routing if no custom rule found.
	 * @return array containing module name and page
	 */
	protected function processDefaultrouting() {
			
		$urlparts = explode('/',$this->url);

		if (count($urlparts) > 0) {
			if (defined('ADMINFOLDER') && $urlparts[0] == ADMINFOLDER) {
				if (count($urlparts) == 3)
					return array($urlparts[1], $urlparts[2]);
				if (count($urlparts) == 2) {
					if ($urlparts[1])
						return array(INDEX_MODULE, $urlparts[1]);
					else
						return array(INDEX_MODULE, INDEX_PAGE);
				}
			} else {
				if (count($urlparts) == 2)
					return array($urlparts[0], $urlparts[1]);
				if (count($urlparts) == 1)
					return array(INDEX_MODULE, $urlparts[0]);
			}
		}
	}

	final public function route() {
			
		$this->defineRules();
			
		$module = null;
		$page = null;
			
		$urlparts = parse_url($_SERVER['REQUEST_URI']);
		$this->url = (isset($urlparts['path']) ? $urlparts['path'] : '');


		if ($this->url == '' || $this->url == '/') {
			$module = INDEX_MODULE;
			$page = INDEX_PAGE;
		} else {
			if (count($this->url) > 0 && $this->url[0] == '/')
				$this->url = substr($this->url, 1);
			foreach ($this->rules as $rule) {

				if (preg_match($rule['expression'], $this->url, $matches)) {

					$module = $rule['module'];
					$page = $rule['page'];

					if (is_array($rule['params']) && count($rule['params']) > 0) {
							
						foreach ($matches as $index=>$match) {
							if ($index == 0) continue;
							if (isset($rule['params'][$index-1]) && $rule['params'][$index-1]) {
								$_GET[$rule['params'][$index-1]] = $match;
							}
						}
					}
						
					if (is_array($rule['staticparams'])) {
							
						$keys = array_keys($rule['staticparams']);
						foreach ($keys as $key) {
							if (!isset($_GET[$key]))
								$_GET[$key] = $rule['staticparams'][$key];
						}
					}
					break;
				}
			}

			if (!$module || !$page) {
				$res = $this->processDefaultrouting();
				if (is_array($res) && count($res) == 2) {
					$module = $res[0];
					$page = $res[1];
				}
			}
		}
			
		$pagefile = DIR_PAGES . $module . DIRECTORY_SEPARATOR . $page . '.php';
		if (file_exists($pagefile)) {
			require_once $pagefile;

			$app = Page::getInstance();
			$app->run();
		} else {
			if (!DEVMODE)
				ob_clean();
			header("HTTP/1.0 404 Not Found");
			require_once DIR_STATIC . 'error404.php';
			if (DEVMODE == true)
				echo $pagefile;
		}
	}
}

?>