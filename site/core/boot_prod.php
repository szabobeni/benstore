<?php

/*
 * This file is a part of the BenStore PHP Framework.
 * All rights reserved.
 * (C) Bence Szabo 2013
 * 
 */


/* Exception handler */

function exceptionHandler($exception) {
    try {
        $file = DIR_LOG . date("Y_m_d") . '.log';
        if ($fh = fopen($file, 'a')) {
            fwrite($fh, "\n" . 'An Exception has been thrown at ' . date("Y-m-d H:i:s") . "\n");
            fwrite($fh, "\n");
            fwrite($fh, $exception->getMessage());
            fwrite($fh, "\n---\n");
            fclose($fh);
        }
    } catch (Exception $ex) {
        
    }
}

/* Error handler */

function errorHandler($errno, $errstr, $errfile, $errline) {
    try {
        $file = DIR_LOG . date("Y_m_d") . '.log';
        if ($fh = fopen($file, 'a')) {
            fwrite($fh, "\n" . 'An Error Occured at ' . date("Y-m-d H:i:s") . "\n");
            fwrite($fh, "\n");
            fwrite($fh, $errstr);
            fwrite($fh, "\n" . $errfile . ': ' . $errline);
            fwrite($fh, "\n---\n");
            fclose($fh);
        }
    } catch (Exception $ex) {
        
    }
    /* Don't execute PHP internal error handler */
    return true;
}

/* Hiding all errors and warnings */
error_reporting(0);
ini_set('display_errors', false);

/* Logging errors if enabled */
if (defined('LOGERRORS') && LOGERRORS == true) {
    set_error_handler('errorHandler');
    set_exception_handler('exceptionHandler');
}
?>