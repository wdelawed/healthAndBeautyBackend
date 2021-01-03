<?php

namespace App\Http;

use Illuminate\Http\Request;

class NetworkResponse 
{
    const msg = null ;
    const error = null ;
    const data = null ;
    public function __construct($message, $error, $data)
    {
        $this->msg = $message;
        $this->error = $error;
        $this->data = $data;
    
    }

    public static function from($message, $error, $data){
        return  response("{'msg' : $message, 'error' : $error, 'data' : $customers}",200)->header("Content-Type", "application/json");
    }
}
?>