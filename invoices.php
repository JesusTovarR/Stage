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
        $csv='Code_journal,Date_de_piece,Numero_de_piece,Copmte_Comptable,Libelle,Debit,Credit|';
        /*echo '<!DOCTYPE html>
                  <html lang="en">
                     <head>
                      <meta charset="UTF-8">
                      <title>Factures</title>
                     </head>
                     <body>
                          <table>
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
                          </table>';*/
        foreach ($invoices as $id_invoice){
            $urlInvoice='https://invoice.zoho.eu/api/v3/invoices/'.$id_invoice.'?authtoken='.$_POST['auth'].'&organization_id='.$_POST['ido'];
            $headersInvoice = array('Accept' => 'application/json');
            $requestInvoice = Requests::get($urlInvoice, $headersInvoice/*, $options*/);
            $dataInvoice=json_decode($requestInvoice->body);
            $invoice=$dataInvoice->invoice;
            var_dump($invoice);
            var_dump($invoice->taxes);
        }
       /* echo '</body>
                  </html>';*/

    }else{
        echo 'error';
    }

}else{
    echo 'token pas valide';
    header('Location: http://localhost/Stage2017/zoho/index.html');
}
