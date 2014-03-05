<?php
/**
 * Create a taxonomy if not exists
 * 
 * @global type $util
 * @param type $key
 * @param type $name
 */
function _create_taxononmy($key, $name, $module_that_has_the_settings) {
  // check if vocabulary 'location' exists, if no, create it and terms
  $vs = taxonomy_get_vocabularies();
  $exists = false;
  foreach ($vs as $v) {
    if ($v->machine_name == $key) {
      $exists = true;
    }
  }
  
  if ($exists == false) {
    // create a vocabulary to hold location
    $vocabulary = new stdClass();
    $vocabulary->name = $name;
    $vocabulary->machine_name = $key;
    taxonomy_vocabulary_save($vocabulary);
    $vid = $vocabulary->vid;

    // create suburb terms
    module_load_include('inc', 'zhekou', 'includes/Utility.class');
    global $util;
    if (!isset($util)) {
      $util = Utility::getInstance();
    }
    $util->loadSettings('zhekou');

    foreach ($util->getSetting($module_that_has_the_settings . '->' . $key) as $location) {
      $term = new stdClass();
      $term->name = $location;
      $term->vid = $vid;
      taxonomy_term_save($term);
    }
  }
}