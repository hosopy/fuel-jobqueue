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

interface Job_Driver
{
	/**
	 * Fire the job.
	 *
	 * @return void
	 */
	public function fire();

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete();

	/**
	 * Release the job back into the queue.
	 *
	 * @param  int   $delay
	 * @return void
	 */
	public function release($delay = 0);

	/**
	 * Get the number of times the job has been attempted.
	 *
	 * @return int
	 */
	public function attempts();
}
