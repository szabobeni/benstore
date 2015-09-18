<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

require_once DIR_CORE_HELPERS . 'mail.php';

/**
 *
 * Email functions
 */
class MailHelper extends BaseMailHelper {

	protected function processError() {
		$app = App::getInstance();
		$app->log($this->getErrormessage());
	}	
}

?>