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

class OAuthSignatureMethod_PLAINTEXT extends OAuthSignatureMethod {
  public function get_name() {
    return "PLAINTEXT";
  }

  public function build_signature($request, $consumer, $token) {
    $sig = array(
      OAuthUtil::urlencode_rfc3986($consumer->secret)
    );

    if ($token) {
      array_push($sig, OAuthUtil::urlencode_rfc3986($token->secret));
    } else {
      array_push($sig, '');
    }

    $raw = implode("&", $sig);
    // for debug purposes
    $request->base_string = $raw;

    return OAuthUtil::urlencode_rfc3986($raw);
  }
}
?>