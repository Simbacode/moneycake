<?php

/*
 * This file is part of the Moneycake package.
 *
 * (c) Acellam Guy <abiccel@yahoo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Simbacode\Moneycake;

use Simbacode\Moneycake\Oauth\OAuthSignatureMethod_HMAC_SHA1;
use Simbacode\Moneycake\Oauth\OAuthConsumer;
use Simbacode\Moneycake\Oauth\OAuthRequest;
use Cake\Network\Http\Client;

/**
 * This is main Pesapal Oauth 1.0 cakePHP class
 *
 * @author Acellam Guy
 * @version 0.0.1
 */
class Pesapal {

    //pesapal params
    private $token = NULL;
    private $params = NULL;

    /*
      PesaPal Sandbox is at http://demo.pesapal.com. Use this to test your developement and
      when you are ready to go live change to https://www.pesapal.com.
     */
    private $consumer_key = 'Your PesaPal Merchant Consumer Key'; //Register a merchant account on
    //demo.pesapal.com and use the merchant key for testing.
    //When you are ready to go live make sure you change the key to the live account
    //registered on www.pesapal.com!
    private $consumer_secret = 'Your PesaPal Merchant Consumer Secret'; // Use the secret from your test
    //account on demo.pesapal.com. When you are ready to go live make sure you 
    //change the secret to the live account registered on www.pesapal.com!
    private $signature_method = NULL;
    private $url = 'http://demo.pesapal.com/api/PostPesapalDirectOrderV4'; //change to    
    private $http = NULL;
    private $OauthRequest = NUll;
    private $OauthConsumer = NUll;
    private $HttpRequest = NUll;

    //https://www.pesapal.com/API/PostPesapalDirectOrderV4 when you are ready to go live!

    /**
     * Contructor for the pespal class
     * @param String $production 
     *                  used to specify if you are running in production or not
     * @param String $consumer_key
     *                  The key used to access the API
     * @param String $consumer_secret
     *                  The secret/password used to accesst the API
     * @return void 
     */
    public function __construct($production = FALSE, $consumer_key = NULL, $consumer_secret = NULL) {
        $this->signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $production == TRUE ? $this->http = "https://www." : $this->http = "http://demo.";
    }

    /**
     * A getter method used to return the Oauth request that was made to pesapal server
     * @return OAuthRequest
     */
    public function getOauthRequest() {
        return $this->OauthRequest;
    }
       /**
     * A getter method used to return the request that was made to pesapal server
     * @return \Cake\Network\Http\Request
     */
    public function getHttpRequest() {
        return $this->HttpRequest;
    }
       /**
     * A getter method used to return the OauthConsumer used
     * @return OAuthConsumer
     */
    public function getOauthConsumer() {
        return $this->OauthConsumer;
    }

    /**
     * Makes request to the pesapal server and returns the response that
     * contains an iframe for making payment.
     * 
     * Use this to post a transaction to PesaPal. PesaPal will return a response
     * with a page which contains the available payment options and will
     * redirect to your site once the user has completed the payment process. A
     * tracking id will be returned as a query parameter � this can be used
     * subsequently to track the payment status on pesapal for this transaction.
     *
     * @param String  $callback_url 
     *                  The URL on your server that is used to process sucessful
     *                  payment.
     * @param String $amount
     *                  the amount of money for the good/service
     * @param String $desc
     *                  description of what is being paid for good or service
     * @param String $type  
     *                  the type of pesapal account eg Merchant
     * @param String $reference
     *                  the unique id to your request.
     * @param email
     * @param phonenumber
     * @param first_name
     * @param last_name
     * @return OAuthRequest
     */
    public function PostPesapalDirectOrderV4($callback_url, $amount,$currency, $desc, $type, $reference, $email, $phonenumber, $first_name, $last_name) {

        $this->url = $this->http . "pesapal.com/api/PostPesapalDirectOrderV4";
        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Currency=\"".$currency."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
        $post_xml = h($post_xml);


        $this->OauthConsumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        //post transaction to pesapal
        $this->OauthRequest = OAuthRequest::from_consumer_and_token($this->OauthConsumer, $this->token, "GET", $this->url, $this->params);
        $this->OauthRequest->set_parameter("oauth_callback", $callback_url);
        $this->OauthRequest->set_parameter("pesapal_request_data", $post_xml);
        $this->OauthRequest->sign_request($this->signature_method, $this->OauthConsumer, $this->token);
        
        return $this->OauthRequest;
    }

