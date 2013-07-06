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

class Connector_Sync implements Connector_Driver
{
	/**
	 * Establish a queue connection.
	 *
	 * @param  array  $config
	 * @return \Jobqueue\Queue_Driver
	 */
	public function connect(array $config)
	{
		return new Queue_Sync;
	}
}
