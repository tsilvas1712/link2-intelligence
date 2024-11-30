<?php

namespace App\Services;

use App\Jobs\ProcessDatasys;
use App\Jobs\ProcessDatasysJob;
use Carbon\Carbon;
use SimpleXMLElement;

class DatasysService
{
    private $datasysUrl;
    private $datasysToken;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->datasysUrl = env('DATASYS_URL');
        $this->datasysToken = env('DATASYS_TOKEN');
        //
    }

    public function getDatasysData()
    {

        $dateInicial = Carbon::now()->subDays(1)->format('Y-m-d');
        $dateFinal = Carbon::now()->subDays(1)->format('Y-m-d');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->datasysUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <BaixarVendas xmlns="http://tempuri.org/">
                      <Token>' . $this->datasysToken . '</Token>
                      <DataInicial>' . $dateInicial . '</DataInicial>
                      <DataFinal>' . $dateFinal . '</DataFinal>
                    </BaixarVendas>
                  </soap:Body>
                </soap:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
            ),
        ));

        $response = curl_exec($curl);

        $res = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);


        curl_close($curl);
        $xml = new SimpleXMLElement($res);
        $body = $xml->xpath('//soapBody')[0];
        $array = json_decode(json_encode((array)$body), true);

        $responseArray = $array['BaixarVendasResponse']['BaixarVendasResult']['NewDataSet']['Table'];


        foreach ($responseArray as $record) {

            ProcessDatasysJob::dispatch($record);
        }
    }
}
