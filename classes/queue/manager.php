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

class Queue_Manager
{
	/**
	 * The context.
	 *
	 * @var \Jobqueue\Context
	 */
	private $context;

	/**
	 * The array of resolved queue connections.
	 *
	 * @var array
	 */
	private $connections = array();

	/**
	 * Create a new manager instance.
	 *
	 * @param  \Jobqueue\Context  $context
	 * @return void
	 */
	public function __construct(Context $context)
	{
		$this->context = $context;

		$resolvers = $this->context->get_connector_resolvers();
		foreach ($resolvers as $name => $resolver)
		{
			$this->add_connector($name, $resolver);
		}
	}

	/**
	 * Resolve a queue connection instance.
	 *
	 * @param  string  $name
	 * @return \Jobqueue\Queue_Driver
	 */
	public function connection($name = null)
	{
		$name = $name ?: $this->get_default();

		// If the connection has not been resolved yet we will resolve it now as all
		// of the connections are resolved when they are actually needed so we do
		// not make any unnecessary connection to the various queue end-points.
		if ( ! isset($this->connections[$name]))
		{
			$this->connections[$name] = $this->resolve($name);

			$this->connections[$name]->set_context($this->context);
		}

		return $this->connections[$name];
	}

	/**
	 * Resolve a queue connection.
	 *
	 * @param  string  $name
	 * @return \Jobqueue\Queue_Driver
	 */
	protected function resolve($name)
	{
		$config = $this->get_config($name);

		return $this->get_connector($config['driver'])->connect($config);
	}

	/**
	 * Get the connector for a given driver.
	 *
	 * @param  string  $driver
	 * @return \Jobqueue\Connector_Driver
	 */
	protected function get_connector($driver)
	{
		if (isset($this->connectors[$driver]))
		{
			return call_user_func($this->connectors[$driver]);
		}

		throw new \InvalidArgumentException("No connector for [$driver]");
	}

	/**
	 * Add a queue connection resolver.
	 *
	 * @param  string   $driver
	 * @param  Closure  $resolver
	 * @return void
	 */
	public function add_connector($driver, \Closure $resolver)
	{
		$this->connectors[$driver] = $resolver;
	}

	/**
	 * Get the queue connection configuration.
	 *
	 * @param  string  $name
	 * @return array
	 */
	protected function get_config($name)
	{
		return $this->context->get_connection_config($name);
	}

	/**
	 * Get the name of the default queue connection.
	 *
	 * @return string
	 */
	protected function get_default()
	{
		return $this->context->get_default_connection();
	}

	/**
	 * Dynamically pass calls to the default connection.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		$callable = array($this->connection(), $method);

		return call_user_func_array($callable, $parameters);
	}
}
