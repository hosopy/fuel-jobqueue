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

abstract class Job_Base
{
	/**
	 * The job handler instance.
	 *
	 * @var mixed
	 */
	protected $instance;

	/**
	 * The Context.
	 *
	 * @var \Jobqueue\Context
	 */
	protected $context;

	
	public function __construct(Context $context)
	{
		$this->context = $context;
	}

	/**
	 * Resolve and fire the job handler method.
	 *
	 * @param  array  $payload
	 * @return void
	 */
	protected function resolve_and_fire(array $payload)
	{
		list($class, $method) = $this->parse_job($payload['job']);

		$this->instance = $this->resolve($class);

		$this->instance->{$method}($this, $payload['data']);
	}

	/**
	 * Resolve the given job handler.
	 *
	 * @param  string  $class
	 * @return mixed
	 */
	protected function resolve($class)
	{
		return $this->context->make_job_handler($class);
	}

	/**
	 * Parse the job declaration into class and method.
	 *
	 * @param  string  $job
	 * @return array
	 */
	protected function parse_job($job)
	{
		$segments = explode('@', $job);

		return count($segments) > 1 ? $segments : array($segments[0], 'fire');
	}

	/**
	 * Determine if job should be kept moving in the queue after processing.
	 *
	 * @return bool
	 */
	public function should_keep_moving()
	{
		return isset($this->instance->_keep_moving);
	}
}
