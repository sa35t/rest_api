<?php

    /*
    * Helper function used to
    * get the http_body of the Request
    */

    function grab_http_body()
    {
        $body = file_get_contents("php://input");
        if($body)
        {
            /* Jecoding Json data */

            $body_params = json_decode($body);
            return $body_params;
        }
        else
        {
            return false;
        }
    }


 ?>