<?php
namespace src\models;

use Src\Helper;

/**
   * InvoiceModel
   * 
   * 
   * @package    Src
   * @subpackage Controller
   * @author     Zabir
   * 
   * get the articlegroup name from arbitary URI
   */


class InvoiceModel extends Helper{


    public function getArticleName($id)
    {
        //set api url
        $url = "http://127.0.0.1:7000/v1/article-group/";

        //call api
        $json = file_get_contents($url);
        $json = json_decode($json, true);

        
        if (!empty($json)){
            foreach($json as $key=>$value){
                if($key == $id){
                    $articleName = $value;
                break;
                }else{
                    $articleName = null;
                }
            }
            return $articleName; 
        }else {
            return $articleName='uriError';
        }
        
    }

   
}