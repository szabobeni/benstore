<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
 */

abstract class BaseMovePage extends App {

    protected $data = null;
    protected $other = null;

    abstract protected function getOtherItem();

    abstract protected function getItemQuery();
    
    protected function getReturnUrl() {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function init() {
        parent::init();
        $q = $this->getItemQuery();
        if (isset($_GET['id']) && $_GET['id'])
            $this->data = $q->findOneById($_GET['id']);
        if ($this->data)
            $this->other = $this->getOtherItem();
    }

    protected function process() {
        if ($this->data && $this->other) {
            $con = Propel::getConnection();
            try {
                $temp = $this->data->getOrdNumber();
                $this->data->setOrdNumber($this->other->getOrdNumber());
                $this->other->setOrdNumber($temp);
                $this->data->save($con);
                $this->other->save($con);
                $con->commit();
                header('Location: ' . $this->getReturnUrl());
            } catch (Exception $ex) {
                $con->rollback();
                throw $ex;
            }
        } else {
            header('Location: ' . $this->getReturnUrl());
        }
        return null;
    }

}

?>
