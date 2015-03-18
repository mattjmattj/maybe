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
		$this->assertInstanceOf('Maybe\Tests\SimpleInterface',$wrapped);
	}
	
	public function testMaybeCanWrapNullAndProvideAFakeImplementationOfAnInterface () {
		
		$maybe = new Maybe('Maybe\Tests\SimpleInterface');
		
		$wrapped = $maybe->wrap(null);
		
		$this->assertTrue(method_exists($wrapped,'doSomething'));
		$this->assertTrue(method_exists($wrapped,'doSomethingElse'));
		$this->assertInstanceOf('Maybe\Tests\SimpleInterface',$wrapped);
	}
	
	public function testFakeInstancesShouldReturnTheRightTypeAccordingToAnnotations () {
		
		$maybe = new Maybe('Maybe\Tests\Simple');
		
		$wrapped = $maybe->wrap(null);
		
		$this->assertInternalType('int', $wrapped->doSomething());
		$this->assertInternalType('string', $wrapped->doSomethingElse());
		$this->assertInternalType('bool', $wrapped->returnSomeBoolean());
		$this->assertInternalType('float', $wrapped->returnSomeFloatNumber());
		$this->assertInternalType('array', $wrapped->returnSomeArray());
		$this->assertNull($wrapped->uncommented());
		$this->assertNull($wrapped->commentedButUnknown());
		
	}
}

interface SimpleInterface {
	/**
	 * @return int
	 */ 
	public function doSomething ();
	
	
	/**
	 * @return string
	 */ 
	public function doSomethingElse ();
}

class Simple implements SimpleInterface {
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
	
	/** @return bool a boolean value */
	public function returnSomeBoolean () {}
	
	/** @return float a float value */
	public function returnSomeFloatNumber () {}
	
	/** @return array an array */
	public function returnSomeArray () {}
	
	public function uncommented () {}
	
	/** @return zertyui dzffdfzfonz */
	public function commentedButUnknown () {}
}