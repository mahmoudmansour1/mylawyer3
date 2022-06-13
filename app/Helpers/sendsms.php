<?php
if ( ! function_exists( 'sendsms' ) ) {
    /**
     * Get Total Refunded Amount order
     * @param $id
     *
     * @return  float|integer
     */
    function sendsms( $phone , $message) {


        $data = array(
            'username' => 'mylawyer',
            'password' => 'rashid66800004',
            'customerid' => '3049',
            'senderText' => 'Mylawyer',
            'messageBody' => $message,
            'recipientNumbers' => $phone,
            'defdate' => '',
            'isBlink' => 'false',
            'isFlash' => 'false'
        );
        $url = 'https://smsbox.com/smsgateway/services/messaging.asmx/Http_SendSMS?username=mylawyer&password=rashid66800004&customerid=3049&sendertext=My%20Lawyer&messagebody='.$message.'&recipientnumbers='.$phone.'&defdate=&isblink=false&isflash=false';
     //   $url = 'http://google.com';

        //Use file_get_contents to GET the URL in question.
        //$contents = file_get_contents($url);
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, 0);

        // $response = curl_exec($ch);
        //     curl_close($ch); // Close the connection
        //     return $response;


        // $ch = curl_init();

        // //Set the URL that you want to GET by using the CURLOPT_URL option.
        // curl_setopt($ch, CURLOPT_URL, $url);
        
        // //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        // //Execute the request.
        // $response = curl_exec($ch);
        //      return $response;




        $url = "https://smsbox.com/smsgateway/services/messaging.asmx/Http_SendSMS?username=mylawyer&password=rashid66800004&customerid=3049&sendertext=My%20Lawyer&messagebody=".$message."&recipientnumbers=".$phone."&defdate=&isblink=false&isflash=false";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
           "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        var_dump($resp);
    }
}