    /**
     * Makes request to the pesapal server and returns the response that
     * contains All the OATH details.
     * 
     * Use this to post a transaction to PesaPal. PesaPal will return a response
     * with a page which contains the available payment options and will
     * redirect to your site once the user has completed the payment process. A
     * tracking id will be returned as a query parameter � this can be used
     * subsequently to track the payment status on pesapal for this transaction.
     *
     * @param String  $callback_url 
     *                  The URL on your server that is used to process sucessful
     *                  payment.
     * 
     * @param String $post_xml
     *            The pespal XML formated order data.Take not of space.
     * return OAuthRequest
     */
    public function PostPesapalDirectOrderV4XML($callback_url, $post_xml) {
        $this->url = $this->http . "pesapal.com/api/PostPesapalDirectOrderV4";

        $post_xml = h($post_xml);

        $this->OauthConsumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        //post transaction to pesapal
        $this->OauthRequest = OAuthRequest::from_consumer_and_token($this->OauthConsumer, $this->token, "GET", $this->url, $this->params);
        $this->OauthRequest->set_parameter("oauth_callback", $callback_url);
        $this->OauthRequest->set_parameter("pesapal_request_data", $post_xml);
        $this->OauthRequest->sign_request($this->signature_method, $this->OauthConsumer, $this->token);
        
        return $this->OauthRequest;
    }

    /**
     * Use this to query the status of the transaction. When a transaction is
     * posted to PesaPal, it may be in a PENDING, COMPLETED or FAILED state. If
     * the transaction is PENDING, the payment may complete or fail at a later
     * stage. Both the unique order id generated by your system and the pesapal
     * tracking id are required as input parameters.
     * 
     * @param String $reference
     *                  the order id/ reference id you created during
     *                  {@link Pesapal#PostPesapalDirectOrderV4}
     * @param String $trackingId
     *                  the reference that was returned by pesapal server during post
     *                  order
     * @return String containing the payment status
     */
    public function QueryPaymentStatus($reference, $trackingId) {

        $this->url = $this->http . "pesapal.com/API/QueryPaymentStatus";

        $this->OauthConsumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        //post transaction to pesapal
        $this->OauthRequest = OAuthRequest::from_consumer_and_token($this->OauthConsumer, $this->token, "GET", $this->url, $this->params);
        $this->OauthRequest->set_parameter("pesapal_merchant_reference", $reference);
        $this->OauthRequest->set_parameter("pesapal_transaction_tracking_id", $trackingId);
        $this->OauthRequest->sign_request($this->signature_method, $this->OauthConsumer, $this->token);

        $this->HttpRequest = new Client();
        $response = $this->HttpRequest->get($this->OauthRequest->to_url());
        return $response;
    }

    /**
     * Same as {@link Pesapal#QueryPaymentStatus(String, String)}, but only the
     * unique order id generated by your system is required as the input
     * parameter.
     * 
     * @param String $reference
     *                  the unique id generated by your app
     * @return String containing the payment status
     */
    public function QueryPaymentStatusByMerchantRef($reference) {
        $this->url = $this->http . "pesapal.com/API/QueryPaymentStatusByMerchantRef";

        $this->OauthConsumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        //post transaction to pesapal
        $this->OauthRequest = OAuthRequest::from_consumer_and_token($this->OauthConsumer, $this->token, "GET", $this->url, $this->params);
        $this->OauthRequest->set_parameter("pesapal_merchant_reference", $reference);
        $this->OauthRequest->sign_request($this->signature_method, $this->OauthConsumer, $this->token);

        $this->HttpRequest = new Client();
        $response = $this->HttpRequest->get($this->OauthRequest->to_url());
        return $response;
    }

