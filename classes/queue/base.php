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

abstract class Queue_Base
{
	/**
	 * The context.
	 *
	 * @var \Jobqueue\Context
	 */
	protected $context;

	/**
	 * Set the context.
	 *
	 * @param  \Jobqueue\Context  $context
	 * @return void
	 */
	public function set_context(Context $context)
	{
		$this->context = $context;
	}

	/**
	 * Create a payload string from the given job and data.
	 *
	 * @param  string  $job
	 * @param  mixed   $data
	 * @return string
	 */
	protected function create_payload($job, $data = '')
	{
		return json_encode(array('job' => $job, 'data' => $data));
	}
}
