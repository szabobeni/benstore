<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

/**
 *
 * General page for deleting items.
 */
abstract class SoftDeletePage extends App {

	/**
	 *
	 * Object to be deleted.
	 */
	protected $item;

	/**
	 *
	 * Instanciates the query object.
	 */
	abstract protected function getQuery();

	/**
	 *
	 * Define the location for redirecting in case of success.
	 */
	protected function getSuccessLocation() {
		return $_SERVER['HTTP_REFERER'];
	}


	/**
	 *
	 * Initialization
	 */
	public function init() {
		parent::init();
		if (isset($_GET['id'])) {
			$q = $this->getQuery();
			$this->item = $q->findOneById($_GET['id']);
		}
	}
	
	protected function doBeforeDelete() {
		
	}

	/**
	 *
	 * Processes the deleting.
	 */
	public function process() {
		if ($this->item) {
			$this->item->setDeleted(true);
			$this->doBeforeDelete();
			$this->item->save();
			$this->redirect($this->getSuccessLocation());
		}
		return null;
	}

}

?>