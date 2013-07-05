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

use Pheanstalk_Job;
use Pheanstalk_Pheanstalk as Pheanstalk;
use Jobqueue\Queue_Driver;
use Jobqueue\Queue_Base;
use Jobqueue\Job_Beanstalkd;

class Queue_Beanstalkd extends Queue_Base implements Queue_Driver
{
	/**
	 * The Pheanstalk instance.
	 *
	 * @var Pheanstalk
	 */
	protected $pheanstalk;

	/**
	 * The name of the default tube.
	 *
	 * @var string
	 */
	protected $default;

	/**
	 * Create a new Beanstalkd queue instance.
	 *
	 * @param  Pheanstalk  $pheanstalk
	 * @param  string  $default
	 * @return void
	 */
	public function __construct(Pheanstalk $pheanstalk, $default)
	{
		$this->default = $default;
		$this->pheanstalk = $pheanstalk;
	}

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
		$payload = $this->create_payload($job, $data);

		$this->pheanstalk->useTube($this->get_queue($queue))->put($payload);
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
		$payload = $this->create_payload($job, $data);

		$pheanstalk = $this->pheanstalk->useTube($this->get_queue($queue));

		$pheanstalk->put($payload, Pheanstalk::DEFAULT_PRIORITY, $delay);
	}

	/**
	 * Pop the next job off of the queue.
	 *
	 * @param  string  $queue
	 * @return \Jobqueue\Job_Driver|null
	 */
	public function pop($queue = null)
	{
		$job = $this->pheanstalk->watchOnly($this->get_queue($queue))->reserve(0);

		if ($job instanceof Pheanstalk_Job)
		{
			return new Job_Beanstalkd($this->context, $this->pheanstalk, $job);
		}
	}

	/**
	 * Get the queue or return the default.
	 *
	 * @param  string|null  $queue
	 * @return string
	 */
	protected function get_queue($queue)
	{
		return $queue ?: $this->default;
	}

	/**
	 * Get the underlying Pheanstalk instance.
	 *
	 * @return Pheanstalk
	 */
	public function get_pheanstalk()
	{
		return $this->pheanstalk;
	}
}
