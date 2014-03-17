<?php
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SCRIPT_NAME'] = '/debug.php';

define('DRUPAL_ROOT','/var/webs/zhekou.websitesydney.net');
require_once(DRUPAL_ROOT . '/includes/bootstrap.inc');
$variables['url'] = 'http://www.iwantmydeal.com';
drupal_override_server_variables($variables);
chdir(DRUPAL_ROOT);
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

global $user;

$item = array(
  'rid' => '12345',
  'title' => 'Best deal in town and you dont want to miss',
  'dealUrl' => 'http://www.google.com',
  'location' => 'Sydney',
  'startAt' => '2014-03-13T16:30:00',
  'endAt' => '2014-03-13T16:30:00',
  'smallImageUrl' => 'http://www.google.com',
  'mediumImageUrl' => 'http://www.google.com',
  'largeImageUrl' => 'http://www.gogole.com',
  'sidebarImageUrl' => 'http://www.google.com',
  'grid4ImageUrl' => 'http://www.google.com',
  'grid6ImageUrl' => 'http://www.google.com',
  'highlightsHtml' => '<p>This is the Highlight</p>',
  'pitchHtml' => '<p>This is the pitch</p>',
  'description' => '<p>I am dscription</p>',
  'shortAnnouncementTitle' => 'Best deal in town',
  'announcementTitle' => 'Best deal in town',
  'newsletterTitle' => 'Best deal in town',
  'discountPercent' => '80%',
  'isSoldOut' => 0,
  'discount' => 30,
  'price' => 33,
  'value' => 90,
  'finePrint' => "<h3>Fine print</h3><p>This is the fine print and you should know</p>",
);

// create both en and zh-hans node
$nodes = array();
foreach (array('en', 'zh-hans') as $lang) {
  $node = new stdClass();
  $node->title = $item['title'];
  $node->type = 'groupon_deals';
  node_object_prepare($node);
  $node->language = $lang;
  $node->uid = 1;
  $node->status = 1; // published or not
  //$ennode->promote = 0;
  //$ennode->comment = 0;
  $node->field_short_title = $item['newsletterTitle'];
  $node->field_highlight['und'][0]['value'] = $item['highlightsHtml'];
  $node->field_highlight['und'][0]['format'] = 'full_html';
  $node->field_fine_print['und'][0]['value'] = $item['finePrint'];
  $node->field_fine_print['und'][0]['format'] = 'full_html';
  $node->field_description['und'][0]['value'] = $item['description'];
  $node->field_description['und'][0]['format'] = 'full_html';
  $node->field_pitch['und'][0]['value'] = $item['pitchHtml'];
  $node->field_pitch['und'][0]['format'] = 'full_html';
  $node->field_price['und'][0]['value'] = $item['price'];
  $node->field_discount['und'][0]['value'] = $item['discount'];
  $node->field_deal_url['und'][0]['value'] = $item['dealUrl'];
  $node->field_deal_ends_at['und'][0]['value'] = $item['endAt'];
  $node->field_featured_image['und'][0]['value'] = $item['largeImageUrl'];
  $node->field_value['und'][0]['value'] = $item['value'];
  if ($terms = taxonomy_get_term_by_name($item['location'], 'location')) {
    $term = array_pop($terms);
    $node->field_location['und'][0]['tid'] = $term->tid;
  }
  if ($terms = taxonomy_get_term_by_name('Groupon', 'vendor')) {
    $term = array_pop($terms);
    $node->field_location['und'][0]['tid'] = $term->tid;
  }
  node_save($node);
  $nodes[$lang] = $node;
}

$nodes['en']->tnid = $nodes['en']->nid;
$nodes['zh-hans']->tnid = $nodes['en']->nid;
$nodes['zh-hans']->status = 0;

node_save($nodes['en']);
node_save($nodes['zh-hans']);
