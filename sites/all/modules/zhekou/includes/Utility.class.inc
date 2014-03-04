<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Setting.class.inc';

class Utility {
  public static function getInstance() {
    static $inst = null;
    if ($inst === null) {
      $inst = new Utility();
    }
    return $inst;
  }

  private function __construct() {

  }

  /**
   * Load all settings
   */
  public function loadSettings($module) {
    $settings = Setting::getInstance();
    $settings->load($module);
  }
  
  /**
   * Get settings
   */
  public function getSetting($path = "") {
    $settings = Setting::getInstance();
    return $settings->get($path);
  }
  
  /**
   * get a page crawler
   * @return \Crawler
   */
  public function getCrawler() {
    module_load_include('inc', 'zhekou', 'includes/Crawler.class');
    
    $crawler = new Crawler();
    return $crawler;
  }
  
  /**
   * get a dom object out of a html text content
   * 
   * @param type $content
   */
  public function getDom($content) {
    module_load_include('php', 'zhekou', 'libraries/simple_html_dom');
    return str_get_html($content);
  }
  
  
  public function _debug($var) {
    echo "<pre>";
    print_r($var);
    die("</pre>");
  }
}

