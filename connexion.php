<?php
/**
 * Created by PhpStorm.
 * User: Jesus Tovar
 * Date: 10/04/17
 * Time: 10:10
 */
require_once 'Requests/library/Requests.php';
Requests::register_autoloader();

$array="";
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
    var_dump($result);
    $array = explode("\n", $result);
    $token = explode("=", $array[2]);
    $auth = $token[1];
    org($auth);
}

/*****************************************************************************/

function org($auth){
    $url='https://invoice.zoho.eu/api/v3/organizations?authtoken='.$auth;
    $headers = array('Accept' => 'application/json');
//$options = array('auth' => array('jesustovar678@gmail.com', 'Tovar19011995'));
    $request = Requests::get($url, $headers/*, $options*/);

    var_dump($request->status_code);
// int(200)

//    var_dump($request->headers['content-type']);
// string(31) "application/json; charset=utf-8"

    $data=json_decode($request->body);
//    var_dump($data);

    $ido=$data->organizations[0]->organization_id;
// string(26891) "[...]"
    invoices($auth, $ido );
}


function invoices($auth, $ido){
    $url='https://invoice.zoho.eu/api/v3/invoices?authtoken='.$auth.'&organization_id='.$ido;
    $headers = array('Accept' => 'application/json');
//$options = array('auth' => array('jesustovar678@gmail.com', 'Tovar19011995'));
    $request = Requests::get($url, $headers/*, $options*/);

    var_dump($request->status_code);
// int(200)

    var_dump($request->headers['content-type']);
// string(31) "application/json; charset=utf-8"

    $data=json_decode($request->body);
    var_dump($data);

}



