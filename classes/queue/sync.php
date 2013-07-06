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

class Queue_Sync extends Queue_Base implements Queue_Driver
{
	/**
	 * Push a new job onto the queue.
	 *
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return void
	 */
	public function push($job, $data = '', $queue = null)
	{
		$this->resolve_job($job, $data)->fire();
	}

	/**
	 * Push a new job onto the queue after a delay.
	 *
	 * @param  int     $delay
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return void
	 */
	public function later($delay, $job, $data = '', $queue = null)
	{
		return $this->push($job, $data, $queue);
	}

	/**
	 * Pop the next job off of the queue.
	 *
	 * @param  string  $queue
	 * @return \Jobqueue\Job_Base
	 */
	public function pop($queue = null) {}

	/**
	 * Resolve a Sync job instance.
	 *
	 * @param  string  $job
	 * @param  string  $data
	 * @return \Jobqueue\Job_Base
	 */
	protected function resolve_job($job, $data)
	{
		return new Job_Sync($this->context, $job, $data);
	}
}
