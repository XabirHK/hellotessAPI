<?php
namespace Src\controllers;

use Src\models\InvoiceModel;
use Src\Helper;


/**
   * InvoiceController
   * 
   * 
   * @package    Src
   * @subpackage Controller
   * @author     Zabir
   * @param string $requestMethod  type of method get,post etc
   * @param array $jsonBody  Json data
   */


class InvoiceController extends Helper{


    private $requestMethod;
    private $jsonBody;
    private $toSendURL;

    private $invoiceModel;


    public function __construct($requestMethod, $jsonBody, $toSendURL)
    {
        $this->requestMethod = $requestMethod;
        $this->jsonBody = $jsonBody;
        $this->toSendURL = $toSendURL;

        $this->invoiceModel = new InvoiceModel();
    }

    // validate request method
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->reformateInput();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
            }
        //send appropiate response
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }

    }


    
    // reformate the json to the spec
    private function reformateInput(){


        $articlegroupId = $this->searchKey('articleGroupId', $this->jsonBody);

        if($articlegroupId === null){
            $response = $this->unprocessableEntityResponse();
            return $response;
        }else{

            $articleName = $this->invoiceModel->getArticleName($articlegroupId);
            
            if($articleName === null){
                $response = $this->unprocessableEntityResponse();
                return $response;
            }elseif ($articleName == 'uriError') {
                
                $response = $this->badGatwayResponse();
                return $response;
            }

            $results=$this->readJson($this->jsonBody);

            $items = array(
            'plu' => $results['values'][5],
            'articleGroup' => $articleName,
            'name' => $results['values'][7],
            'price' => $results['values'][8]/100,
            'quantity' => $results['values'][9],
            'totalPrice' => $results['values'][10]/100);

            $responseJson= array(
            'invoice' => $this->jsonBody['number'],
            'date' => $this->jsonBody['date'],
            'items' => [$items]);
            
            $responseJson = json_encode($responseJson);
            //print_r($responseJson);

           
            //initialized curl
            $ch = curl_init($this->toSendURL);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $responseJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            // Return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            //if success the result will be empty
            if($result !== false){
                $response = $this->badGatwayResponse();
                return $response;
                
            }
            else{
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = $responseJson;
                //$response['body'] = $result;
                return $response;
            }


            // Close cURL resource
            curl_close($ch);
            
            
        }

        
    }

    
}