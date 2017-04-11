<?php
/**
 * Created by PhpStorm.
 * User: Jesus Tovar
 * Date: 10/04/17
 * Time: 10:10
 */
require_once 'Requests/library/Requests.php';
Requests::register_autoloader();

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
            $_SESSION['auth'] = $token[1];
            if($_SESSION['auth']=="EXCEEDED_MAXIMUM_ALLOWED_AUTHTOKENS"){
                echo "Vous avez dépassé le limite de tokens permis pour cette compte. Contactez au propietaire.";
            }else if($_SESSION['auth']=="INVALID_PASSWORD"||$_SESSION['auth']=="INVALID_CREDENTIALS"){
                echo "L'adresse ou le mot de passe ne sont pas validés.";
            }else{
               getOrganization();
            }
        }

function getOrganization(){
    if(isset($_SESSION['auth'])||$_SESSION['auth']!="EXCEEDED_MAXIMUM_ALLOWED_AUTHTOKENS"){
        $url='https://invoice.zoho.eu/api/v3/organizations?authtoken='.$_SESSION['auth'];
        $headers = array('Accept' => 'application/json');
        $request = Requests::get($url, $headers/*, $options*/);
        $data=json_decode($request->body);
        $organizations=$data->organizations;
        echo '<!DOCTYPE html>
            <html lang="en">
               <head>
                <meta charset="UTF-8">
                <title>Connexion</title>
               </head>
               <body>';
        foreach ($organizations as $val){
            $ido=$val->organization_id;
            $name=$val->name;
            echo ' <form method="post" ACTION="invoices.php">
                        <label>Société: '.$name.'</label>
                        <input type="hidden" value="'.$ido.'" name="ido"/>
                        <input type="hidden" value="'.$_SESSION['auth'].'" name="auth"/>
                        <input type="submit" value="Choisir"/>
                    </form>';
        }
        echo '</body>
            </html>';
    }else{
        echo 'token pas valide';
        header('Location: http://localhost/Stage2017/zoho/index.html');
    }
}






