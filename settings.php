<?php

/**
* SMS Validation using SMSgateway.me
*
* @package    SMS_validation
* @author     Fidde.nu
* @version    1.0
* 
*/

/**
* Settings for SMSgateway.me and the database.
* Make sure to run the install.sql script in your database first.
*
* @package    SMS_validation
* @subpackage settings
* @author     Fidde.nu
* @version    1.0
* 
*/

/* DB Settings */
$info['db'] = [
	'host'      =>  '',
	'user'      =>  '',
	'pass'      =>  '',
	'name'      =>  'validation',
	'tblprefix' =>  'val_'
];

/* SMS-gateway Settings */
$info['gw'] = [
	'email'      =>  'email@domain.tld',
	'pass'      =>  'password',
	'deviceID'      =>  '1234'
];