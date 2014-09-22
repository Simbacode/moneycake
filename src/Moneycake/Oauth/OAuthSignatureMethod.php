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
 * Method Used for encryption
 */
class OAuthSignatureMethod {
  public function check_signature(&$request, $consumer, $token, $signature) {
    $built = $this->build_signature($request, $consumer, $token);
    return $built == $signature;
  }
}
?>