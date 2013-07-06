<?php
/**
 * fuel-jobqueue
 *
 * @package    Jobqueue
 * @author     Keishi HOSOBA
 * @license    MIT License
 * @copyright  2013 Keishi HOSOBA
 * @link       https://github.com/hosopy/fuel-jobqueue

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 */

return array(
	// default connection name
	'default' => 'default_connection',

	'connections' => array(
		// name => array(...)
		'default_connection' => array(
			'driver'   => 'beanstalkd',
			'host'     => '127.0.0.1',
			'port'     => '11300',
			'queue'    => 'jobqueue',
		),
		'debug_connection' => array(
			'driver'   => 'sync',
		),
		/*
		 in the future...
		'resque_connection' => array(
			'driver' => 'resque',
		),
		'sqs_connection' => array(
			'driver' => 'sqs',
		),
		*/
	),
);
