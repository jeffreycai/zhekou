<?php

/**
 * Dispatch to a controller
 * 
 * @param type $controller
 */
function dispatch($controller, $vars = array()) {
  $controller_file = dirname(__FILE__) . DS . 'controllers' . DS . $controller . ".php";
  if (is_file($controller_file)) {
    require_once $controller_file;
  } else {
    die("Controller '$controller' does not exist.");
  }
}


/**
* Get current page url
*/
function get_cur_page_url() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 // a hack for pdrupal production, the $_SERVER["SERVER_NAME"] shows ip address instead of domain in pdrupal box.
 // we replace the ip with domain here
 $pageURL = str_replace('147.200.5.46', 'intranet.transport.nsw.gov.au', $pageURL);
 $pageURL = str_replace('pdrupal', 'intranet.transport.nsw.gov.au', $pageURL);
 return $pageURL;
}

/**
* Build GET query string from drupal_get_query_parameters()
*/
function build_query_string($params) {
  $rtn = array();
  foreach ($params as $key => $val) {
    if (empty($val)) {
      continue;
    }
    
    // we only build query string with the params related with phonelist application
    if (!in_array($key, array('page', 'sort', 'keyword', 'shortcut', 'id', 'order', 'agency'))) {
      continue;
    }
    
    $key = urlencode(strip_tags($key));
    $val = urlencode(strip_tags($val));
    $rtn[] = $key.'='.$val;
  }
  return '?'.implode('&', $rtn);
}

/**
* Update a $_REQUEST parameter value and output the query string
*/
function update_query_string($input) {
  $url = explode('?', get_cur_page_url()); 
  $url = $url[0];
  $params = $_REQUEST;
  foreach ($input as $key => $val) {
    $params[$key] = $val;
  }
  return $url . build_query_string($params);
}

function _debug($var) {
  echo "<pre>";
  var_dump($var);
  die("</pre>");
}


function preprocess_sql ($sql) {
  global $mysqli;
  return $mysqli->escape_string($sql);
}


/**
 * Setup a Massage
 * 
 * @param type $type
 * @param type $text
 */
function setMsg($type, $text) {
  $_SESSION[$type] = $text;
}

/**
 * Get a Massage and clear it
 * 
 * @param type $type
 * @return type
 */
function getMsg($type) {
  $msg = null;
  if (isset($_SESSION[$type])) {
    $msg = $_SESSION[$type];
    unset($_SESSION[$type]);
  }
  return $msg;
}

/**
 * render a single massage
 * 
 * @param type $type
 */
function renderMsg($type) {
  $msg = getMsg($type);
  if ($msg) {
    $rtn = "
<div id='msg' class='$type'>
  <div class='inner'>$msg</div>
</div>
";
  }
  return isset($rtn) ? $rtn : '';
}

/**
 * render all messages
 */
function renderMsgs() {
  $msgs = renderMsg(MSG_SUCCESS);
  $msgs.= renderMsg(MSG_WARNING);
  $msgs.= renderMsg(MSG_ERROR);

  return $msgs;
}

function get_file_upload_limit() {
  return round(ini_get('upload_max_filesize') / 1000) . "KB";
}

/**
 * Check if the current user is login or not
 */
function isLogin() {
  return isset($_SESSION['login']) && $_SESSION['login'] == true;
}

/**
 * Login the user
 */
function login($username) {
  $_SESSION['login'] = true;
  $_SESSION['username'] = $username;
}

/**
 * Logout the user
 */
function logout() {
  unset($_SESSION['login']);
  unset($_SESSION['username']);
  HTML::forward('/login');
}

/**
 * Get ip address of the user
 * 
 * @return type
 */
function get_ip_address() {
  return $_SERVER['REMOTE_ADDR'];
}

/**
 * return the current time in MySQL DATETIME format
 */
function timestamp() {
  return date('Y-m-d H:i:s');
}

/**
 * get the current login username
 */
function get_login_username() {
  if (isset($_SESSION['username'])) {
    return $_SESSION['username'];
  }
  return null;
}