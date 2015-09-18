<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
 */

/**
 * 
 * Database functions
 */
class BaseDbHelper {

    /**
     * 
     * Application instance.
     */
    private $app;

    /**
     * 
     * Setting application instance.
     */
    public function setApp($value) {
        $this->app = $value;
    }

    /**
     * 
     * Prepars string data for DB.
     */
    public function strToDB($index) {
        /* If there is no data, return with null */
        if (!isset($_POST[$index]))
            return null;

        /* Trimming the string */
        $str = trim($_POST[$index]);
        if ($str == '')
            $str = null;
        return $str;
    }

    /**
     * 
     * Prepars boolean data for DB.
     */
    public function boolToDB($index) {
        if (!isset($_POST[$index]))
            return false;

        $bool = $_POST[$index];
        if (!isset($bool) || $bool == false)
            $bool = false;
        else
            $bool = true;
        return $bool;
    }

    /**
     * 
     * Preparing foreign key data for DB.
     */
    public function fkeyToDB($index) {
        /* If there is no data, return with null */
        if (!isset($_POST[$index]))
            return null;
        $fkey = $_POST[$index];
        $fkey = intval($fkey);
        if ($fkey > 0)
            return $fkey;
        else
            return null;
    }

    /**
     * 
     * Prepars a date data for DB.
     */
    public function dateToDB($index) {
        /* If there is no data, return with null */
        if (!isset($_POST[$index]))
            return null;

        $date = $_POST[$index];
        $date = strtotime($date);
        if ($date === false)
            return null;
        return date("Y-m-d", $date);
    }

    /**
     * 
     * Prepars integer data for DB.
     */
    public function intToDB($index) {
        /* If there is no data, return with null */
        if (!isset($_POST[$index]))
            return null;
        return $_POST[$index];
    }

    /**
     * 
     * Prepars integer data for DB.
     */
    public function floatToDB($index) {
        /* If there is no data, return with null */
        if (!isset($_POST[$index]))
            return null;
        return $_POST[$index];
    }

    /**
     * 
     * Prepars template parameters for pagination
     */
    public function paginateResult($res) {
        $this->app->setParam('havetopaginate', $res->haveToPaginate());
        $prev = $res->getPreviousPage();
        $next = $res->getNextPage();
        $current = $res->getPage();
        $total = $res->getLastPage();
        if ($current > 1)
            $this->app->setParam('prevpage', $prev);
        if ($current < $total)
            $this->app->setParam('nextpage', $next);
        $this->app->setParam('currentpage', $current);
        $this->app->setParam('totalpage', $total);
    }

    /**
     *
     * Elliminates accents from a string
     */
    public function normalize($string) {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'd' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c',
            'Ŕ' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ă' => 'A', 'Ä' => 'A', 'Ĺ' => 'A', 'Ć' => 'A', 'Ç' => 'C', 'Č' => 'E', 'É' => 'E',
            'Ę' => 'E', 'Ë' => 'E', 'Ě' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ď' => 'I', 'Ń' => 'N', 'Ň' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Ő' => 'O', 'Ö' => 'O', 'Ř' => 'O', 'Ů' => 'U', 'Ú' => 'U', 'Ű' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ţ' => 'B', 'ß' => 'Ss',
            'ŕ' => 'a', 'á' => 'a', 'â' => 'a', 'ă' => 'a', 'ä' => 'a', 'ĺ' => 'a', 'ć' => 'a', 'ç' => 'c', 'č' => 'e', 'é' => 'e',
            'ę' => 'e', 'ë' => 'e', 'ě' => 'i', 'í' => 'i', 'î' => 'i', 'ď' => 'i', 'đ' => 'o', 'ń' => 'n', 'ň' => 'o', 'ó' => 'o',
            'ô' => 'o', 'ő' => 'o', 'ö' => 'o', 'ř' => 'o', 'ů' => 'u', 'ú' => 'u', 'ű' => 'u', 'ý' => 'y', 'ý' => 'y', 'ţ' => 'b',
            '˙' => 'y', 'R' => 'R', 'r' => 'r',
        );
        return strtr($string, $table);
    }

    /**
     *
     * Converts string to url format
     */
    public function toURL($string) {
        $string = $this->normalize($string);
        $string = trim($string);
        $string = str_replace(" ", "-", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace("'", "", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace("é", "e", $string);
        $string = str_replace("č", "e", $string);
        $string = str_replace("ę", "e", $string);
        $string = str_replace("ë", "e", $string);
        $string = str_replace("ŕ", "a", $string);
        $string = str_replace("ä", "a", $string);
        $string = str_replace("ô", "o", $string);
        $string = str_replace("ö", "o", $string);
        $string = str_replace("ű", "u", $string);
        $string = str_replace("ů", "u", $string);
        $string = str_replace("î", "i", $string);
        $string = str_replace("\\", "", $string);
        $string = str_replace("---", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("/", "-", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace("%", "", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("=", "", $string);
        return strtolower($string);
    }

    /**
     *
     * Generates a random password
     */
    public function createPassword($length) {
        $chars = "1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $i = 0;
        $password = "";
        while ($i <= $length) {
            $password = $password . $chars{mt_rand(0, strlen($chars)-1)};
            $i++;
        }
        return $password;
    }

}

?>