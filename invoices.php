<?php
/**
 * Created by PhpStorm.
 * User: Jesus Tovar
 * Date: 11/04/17
 * Time: 10:22
 */

require_once 'Requests/library/Requests.php';
Requests::register_autoloader();

echo $_POST['auth'];
echo $_POST['ido'];
if (isset($_POST['auth'])){
    if ($_POST['ido']){
        $url='https://invoice.zoho.eu/api/v3/invoices?authtoken='.$_POST['auth'].'&organization_id='.$_POST['ido'];
        $headers = array('Accept' => 'application/json');
        $request = Requests::get($url, $headers/*, $options*/);
        $data=json_decode($request->body);
        $invoices=array();
        foreach ($data->invoices as $val){
            $invoices[$val->invoice_number]=$val->invoice_id;
        }
        ksort($invoices);
        $csv_end = "|";
        $csv_sep = ",";
        $csv_file = "factures.csv";
        $csv="Code_journal,Date_de_piece,Numero_de_piece,Copmte_Comptable,Libelle,Debit,Credit\r\n";
        echo '<!DOCTYPE html>
                  <html lang="en">
                     <head>
                      <meta charset="UTF-8">
                      <title>Factures</title>
                     </head>
                     <body>
                          <table border="2px">
                              <thead>
                                  <tr>
                                      <td>Code journal</td>
                                      <td>Date de piece</td>
                                      <td>Numero de piece</td>
                                      <td>Compte Comptable</td>
                                      <td>Libelle</td>
                                      <td>Debit</td>
                                      <td>Credit</td>
                                  </tr>
                              </thead>
                          <tbody>';
        foreach ($invoices as $id_invoice){
            $urlInvoice='https://invoice.zoho.eu/api/v3/invoices/'.$id_invoice.'?authtoken='.$_POST['auth'].'&organization_id='.$_POST['ido'];
            $headersInvoice = array('Accept' => 'application/json');
            $requestInvoice = Requests::get($urlInvoice, $headersInvoice/*, $options*/);
            $dataInvoice=json_decode($requestInvoice->body);
            $invoice=$dataInvoice->invoice;
            $tax=0;
            foreach($invoice->taxes as $val){
                    $tax=$val->tax_amount;
            }
            echo'<tr><td>VT</td><td>'.$invoice->created_time.'</td><td>'.$invoice->reference_number.'</td><td>402</td><td>'.$invoice->customer_name.'</td><td>'.$invoice->total.'</td><td>  </td></tr>';
            $csv=$csv."VT,".$invoice->created_time.",".$invoice->reference_number.",402,".$invoice->customer_name.",".$invoice->total.",  \r\n";
            echo'<tr><td>VT</td><td>'.$invoice->created_time.'</td><td>'.$invoice->reference_number.'</td><td>402</td><td>'.$invoice->customer_name.'</td><td>  </td><td>'.$invoice->sub_total.'</td></tr>';
            $csv=$csv."VT,".$invoice->created_time.",".$invoice->reference_number.",402,".$invoice->customer_name.", ,".$invoice->sub_total."\r\n";
            if(!is_null($tax)){
                echo'<tr><td>VT</td><td>'.$invoice->created_time.'</td><td>'.$invoice->reference_number.'</td><td>402</td><td>'.$invoice->customer_name.'</td><td>  </td><td>'.$tax.'</td></tr>';
                $csv=$csv."VT,".$invoice->created_time.",".$invoice->reference_number.",402,".$invoice->customer_name.", ,".$tax."\r\n";
            }
            if($invoice->shipping_charge>0){
                echo'<tr><td>VT</td><td>'.$invoice->created_time.'</td><td>'.$invoice->reference_number.'</td><td>402</td><td>'.$invoice->customer_name.'</td><td>  </td><td>'.$invoice->shipping_charge.'</td></tr>';
                $csv=$csv."VT,".$invoice->created_time.",".$invoice->reference_number.",402,".$invoice->customer_name.", ,".$invoice->shipping_charge." \r\n";
            }
        }
       echo '</tbody></table></body></html>';
        //Generamos el csv de todos los datos
        if (!$handle = fopen($csv_file, "w")) {
            echo "Cannot open file";
            exit;
        }
        if (fwrite($handle, utf8_decode($csv)) === FALSE) {
            echo "Cannot write to file";
            exit;
        }
        fclose($handle);
    }else{
        echo 'error';
    }

}else{
    echo 'token pas valide';
    header('Location: http://localhost/Stage2017/zoho/index.html');
}
