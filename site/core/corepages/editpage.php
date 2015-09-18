<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

/**
 *
 * General page for editing items.
 */
abstract class BaseEditPage extends App {

	/**
	 *
	 * Object to be edited.
	 */
	protected $item = null;

	/**
	 *
	 * Connection object.
	 */
	protected $con = null;

	/**
	 *
	 * DB Helper.
	 */
	protected $db = null;

	/**
	 *
	 * Template
	 */
	protected $template = null;

	/**
	 *
	 * Is new item
	 */
	protected $isnew = false;

	/**
	 *
	 * Instanciates the query object.
	 */
	abstract protected function getQuery();

	/**
	 *
	 * Instanciates a new item.
	 */
	protected function createItem() {
		return null;
	}

	/**
	 *
	 * Initializes the edit page. Will be called at the beginning of processing.
	 */
	protected function initEditPage() {

	}

	/**
	 *
	 * Sets the data fields of the data object.
	 */
	protected function fillUpFields() {

	}

	/**
	 *
	 * Tasks before saving.
	 */
	protected function doBeforeSave() {

	}

	/**
	 *
	 * Tasks after saving.
	 */
	protected function doAfterSave() {
		return true;
	}

	/**
	 *
	 * Tasks after commit.
	 */
	protected function doAfterCommit() {

	}

	/**
	 *
	 * Tasks on rollback.
	 */
	protected function doOnRollback() {

	}

	/**
	 *
	 * Tasks on validation error.
	 */
	protected function doOnValidationError() {

	}

	/**
	 *
	 * Custom validation.
	 * @return true if the validation is successfull
	 * @return array with the error messages if the validation is not successfull
	 */
	protected function doCustomValidation() {
		return true;
	}

	/**
	 *
	 * Initialization.
	 */
	protected function init() {
		parent::init();
		if (isset($_GET['id'])) {
			$q = $this->getQuery();
			$this->item = $q->findOneById($_GET['id']);
		}
	}

	/**
	 *
	 * Processes the editing.
	 */
	public function process() {

		$error = null;

		/* Calling the parent process */
		parent::process();

		/* Creating the item if it doesn't exist */
		if (!isset($this->item) || $this->item == NULL) {
			$this->item = $this->createItem();
			$this->isnew = true;
		}

		/* Doing the initialization of the current page */
		$this->initEditPage();

		/* Saving */
		if (isset($_POST['submitdata'])) {

			/* Filling up the fields */
			$this->db = $this->getHelper('db');
			$this->fillUpFields();

			/* Validating */
			$valid = $this->item->validate();
			$customvalid = $this->doCustomValidation();
			if ($valid && $customvalid === true) {

				/* Saving */
				$this->con = Propel::getConnection();
				try {
					$this->con->begintransaction();
					$this->doBeforeSave();
					$this->item->save($this->con);
					if ($this->doAfterSave()) {
						$this->con->commit();
						$this->doAfterCommit();
					} else {
						$this->con->rollback();
						$this->doOnRollback();
					}
				} catch (Exception $ex) {
					$this->con->rollback();
					$this->doOnRollback();
					throw $ex;
				}
			}
			/* Validation error */ else {
			$error = array();
			foreach ($this->item->getValidationFailures() as $failure) {
				$error[] = $failure->getMessage();
			}
			if (is_array($customvalid) && count($customvalid) > 0)
				$error = array_merge($error, $customvalid);
			$this->doOnValidationError();
			}
		}
		$this->setParam('data', $this->item);
		$this->setParam('error', $error);

		return $this->template;
	}

}

?>