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


namespace Maybe\Tests\Util;

use Maybe\Util\Reflection;

use Maybe\Maybe as TestClass;

class ReflectionTest extends \PHPUnit_Framework_TestCase {
	
	public function testReflectionShouldFindReturnTypesAccordingToAnnotations () {
		
		$reflection = new Reflection('Maybe\Tests\Util\Simple');
		
		$expected = [
			'doSomething' => 'int',
			'doSomethingElse' => 'string',
			'returnSomeBoolean' => 'bool',
			'returnSomeFloatNumber' => 'float',
			'returnSomeArray' => 'array',
			'uncommented' => null,
			'commentedButUnknown' => null,
			'getAnotherEmptyClass' => '\Maybe\Tests\Util\AnotherEmptyClass',
			'getMaybe' => '\Maybe\Maybe',
			
			// NOT IMPLEMENTED YET
			//'getTestClass' => 'Maybe\Maybe'
		];
		
		$this->assertEquals($expected, $reflection->getReturnTypes());
		
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
	 * @return Maybe\Maybe a maybe
	 */ 
	public function getMaybe () {
		return null;
	}
	
	/**
	 * @return TestClass a object using an alias in "use"
	 */ 
	//public function getTestClass () {
	//	return null;
	//}
	
}

class AnotherEmptyClass {
	
	/**
	 * @return int 3
	 */ 
	public function getThree () {
		return 3;
	}
	
}