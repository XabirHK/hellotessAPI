<?php
require "../bootstrap.php";

use Src\controllers\InvoiceController;

//
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//get the url
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//dived the url in sub parts
$uri = explode( '/', $uri );
$requestMethod = $_SERVER["REQUEST_METHOD"];


//manage the end points to send  
$url1 = 'http://example/api/v1/test';
$url2 = 'http://example/api/v1/user';


//validating the url
if($uri[1] !== 'v1'){
    header("HTTP/1.1 404 Not Found");
    exit();
} 

//simple routing protocal
if($uri[1] == 'v1' && $uri[2] == 'invoice'){
    $jsonBody = json_decode(file_get_contents('php://input'), true);
    $invoiceController = new InvoiceController($requestMethod, $jsonBody, $url1);
    $invoiceController->processRequest();
}

//for getting the json of articleGroupname List
if($uri[1] == 'v1' && $uri[2] == 'article-group' && $uri[3]!=''){
    $articleID = $uri[3];
    
    $articleGroupData  = file_get_contents('articleGroupName.txt');
    // $invoiceController = new InvoiceController($requestMethod, $jsonBody);
    // $invoiceController->processRequest();
    print_r($articleGroupData);
}







//print_r($uri);
//echo "this is public index";

?>
