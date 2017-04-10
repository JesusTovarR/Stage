<?php
/**
 * Created by PhpStorm.
 * User: Jesus Tovar
 * Date: 10/04/17
 * Time: 10:10
 */
/*$array="";
$token="";
$auth="";

$url = 'https://accounts.zoho.eu/apiauthtoken/nb/create';
$data = array('SCOPE' => 'ZohoInvoice/invoiceapi', 'EMAIL_ID' => $_POST['email'], 'PASSWORD' => $_POST['password']);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === false){
    echo "error";
}else {
    $array = explode("\n", $result);
    $token = explode("=", $array[2]);
    $auth = $token[1];
    echo $auth;
}
*/
/*****************************************************************************/





