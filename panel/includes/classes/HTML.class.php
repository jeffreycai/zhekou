<?php
class HTML {
  public function render($tpl, $param = array()) {
    if (!is_array($param)) {
      return;
    }

    // assign template vars
    $data = new PARAM();
    foreach ($param as $key => $val) {
      $data->$key = $val;
    }
    ob_start();
    include(TPL_DIR . DS . $tpl . '.tpl.php');
    $content = ob_get_clean();
    return $content;
  }
  
  public function renderOut($tpl, $param = array()) {
    echo $this->render($tpl, $param);
  }

  public function output($str) {
    echo "\n" . $str;
  }
  
  /**
   * redirect to $_SERVER['HTTP_REFERER']
   * 
   * @param type $no_cache
   */
  static function forwardBackToReferer($no_cache = false) {
    self::forward($_SERVER['HTTP_REFERER']);
    exit;
  }
  
  /**
   * Forward page
   * 
   * @param type $no_cache
   */
  static function forward($destination, $no_cache = false) {
    if ($no_cache) {
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
    }
    
    // if no referer, we forward to homepage
    if (empty($destination)) {
      header('Location: /');
    } else {
      header('Location: ' . $destination);
    }
    
    exit;
  }
  
  /**
   * send back a json response
   * 
   * @param type $content
   */
  static function sendJSONresponse($content) {
    header('Content-type: application/json');
    echo $content;
    exit;
  }
}

class PARAM {
  function __get($name) {
    // this is to prevent php notice message
    return isset($this->$name) ? $this->$name : null;
  }
}
