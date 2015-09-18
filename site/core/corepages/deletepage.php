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
abstract class BaseDeletePage extends App {

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
    abstract protected function getSuccessLocation();

    /**
     * 
     * Define the location for redirecting in case of error.
     */
    abstract protected function getErrorLocation();

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

    /**
     * 
     * Processes the deleting.
     */
    public function process() {
        if ($this->item) {
            try {
                $this->item->delete();
                $this->redirect($this->getSuccessLocation());
                return true;
            } catch (Exception $e) {
                $this->redirect($this->getErrorLocation());
            }
        }
        return false;
    }

}

?>