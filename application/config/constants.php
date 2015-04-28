<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


#custom constants
define('DOC_ROOT',	$_SERVER["DOCUMENT_ROOT"]."/turnt-octo-wallhack/");
define('DOC_ROOT_PROFILE_IMG',  "./uploads/profile_images/");
define('DOC_ROOT_CATEGORY_IMG',  "./uploads/category_images/");

define('ADMIN',	'admin');
define('DEAL_USER',	'deal_user');
define('DEAL_CATEGORY',	'deal_category');
define('DEAL_DEALER',	'deal_dealer');
define('DEAL_BUYOUT',	'deal_buyout');
define('DEAL_DETAIL',	'deal_detail');
define('DEAL_FAV',	'deal_fav');
define('DEAL_LINKS',	'deal_links');
define('DEAL_MAP_TAGS',	'deal_map_tags');
define('DEAL_TAGS',	'deal_tags');
define('DEAL_OFFER',	'deal_offer');
define('DEAL_USER_ADDRESS',    'deal_user_address');


define('FROM_EMAIL', 'nirav.ce.2008@gmail.com');
define('FROM_NAME', 'djangodeals.com');
define('SUBJECT_LOGIN_INFO', 'Login info');
define('SUBJECT_DEAL_INFO', 'Deal info');
define('SUBJECT_CONTACT_ADMIN', 'Contact us (djangodeals.com)');
define('SUBJECT_PAYMENT_FAILED', 'Payment failed');


/* End of file constants.php */
/* Location: ./application/config/constants.php */