    /**
     * Same as {@link Pesapal#QueryPaymentStatus(String, String)}, but
     * additional information is returned.
     * 
     * @param String $reference
     *                  the order id/ reference id you created during
     *                  {@link Pesapal#PostPesapalDirectOrderV4}
     * @param String $trackingId
     *                  the reference that was returned by pesapal server during post
     *                  order
     * @return String containing the payment status
     */
    public function QueryPaymentDetails($reference, $trackingId) {

        $this->url = $this->http . "pesapal.com/API/QueryPaymentDetails";

        $this->OauthConsumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        //post transaction to pesapal
        $this->OauthRequest = OAuthRequest::from_consumer_and_token($this->OauthConsumer, $this->token, "GET", $this->url, $this->params);
        $this->OauthRequest->set_parameter("pesapal_merchant_reference", $reference);
        $this->OauthRequest->set_parameter("pesapal_transaction_tracking_id", $trackingId);
        $this->OauthRequest->sign_request($this->signature_method, $this->OauthConsumer, $this->token);

        $this->HttpRequest = new Client();
        $response = $this->HttpRequest->get($this->OauthRequest->to_url());
        return $response;
    }

    /**
     * This is to be used with an action configured to receive IPN as indicated
     * by pesapal. When the action recieves request from Pesapal, Get the 
     * parameters and pass the details to this function.
     * you will receive transaction status in the format:
     * pesapal_notification_type=CHANGE&pesapal_transaction_tracking_id =<the
     * unique tracking id of the transaction>&pesapal_merchant_reference=<the
     * merchant reference>. Also remember to parse the header of this response
     * to get the payment status.
     * 
     * After that remember to send back a response to pesapal in the same format
     * using your web app.This is to acknowledge the receipt of the sent IPN.
     * Send back the response after doing some things such as updating records
     * in your data store.
     *
     *
     * @param String $notificationType
     *                  this one of the notification types specified by pesapal
     * @param String $reference
     *                  the order id/ reference id you created during
     *                  {@link Pesapal#PostPesapalDirectOrderV4}
     * @param trackingId
     *                  the reference that was returned by pesapal server during 
     *                  post
     * @param Boolean $printResponse payment response to be printed
     * 
     * @param function $callback a function to indicated that database update 
     *                  was successfull
     * 
     *                  order
     * @return a boolean yes to indicate successfull IPN of failure. Null for nothing
     */
    public function InstantPaymentNotification($notificationType, $reference, $trackingId,$printResponse=FALSE, $callback) {

        $this->url = $this->http . "pesapal.com/api/querypaymentstatus";

        if ($notificationType == "CHANGE" && $trackingId != "") {

            $this->OauthConsumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

            //get transaction status
            $this->OauthRequest = OAuthRequest::from_consumer_and_token($this->OauthConsumer, $this->token, "GET", $this->url, $this->params);
            $this->OauthRequest->set_parameter("pesapal_merchant_reference", $reference);
            $this->OauthRequest->set_parameter("pesapal_transaction_tracking_id", $trackingId);
            $this->OauthRequest->sign_request($this->signature_method, $this->OauthConsumer, $this->token);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->OauthRequest);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (defined('CURL_PROXY_REQUIRED'))
                if (CURL_PROXY_REQUIRED == 'True') {
                    $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
                    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                }

            $response = curl_exec($ch);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header = $headerArray[count($headerArray) - 1];

            //transaction status
            $elements = preg_split("/=/", substr($response, $header_size));
            $status = $elements[1];
            curl_close($ch);

            //UPDATE YOUR DB TABLE WITH NEW STATUS FOR TRANSACTION WITH pesapal_transaction_tracking_id $pesapalTrackingId
            if ($callback($status,$trackingId)) {
                if($printResponse){
                  $resp = "pesapal_notification_type=$notificationType&pesapal_transaction_tracking_id=$trackingId&pesapal_merchant_reference=$reference";
                  ob_start();
                  echo $resp;
                  ob_flush();
                  exit;
                    
                }
            } 
        } else {
            return null;
        }
    }
    

}
