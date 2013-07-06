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

class Job_Sync extends Job_Base implements Job_Driver
{
	/**
	 * The class name of the job.
	 *
	 * @var string
	 */
	protected $job;

	/**
	 * The queue message data.
	 *
	 * @var string
	 */
	protected $data;

	/**
	 * Create a new job instance.
	 *
	 * @param  \Jobqueue\Context  $context
	 * @param  string  $job
	 * @param  string  $data
	 * @return void
	 */
	public function __construct(Context $context, $job, $data = '')
	{
		$this->context = $context;
		$this->job = $job;
		$this->data = $data;
	}

	/**
	 * Fire the job.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->resolve_and_fire(array('job' => $this->job, 'data' => $this->data));
	}

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete()
	{
		//
	}

	/**
	 * Release the job back into the queue.
	 *
	 * @param  int   $delay
	 * @return void
	 */
	public function release($delay = 0)
	{
		//
	}

	/**
	 * Get the number of times the job has been attempted.
	 *
	 * @return int
	 */
	public function attempts()
	{
		return 1;
	}

	/**
	 * Get the job identifier.
	 *
	 * @return string
	 */
	public function get_job_id()
	{
		return '';
	}

}
