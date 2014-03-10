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
  'startAt' => '2014-03-13T16:30:00',
  'endAt' => '2014-03-13T16:30:00',
  'smallImageUrl' => 'http://www.google.com',
  'mediumImageUrl' => 'http://www.google.com',
  'largeImageUrl' => 'http://www.gogole.com',
  'sidebarImageUrl' => 'http://www.google.com',
  'grid4ImageUrl' => 'http://www.google.com',
  'grid6ImageUrl' => 'http://www.google.com',
  'pitchHtml' => '<p>This is the pitch</p>',
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

$ennode = new stdClass();

$ennode->title = 'Test node';
$ennode->type = 'groupon_deals';
node_object_prepare($ennode);
$ennode->language = 'en';
$ennode->uid = $user->uid;
$ennode->status = 1; // published or not
//$ennode->promote = 0;
//$ennode->comment = 0;

$ennode->body['und'][0]['value'] = "<p>hello world</p> <pre>stuff <code></pre>";
$ennode->body['und'][0]['format'] = 'full_html';

$terms = taxonomy_get_term_by_name('Gold Coast', 'location');
$term = array_pop($terms);
$ennode->field_location['und'][]['tid'] = $term->tid;

$ennode->field_pitch['und'][0]['value'] = "<p>hello world</p> <pre>stuff <code></pre>";
$ennode->field_pitch['und'][0]['format'] = 'full_html';
          
node_save($ennode);




$zhnode = new stdClass();

$zhnode->title = '测试节点';
$zhnode->type = 'groupon_deals';
node_object_prepare($zhnode);
$zhnode->language = 'zh-hans';
$zhnode->uid = $user->uid;
$zhnode->status = 1; // published or not
//$zhnode->promote = 0;
//$zhnode->comment = 0;

$zhnode->body['und'][0]['value'] = "<p>测试节点</p> <pre>测试节点</pre>";
$zhnode->body['und'][0]['format'] = 'full_html';

$terms = taxonomy_get_term_by_name('Gold Coast', 'location');
$term = array_pop($terms);
$zhnode->field_location['und'][]['tid'] = $term->tid;

$zhnode->field_pitch['und'][0]['value'] = "<p>测试节点</p> <pre>测试节点</pre>";
$zhnode->field_pitch['und'][0]['format'] = 'full_html';

$zhnode->tnid = $ennode->nid;          
node_save($zhnode);

$ennode->tnid = $ennode->nid;
node_save($ennode);