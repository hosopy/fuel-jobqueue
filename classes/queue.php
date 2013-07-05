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

use Jobqueue\Context;
use Jobqueue\Queue_Manager;
use Jobqueue\Worker;
use Jobqueue\Connector_Beanstalkd;

class Queue
{
	/**
	 * The instance.
	 *
	 * @var \Jobqueue\Jobqueue
	 */
	private static $instance;

	/**
	 * The context.
	 *
	 * @var \Jobqueue\Context
	 */
	private $context;

	/**
	 * The Queue_Manager.
	 *
	 * @var \Jobqueue\Queue_Manager
	 */
	private $manager;
	
	/**
	 * The Worker.
	 *
	 * @var \Jobqueue\Worker
	 */
	private $worker;

	/**
	 * Get a instance.
	 *
	 * @return \Jobqueue\Queue
	 */
	public static function instance()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static(Context::instance());
		}
		
		return static::$instance;
	}

	/**
	 * Create a new jobqueue instance.
	 *
	 * @param  \Jobqueue\Context  $context
	 * @return void
	 */
	private function __construct(Context $context)
	{
		$this->context = $context;
		$this->manager = new Queue_Manager($context);
		$this->worker = new Worker($this->manager);
	}

	/**
	 * Push a new job onto the queue.
	 * The default connection is used.
	 *
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return void
	 */
	public static function push($job, $data = '', $queue = null)
	{
		static::instance()->manager->connection()->push($job, $data, $queue);
	}

	/**
	 * Push a new job onto the queue after a delay.
	 * The default connection is used.
	 *
	 * @param  int     $delay
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return void
	 */
	public static function later($connection, $delay, $job, $data = '', $queue = null)
	{
		static::instance()->manager->connection()->later($delay, $job, $data, $queue);
	}

	/**
	 * Resolve a queue connection instance.
	 * You can specify the connection for a job to be pushed.
	 *
	 * [Usage]
	 * Jobqueue::connection('other')->push(...);
	 *
	 * @param  string  $name
	 * @return \Jobqueue\Queue_Driver
	 */
	public static function connection($name = null)
	{
		return static::instance()->manager->connection($name);
	}

	/**
	 * Pop the next job off of the queue.
	 *
	 * @param  string   $connection
	 * @param  string   $queue
	 * @return \Jobqueue\Job|nul
	 */
	public static function pop($connection = null, $queue = null)
	{
		return static::instance()->worker->pop($connection, $queue);
	}
}
