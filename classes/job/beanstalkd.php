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
use Pheanstalk_Job;

class Job_Beanstalkd extends Job_Base implements Job_Driver
{
	/**
	 * The Pheanstalk instance.
	 *
	 * @var Pheanstalk
	 */
	protected $pheanstalk;

	/**
	 * The Pheanstalk job instance.
	 *
	 * @var Pheanstalk_Job
	 */
	protected $job;

	/**
	 * Create a new job instance.
	 *
	 * @param  \Jobqueue\Context $context
	 * @param  Pheanstalk  $pheanstalk
	 * @param  Pheanstalk_Job  $job
	 * @return void
	 */
	public function __construct(Context $context, Pheanstalk $pheanstalk, Pheanstalk_Job $job)
	{
		parent::__construct($context);
		$this->pheanstalk = $pheanstalk;
		$this->job = $job;
	}

	/**
	 * Fire the job.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->resolve_and_fire(json_decode($this->job->getData(), true));
	}

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete()
	{
		$this->pheanstalk->delete($this->job);
	}

	/**
	 * Release the job back into the queue.
	 *
	 * @param  int   $delay
	 * @return void
	 */
	public function release($delay = 0)
	{
		$priority = Pheanstalk::DEFAULT_PRIORITY;

		$this->pheanstalk->release($this->job, $priority, $delay);
	}

	/**
	 * Get the number of times the job has been attempted.
	 *
	 * @return int
	 */
	public function attempts()
	{
		$stats = $this->pheanstalk->statsJob($this->job);

		return (int) $stats->reserves;
	}

	/**
	 * Get the job identifier.
	 *
	 * @return string
	 */
	public function get_job_id()
	{
		return $this->job->getId();
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

	/**
	 * Get the underlying Pheanstalk job.
	 *
	 * @return Pheanstalk_Job
	 */
	public function get_pheanstalk_job()
	{
		return $this->job;
	}
}
