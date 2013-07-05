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

class Context
{
	/**
	 * The Context instance.
	 *
	 * @var \Jobqueue\Context
	 */
	private static $instance;

	/**
	 * Environment
	 *
	 * @var string
	 */
	private $environment;

	/**
	 * Jobqueue config
	 *
	 * @var array
	 */
	private $config;

	/**
	 * Get a instance.
	 *
	 * @return \Jobqueue\Context
	 */
	public static function instance()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static(\Fuel::$env, \Config::load('jobqueue', 'jobqueue'));
		}
		
		return static::$instance;
	}
	
	/**
	 * Create a new context instance.
	 *
	 * @param string $environment
	 * @param array $config
	 */
	private function __construct($environment, array $config)
	{
		$this->environment = $environment;
		$this->config = $config;

	}

	/**
	 * Get the name of the default queue connection.
	 *
	 * @return string
	 */
	public function get_default_connection()
	{
		return $this->config['default'];
	}

	/**
	 * Get the queue connection configuration.
	 * If $name is null, the default connection is returned.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function get_connection_config($name = null)
	{
		if (is_null($name))
		{
			$name = $this->get_default_connection();
		}
		
		return $this->config['connections'][$name];
	}

	/**
	 * Get environment.
	 *
	 * @return string
	 */
	public function get_environment()
	{
		return $this->environment;
	}

	/**
	 * Create a command string to exec worker:work task.
	 */
	public function create_work_task_command($connection, $queue, $delay, $memory, $timeout, $sleep = false)
	{
		$string = 'FUEL_ENV=%s php oil refine jqworker:work --connection=%s --queue=%s --delay=%s --memory=%s';
		if ($sleep)
		{
			$string .= ' --sleep';
		}
		
		return sprintf($string, $this->environment, $connection, $queue, $delay, $memory);
	}

	/**
	 * Get defined connectors as array of Closure.
	 *
	 * @return array Array of Closure
	 */
	public function get_connector_resolvers()
	{
		return array(
			'beanstalkd' => function () { return new Connector_Beanstalkd; },
		);
	}

	/**
	 * Resolve the given job handler class and create a new instance.
	 *
	 * @param  string  $class
	 * @return mixed
	 */
	public function make_job_handler($class)
	{
		if (class_exists($class))
		{
			return new $class;
		}
	}
}
