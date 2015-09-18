<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

/**
 *
 * Sagepay functions
 */
class SagepayHelper {

	private $cardtypes = array('VISA','UKE','MC','MAESTRO','AMEX');

	public function sendSagePayRequest($transid, $amount,$cardholder,$cardnumber,$expirydate,$cv2,$cardtype,$issue,$address1,$address2,$town,$postcode, $phoneorder) {

		$name = array_reverse(explode(' ',$cardholder));
		$surname = array_shift($name);
		$firstname = implode(' ',array_reverse($name));
		$data = array();
		$data['VPSProtocol'] = '3.00';
		$data['TxType'] = 'PAYMENT';
		$data['Vendor'] = SAGEPAY_VENDOR;
		$data['VendorTxCode'] = $transid;
		$data['Amount'] = $amount;
		$data['Currency'] = 'GBP';
		$data['Description'] = SAGEPAY_DESCRIPTION;
		$data['CardHolder'] = $cardholder;
		$data['CardNumber'] = $cardnumber;
		$data['ExpiryDate'] = $expirydate;
		$data['CV2'] = $cv2;
		$data['IssueNumber'] = $issue;
		$data['CardType'] = $this->cardtypes[$cardtype-1];
		$data['BillingSurname'] = $surname;
		$data['BillingFirstnames'] = $firstname;
		$data['BillingAddress1'] = (SAGEPAY_LIVE ? $address1 : '88');
		$data['BillingAddress2'] = (SAGEPAY_LIVE ? $address2 : '');
		$data['BillingCity'] = $town;
		$data['BillingPostCode'] = (SAGEPAY_LIVE ? $postcode : '412');
		$data['BillingCountry'] = 'GB';
		$data['DeliverySurname'] = $surname;
		$data['DeliveryFirstnames'] = $firstname;
		$data['DeliveryAddress1'] = $address1;
		$data['DeliveryAddress2'] = $address2;
		$data['DeliveryCity'] = $town;
		$data['DeliveryPostCode'] = $postcode;
		$data['DeliveryCountry'] = 'GB';
		$data['StoreToken'] = 0;
		$data['AccountType'] = ($phoneorder ? 'M' : 'E');

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL,SAGEPAY_URL);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		
		file_put_contents(DIR_LOG . 'sagepay/' . $transid . '.log',$response);
		$response = preg_split('/$\R?^/m',$response);


		for ($i = 0; $i < count($response); $i++) {
			$splitAt = strpos($response[$i], "=");
			$output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt+1)));
		}
		
		return $output;
	}
	
	public function sendSagePayCallback($md, $pares) {
		$ch = curl_init();
		
		$data = array();
		$data['MD'] = $md;
		$data['PaRes'] = $pares;
	
		curl_setopt($ch,CURLOPT_URL,SAGEPAY_URL3D);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		
		ob_start();
		var_dump($response);
		$data = ob_get_clean();
		file_put_contents(DIR_LOG . 'sagepay/' . $_SESSION['transid'] . '_3D2.log',$data);
		
	
		$response = preg_split('/$\R?^/m',$response);
		for ($i = 0; $i < count($response); $i++) {
			$splitAt = strpos($response[$i], "=");
			$output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt+1)));
		}
			
		return $output;
	
	}
	
}

?>