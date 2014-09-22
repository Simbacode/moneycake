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
 * This is the class that enables the access of the pesapal Oauth server on 
 * behalf of pesapal.
 */
class OAuthConsumer {

    /**
     * 
     * @var String A value used by the Consumer to identify itself to 
     *              the Service Provider. 
     */
    public $key;
    /**
     *
     * @var String A secret used by the Consumer to establish ownership of the 
     *            Consumer Key. 
     */
    public $secret;
    /**
     * 
     * @param String $key
     * @param String $secret
     * @param String $callback_url
     */
    function __construct($key, $secret, $callback_url = NULL) {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;
    }

    /**
     * 
     * @return String of the oauth details
     */
    function __toString() {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }

}

?>