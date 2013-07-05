<?php
/**
 * Jobqueue: Job queue library for FuelPHP.
 *
 * @package    Jobqueue
 * @author     Keishi HOSOBA
 * @license    MIT License
 * @copyright  2013 Keishi HOSOBA
 * @link       https://github.com/hosopy/fuel-jobqueue
 */
Autoloader::add_core_namespace('Jobqueue');

if (file_exists(__DIR__.'/vendor/autoload.php'))
{
    require_once __DIR__.'/vendor/autoload.php';
}

Autoloader::add_classes(array(
	'Jobqueue\\Queue'                     => __DIR__.'/classes/queue.php',
	'Jobqueue\\Context'                   => __DIR__.'/classes/context.php',
	'Jobqueue\\Worker'                    => __DIR__.'/classes/worker.php',
	'Jobqueue\\Listener'                  => __DIR__.'/classes/listener.php',
	'Jobqueue\\Connector_Driver'          => __DIR__.'/classes/connector/driver.php',
	'Jobqueue\\Connector_Beanstalkd'      => __DIR__.'/classes/connector/beanstalkd.php',
	'Jobqueue\\Queue_Manager'             => __DIR__.'/classes/queue/manager.php',
	'Jobqueue\\Queue_Driver'              => __DIR__.'/classes/queue/driver.php',
	'Jobqueue\\Queue_Base'                => __DIR__.'/classes/queue/base.php',
	'Jobqueue\\Queue_Beanstalkd'          => __DIR__.'/classes/queue/beanstalkd.php',
	'Jobqueue\\Job_Driver'                => __DIR__.'/classes/job/driver.php',
	'Jobqueue\\Job_Base'                  => __DIR__.'/classes/job/base.php',
	'Jobqueue\\Job_Beanstalkd'            => __DIR__.'/classes/job/beanstalkd.php',
));
