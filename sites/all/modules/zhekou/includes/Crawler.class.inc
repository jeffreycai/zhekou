<?php
class Crawler {
  private $cookie_path;
  private $agent;
  
  public function __construct() {
    $this->setUserAgent();
  }
  
  public function setCookiePath($path) {
    $this->cookie_path = $path;
  }
  
  public function getCookiePath() {
    return $this->cookie_path;
  }
  
  public function removeCookie() {
    if (is_file($this->cookie_path)) {
      unlink($this->cookie_path);
    }
  }
  
  public function setUserAgent($agent = null) {
    if (is_null($agent)) {
      $agent = 'Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0';
    }
    
    $this->agent = $agent;
  }
  
  public function getUserAgent() {
    return $this->agent;
  }
  
  public function read($url, $cookie_path = null, $headers = false, $tor = false) {
    if (is_null($cookie_path)) {
      $cookie_path = $this->getCookiePath();
    }
    
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // whether to print out or not when curl_exec();
    curl_setopt($ch, CURLOPT_HEADER, 0); // whether to include HEADER in output
    curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
    // set cookie if passed
    if ($cookie_path) {
      curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path); // where to put cookie after curl_close()
    }
    // set to use tor if passed
    if ($tor) {
      curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:9050");
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    // use given headers if passed
    if ($headers) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
  
  public function post($url, $post_data, $cookie_path = null, $headers = false, $tor = false) {
    if (is_null($cookie_path)) {
      $cookie_path = $this->getCookiePath();
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // whether to print out or not when curl_exec();
    curl_setopt($ch, CURLOPT_HEADER, 0); // whether to include HEADER in output
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path); // where to put cookie after curl_close()
    curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Follow redirect or not
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Max redirects to follow. Use it along with CURLOPT_FOLLOWLOCATION
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // use given headers if passed
    if ($headers) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    // set to use tor if passed
    if ($tor) {
      curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:9050");
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
}