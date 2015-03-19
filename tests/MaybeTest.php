<?php
/*

This file is part of Maybe

Copyright (c) 2015, Matthias Jouan
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

namespace Maybe\Tests;

use Maybe\Maybe;

use Maybe\Util\Reflection as TestClass;

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

	public function testMaybeCanProvideAFakeInstanceDirectly () {
		
		$maybe = new Maybe('Maybe\Tests\Simple');
		
		$wrapped = $maybe->wrap(null);
		
		$this->assertEquals($wrapped, $maybe->buildFakeObject());
	}
	
		
	public function testFakeInstancesShouldWrapReturnedObjectsWithMaybe () {
		
		$maybe = new Maybe('Maybe\Tests\Simple');
		$fake = $maybe->buildFakeObject();
		
		$this->assertInstanceOf('\Maybe\Util\Reflection', $fake->getReflection());
		$this->assertInstanceOf('Maybe\Tests\AnotherEmptyClass', $fake->getAnotherEmptyClass());
		$this->assertInternalType('int', $fake->getAnotherEmptyClass()->getThree());
		$this->assertNotEquals(3, $fake->getAnotherEmptyClass()->getThree());
		
		//NOT IMPLEMENTED YET
		//$this->assertInstanceOf('Maybe\Util\Reflection', $fake->getTestClass());
		
	}
	
	public function testFakeInstancesShouldAcceptAnyNumberOfArgument () {
		$maybe = new Maybe('Maybe\Tests\Simple');
		$fake = $maybe->buildFakeObject();
		
		$this->assertInternalType('int', $fake->getAnotherEmptyClass()->getThree(1,2,3,'foo'));
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
	
	/**
	 * @return AnotherEmptyClass some random object
	 */ 
	public function getAnotherEmptyClass () {
		return null;
	}
	
	/**
	 * @return \Maybe\Util\Reflection
	 */ 
	public function getReflection () {
		return null;
	}
	
	/**
	 * @return TestClass an object using an alias in "use"
	 */ 
	public function getTestClass () {
		return null;
	}
	
}

class AnotherEmptyClass {
	
	/**
	 * @return int 3
	 */ 
	public function getThree () {
		return 3;
	}
	
}