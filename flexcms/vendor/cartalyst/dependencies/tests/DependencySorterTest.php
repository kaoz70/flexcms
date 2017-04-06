<?php
/**
 * Part of the Dependencies package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Dependencies
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Mockery as m;
use Cartalyst\Dependencies\DependencySorter;

class DependencySorterTest extends PHPUnit_Framework_TestCase {

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testDependenciesCanBeAddedToSorter()
	{
		$sorter = new DependencySorter;
		$sorter->add('foo/bar');
		$sorter->add('baz/qux', array('foo/bar'));
		$sorter->add('fred/corge', array('baz/qux'));

		$expected = array(
			'foo/bar'    => array(),
			'baz/qux'    => array('foo/bar'),
			'fred/corge' => array('baz/qux'),
		);

		$this->assertEquals($sorter->getItems(), $expected);
	}

	public function testDependenciesCanBeSorted()
	{
		$sorter = new DependencySorter;
		$sorter->add('baz/qux', array('foo/bar'));
		$sorter->add('fred/corge', 'baz/qux'); // Test string dependencies
		$sorter->add('foo/bar');

		$expected = array('foo/bar', 'baz/qux', 'fred/corge');

		// Because the order of our array matters, we'll implode it
		// and compare the two string match
		$this->assertEquals(implode('.', $expected), implode('.', $sorter->sort()));
	}

	public function testDependentInstances()
	{
		$sorter = new DependencySorter;

		$dep1 = m::mock('Cartalyst\Dependencies\DependentInterface');
		$dep1->shouldReceive('getSlug')->andReturn('baz/qux');
		$dep1->shouldReceive('getDependencies')->andReturn(array('foo/bar'));
		$sorter->add($dep1);

		$dep2 = m::mock('Cartalyst\Dependencies\DependentInterface');
		$dep2->shouldReceive('getSlug')->andReturn('fred/corge');
		$dep2->shouldReceive('getDependencies')->andReturn('baz/qux');
		$sorter->add($dep2);

		$dep3 = m::mock('Cartalyst\Dependencies\DependentInterface');
		$dep3->shouldReceive('getSlug')->andReturn('foo/bar');
		$dep3->shouldReceive('getDependencies')->andReturn(array());
		$sorter->add($dep3);

		$this->assertCount(3, $sorted = $sorter->sort());
		$this->assertEquals($dep3, $sorted[0]);
		$this->assertEquals($dep1, $sorted[1]);
		$this->assertEquals($dep2, $sorted[2]);
	}

	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testCircularDependenciesThrowAnException()
	{
		$sorter = new DependencySorter;
		$sorter->add('foo/bar', array('bar/foo'));
		$sorter->add('bar/foo', array('foo/bar'));
		$sorter->sort();
	}

	public function testFoo()
	{
		$sorter = new DependencySorter;
		$sorter->add('foo', ['bar', 'baz']);
		$sorter->add('baz');
		$sorter->add('bar', 'foo');
		var_dump($sorter->sort());
	}

	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testSelfDependencyThrowsAnException()
	{
		$sorter = new DependencySorter;
		$sorter->add('foo/bar', array('foo/bar'));
		$sorter->sort();
	}

}
