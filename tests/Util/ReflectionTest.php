<?php

namespace Maybe\Tests\Util;

use Maybe\Util\Reflection;

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
			'commentedButUnknown' => 'zertyui'
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
}