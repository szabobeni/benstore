<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/


/**
 *
 * Paypal functions
 */
class BasePaypalHelper {

	function validateIpn()
	{

		$IPN = $_POST;
		$IPN['cmd'] = '_notify-validate';

		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($cURL, CURLOPT_URL, PAYPAL_URL);
		curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
		curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($cURL, CURLOPT_POST, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $IPN);
		curl_setopt($cURL, CURLOPT_HEADER, false);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
		curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
		curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
				'Connection: close',
				'Expect: ',
		));

		$response = curl_exec($cURL);
		$status = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);

		curl_close($cURL);

		if(empty($response) || !preg_match('~^(VERIFIED|INVALID)$~i', $response = trim($response)) || !$status){
			throw new Exception('Paypal validation request error: ' . "\n" . print_r($response,true));
			return false;
		} else {
			if(intval($status / 100) != 2){
				throw new Exception('Paypal validation status error: ' . $status);
				return false;
			}
		}

		if (strpos($response,"VERIFIED") !== false)
		{
			return true;
		}
		else
		{
			throw new Exception('Paypal validation error: ' . $response);
			return false;
		}
	}
}

?>