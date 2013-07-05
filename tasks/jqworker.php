<?php
/**
 * Jobqueue Worker Task
 *
 * @package    Jobqueue
 * @author     Keishi HOSOBA
 * @license    MIT License
 * @copyright  2013 Keishi HOSOBA
 * @link       https://github.com/hosopy/fuel-jobqueue
 *
 * Usage:
 *   $ php oil refine jobqueue::worker:help
 */
namespace Fuel\Tasks;

use Jobqueue\Context;
use Jobqueue\Queue_Manager;
use Jobqueue\Worker;
use Jobqueue\Listener;

class Jqworker
{
	public static function run()
	{
		// Prompt the user with menu options
		$option = \Cli::prompt('What would you like to do?', array('work', 'listen', 'help'));

		switch($option)
		{
			case 'work':
				return static::work();
				break;
			case 'listen':
				return static::listen();
				break;
			case 'help':
				return static::help();
				break;
			default:
				return static::help();
				break;
		}
	}

	/**
	 * Implementation of jobqueue::worker:work
	 */
	public static function work()
	{
		$options = static::parse_options();
		$worker  = new Worker(new Queue_Manager(Context::instance()));
		$worker->pop(
			$options['connection'], $options['queue'],
			$options['delay'], $options['memory'],
			$options['sleep']);
	}

	/**
	 * Implementation of jobqueue::worker:listen
	 */
	public static function listen()
	{
		$options = static::parse_options();
		$listener = new Listener(Context::instance(), getcwd());
		$listener->listen(
			$options['connection'], $options['queue'],
			$options['delay'], $options['memory'], $options['timeout']);
	}

	/**
	 * Shows basic help instructions for in oil
	 */
	public static function help()
	{
		echo <<<HELP
            Usage:
                php oil refine jobqueue::worker:work      Process the next job on a queue.
                php oil refine jobqueue::worker:listen    Listen to a given queue.
                php oil refine jobqueue::worker:help      Show help.

            Options:
                [work]
                  --connection=<name> : The name of connection.
                  --queue=<name>      : The queue to listen on.
                  --delay=<num>       : Amount of time to delay failed jobs. (default=0)
                  --memory=<num>      : The memory limit in megabytes. (default=128)
                  --sleep=<num>       : Whether the worker should sleep when no job is available.

                [listen]
                  --connection=<name> : The name of connection which manages the queue to listen on.
                  --queue=<name>      : The queue to listen on.
                  --delay=<num>       : Amount of time to delay failed jobs. (default=0)
                  --memory=<num>      : The memory limit in megabytes. (default=128)
                  --timeout=<num>     : Seconds a job may run before timing out. (default=60)

            Description:
                Run jobqueue worker.

HELP;
    }

	private static function parse_options()
	{
		return array(
			'connection' => \Cli::option('connection', null),
			'queue'      => \Cli::option('queue', null),
			'delay'      => \Cli::option('delay', 0),
			'memory'     => \Cli::option('memory', 128),
			'timeout'    => \Cli::option('timeout', 60),
			'child'      => \Cli::option('child', 1),
			'sleep'      => \Cli::option('sleep', 1),
		);
	}
}
