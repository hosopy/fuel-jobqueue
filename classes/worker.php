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

class Worker
{
	/**
	 * THe queue manager instance.
	 *
	 * @var \Jobqueue\Queue_Manager
	 */
	protected $manager;

	/**
	 * Create a new queue worker.
	 *
	 * @param  \Jobqueue\Queue_Manager  $manager
	 * @return void
	 */
	public function __construct(Queue_Manager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Listen to the given queue.
	 *
	 * @param  string  $connection
	 * @param  string  $queue
	 * @param  int     $delay
	 * @param  int     $memory
	 * @param  bool    $sleep
	 * @return void
	 */
	public function pop($connection, $queue = null, $delay = 0, $memory = 128, $sleep = false)
	{
		$connection = $this->manager->connection($connection);

		$job = $connection->pop($queue);

		// If we're able to pull a job off of the stack, we will process it and
		// then make sure we are not exceeding our memory limits for the run
		// which is to protect against run-away memory leakages from here.
		if ( ! is_null($job))
		{
			$this->process($job, $delay);
		}
		elseif ($sleep)
		{
			$this->sleep(1);
		}
	}

	/**
	 * Process a given job from the queue.
	 *
	 * @param  \Jobqueue\Job_Driver  $job
	 * @param  int  $delay
	 * @return void
	 */
	public function process(Job_Driver $job, $delay)
	{
		try
		{
			// First we will fire off the job. Once it is done we will see if it will
			// be kept after processing and if so we will just keep moving.
			// Otherwise we will delete the job.
			$job->fire();

			if (!$job->should_keep_moving()) $job->delete();
		}
		catch (\Exception $e)
		{
			// If we catch an exception, we will attempt to release the job back onto
			// the queue so it is not lost. This will let is be retried at a later
			// time by another listener (or the same one). We will do that here.
			$job->release($delay);

			throw $e;
		}
	}

	/**
	 * Sleep the script for a given number of seconds.
	 *
	 * @param  int   $seconds
	 * @return void
	 */
	public function sleep($seconds)
	{
		sleep($seconds);
	}

	/**
	 * Get the queue manager instance.
	 *
	 * @return \Jobqueue\Queue_Manage
	 */
	public function get_manager()
	{
		return $this->manager;
	}

	/**
	 * Set the queue manager instance.
	 *
	 * @param  \Jobqueue\Queue_Manage $manager
	 * @return void
	 */
	public function set_manager(Queue_Manage $manager)
	{
		$this->manager = $manager;
	}
}
