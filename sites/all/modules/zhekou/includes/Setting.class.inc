<?php
class Setting {
  private $vars;
  private $loaded;
    
  public static function getInstance() {
    static $inst = null;
    if ($inst === null) {
      $inst = new Setting();
    }
    return $inst;
  }

  private function __construct() {
    $this->vars = array();
    $this->loaded = array();
  }
  
  /**
   * Load module settings to global singleton Setting instance
   * @param type $module
   */
  public function load($module) {
    // do not need to load if already loaded
    if (in_array($module, $this->loaded)) {
      return;
    }
    
    // load spyc lib to parse yaml file
    module_load_include('php', 'zhekou', 'libraries/spyc');
    $Spyc  = new Spyc;
    
    $module_settings_path = DRUPAL_ROOT . '/' . drupal_get_path('module', $module) . '/' . 'conf' . '/' . 'settings.yml';
    // don't do it if settings file does not exist
    if (!is_file($module_settings_path)) {
      return;
    }
    
    $setting = $Spyc->loadFile($module_settings_path);
    $this->set($setting);
    
    $this->loaded[] = $module;
  }
  
  /**
   * Set settings
   * @param type $arr
   */
  public function set($arr) {
    $this->vars = array_merge_recursive($this->vars, $arr);
  }
  
  /**
   * get settings
   * @param string $path, indicating the array keys of the setting that needs to be fetched
   * @return type
   */
  public function get($path = "") {
    if ($path == "") {
      return $this->vars;
    }

    $tokens = explode('->', $path); 
    $rtn = $this->vars;
    foreach ($tokens as $key) {
      $rtn = $rtn[$key];
    }
    return $rtn;
  }
}
