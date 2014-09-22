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

use Simbacode\Moneycake\Oauth\OAuthSignatureMethod;
use Simbacode\Moneycake\Oauth\OAuthUtil;

class OAuthSignatureMethod_HMAC_SHA1 extends OAuthSignatureMethod {
  function get_name() {
    return "HMAC-SHA1";
  }
  /**
   * 
   * @param type $request
   * @param type $consumer
   * @param type $token
   * @return type
   */
  public function build_signature($request, $consumer, $token) {
    $base_string = $request->get_signature_base_string();
    $request->base_string = $base_string;

    $key_parts = array(
      $consumer->secret,
      ($token) ? $token->secret : ""
    );

    $key_parts = OAuthUtil::urlencode_rfc3986($key_parts);
    $key = implode('&', $key_parts);

    return base64_encode(hash_hmac('sha1', $base_string, $key, true));
  }
}


?>

