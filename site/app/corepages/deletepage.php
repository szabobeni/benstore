<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
 */

abstract class DeletePage extends BaseDeletePage {

    protected function getSuccessLocation() {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function getErrorLocation() {
        return $this->root . ADMINFOLDER . '/misc/deleteerror';
    }

}

?>