<?php
namespace Src;

class Helper {
    public function readJson($arr) {
        global $count;
        global $values;

        
        // Check input is an array
        if(!is_array($arr)){
            die("ERROR: Input is not an array");
        }
        
        /*
        Loop through array, if value is itself an array recursively call the
        function else add the value found to the output items array,
        and increment counter by 1 for each value found
        */
        foreach($arr as $key=>$value){
            if(is_array($value)){
                $this->readJson($value);
            } else{
                $values[] = $value;
                $count++;
            }
        }
        
        // Return total count and values found in array
        return array('total' => $count, 'values' => $values);
    }


    public function searchKey($search_key, $arr){


        foreach ($arr['articles'] as $i => $details) {
            if (array_key_exists($search_key, $details)) {
                return $arr['articles'][$i][$search_key];
            } else {
                return null;
            }
        }
    }


    public function okResponse($responseJson)
    {   
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = $responseJson;
        //$response['body'] = $result;
        return $response;
    }



    public function unprocessableEntityResponse()
    {   
        //echo "Unprocessable Entity";
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => '422 Unprocessable Entity'
        ]);
        return $response;
    }

    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => 'HTTP/1.1 404 Not Found'
        ]);
        return $response;
    }

    public function badGatwayResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 502 Bad Gateway';
        $response['body'] = json_encode([
            'error' => 'HTTP/1.1 502 Bad Gateway'
        ]);
        return $response;
    }





}


?>