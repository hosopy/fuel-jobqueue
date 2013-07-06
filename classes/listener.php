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

use \Symfony\Component\Process\Process;

class Listener
{
	/**
	 * The Context instance.
	 *
	 * @var \Jobqueue\Context
	 */
	protected $context;

	/**
	 * The command working path.
	 *
	 * @var string
	 */
	protected $command_path;

	/**
	 * Create a new queue listener.
	 *
	 * @param  \Jobqueue\Context $context
	 * @param  string  $command_path
	 * @return void
	 */
	public function __construct(Context $context, $command_path)
	{
		$this->context = $context;
		$this->command_path = $command_path;
	}

	/**
	 * Listen to the given queue connection.
	 *
	 * @param  string  $connection
	 * @param  string  $queue
	 * @param  string  $delay
	 * @param  string  $memory
	 * @param  int     $timeout
	 * @return void
	 */
	public function listen($connection, $queue, $delay, $memory, $timeout = 60)
	{
		$process = $this->make_process($connection, $queue, $delay, $memory, $timeout);

		while(true)
		{
			$this->run_process($process, $memory);
		}
	}

	/**
	 * Run the given process.
	 *
	 * @param  \Symfony\Component\Process\Process  $process
	 * @param  int  $memory
	 * @return void
	 */
	public function run_process(Process $process, $memory)
	{
		$process->run();

		if ($this->memory_exceeded($memory))
		{
			$this->stop(); return;
		}
	}

	/**
	 * Create a new Process for the worker.
	 *
	 * @param  string  $connection
	 * @param  string  $queue
	 * @param  int     $delay
	 * @param  int     $memory
	 * @param  int     $timeout
	 * @return \Symfony\Component\Process\Process
	 */
	public function make_process($connection, $queue, $delay, $memory, $timeout)
	{
		// 6th argument: $sleep = true
		$command  = $this->context->create_work_task_command($connection, $queue, $delay, $memory, $timeout, true);
		return new Process($command, $this->command_path, null, null, $timeout);
	}

	/**
	 * Determine if the memory limit has been exceeded.
	 *
	 * @param  int   $memoryLimit
	 * @return bool
	 */
	public function memory_exceeded($memory_limit)
	{
		return (memory_get_usage() / 1024 / 1024) >= $memory_limit;
	}

	/**
	 * Stop listening and bail out of the script.
	 *
	 * @return void
	 */
	public function stop()
	{
		die;
	}
}
