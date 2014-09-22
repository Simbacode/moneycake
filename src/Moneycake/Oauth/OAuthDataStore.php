<?php

/*
 * This file is part of the Moneycake package.
 *
 * (c) Acellam Guy <abiccel@yahoo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Simbacode\Moneycake\Oauth;

/**
 * This classs is used to manage the consumer tokens
 */
class OAuthDataStore {

    /**
     * 
     * @param type $consumer_key
     */
    function lookup_consumer($consumer_key) {
        // implement me
    }

    /**
     * 
     * @param type $consumer
     * @param type $token_type
     * @param type $token
     */
    function lookup_token($consumer, $token_type, $token) {
        // implement me
    }

    /**
     * 
     * @param type $consumer
     * @param type $token
     * @param type $nonce
     * @param type $timestamp
     */
    function lookup_nonce($consumer, $token, $nonce, $timestamp) {
        // implement me
    }

    /**
     * 
     * @param type $consumer
     */
    function new_request_token($consumer) {
        // return a new token attached to this consumer
    }

    /**
     * 
     * @param type $token
     * @param type $consumer
     */
    function new_access_token($token, $consumer) {
        // return a new access token attached to this consumer
        // for the user associated with this token if the request token
        // is authorized
        // should also invalidate the request token
    }

}

?>