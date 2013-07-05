<?php
/**
 * Tests for \Jobqueue\Context_Fuel
 *
 * @group Packages
 * @group Jobqueue
 * @group Context_Fuel
 */

namespace Jobqueue;
use Jobqueue\Context_Fuel;

class Tests_Context_Fuel extends \TestCase
{
	/**
	 * @test
	 */
	public function test_get_default_connection()
	{
		$context = new Context_Fuel();

		$this->assertEquals('default_connection', $context->get_default_connection());
	}
}
