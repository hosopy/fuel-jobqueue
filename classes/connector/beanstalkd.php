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
namespace Jobqueue;

use Pheanstalk_Pheanstalk as Pheanstalk;

class Connector_Beanstalkd implements Connector_Driver
{
	private static $DEFAULT_CONFIG;

	public static function _init()
	{
		static::$DEFAULT_CONFIG = array(
			'host'    => '127.0.0.1',
			'port'    => '11300',
			'queue'   => 'default',
			'timeout' => null,
		);
	}

	/**
	 * Establish a queue connection.
	 *
	 * @param  array  $config
	 * @return \Jobqueue\Queue_Driver
	 */
	public function connect(array $config)
	{
		$config = array_merge(static::$DEFAULT_CONFIG, $config);
		$pheanstalk = new Pheanstalk($config['host'], $config['port'], $config['timeout']);
		
		return new Queue_Beanstalkd($pheanstalk, $config['queue']);
	}
}
