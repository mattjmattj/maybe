<?php

namespace Maybe\Tests;

use Maybe\Maybe;

class MaybeTest extends \PHPUnit_Framework_TestCase {
	
	public function testMaybeCanWrapAnObjectWithoutAlteringIt () {
		
		$maybe = new Maybe('Maybe\Tests\Simple');
		$simple = new Simple();
		
		$wrapped = $maybe->wrap($simple);
		
		$this->assertEquals($simple->doSomething(), $wrapped->doSomething());
		$this->assertEquals($simple->doSomethingElse(), $wrapped->doSomethingElse());
	}
	
	public function testMaybeCanWrapNullAndProvideAFakeInstanceOfAClass () {
		
		$maybe = new Maybe('Maybe\Tests\Simple');
		
		$wrapped = $maybe->wrap(null);
		
		$this->assertTrue(method_exists($wrapped,'doSomething'));
		$this->assertTrue(method_exists($wrapped,'doSomethingElse'));
		$this->assertInstanceOf('Maybe\Tests\Simple',$wrapped);
	}
}


class Simple {
	/**
	 * @return int
	 */ 
	public function doSomething () {
		return 3;
	}
	
	/**
	 * @return string
	 */ 
	public function doSomethingElse () {
		return 'foobar';
	}
}