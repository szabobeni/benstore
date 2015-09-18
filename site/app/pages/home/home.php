<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

class Page extends App {
	
	protected function process() {
		parent::process();

		return 'home/home';
	}
}
?>
