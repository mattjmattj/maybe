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


namespace Maybe;

class Maybe {
	
	/** @var string */ 
	private $classname;
	
	/** @var Prophecy */
	private $prophecy;
	
	/**
	 * @param string $classname - the name of the class/interface we want to wrap
	 */ 
	public function __construct ($classname) {
		$this->classname = $classname;
	}
	
	/**
	 * @param mixed $object - the object we want to wrap. May be null
	 */ 
	public function wrap ($object) {

		if (!is_null($object)) {
			return $object;
		}

		return $this->buildFakeObject ();
	}
	
	/**
	 * Builds a fake object using Prophecy
	 */ 
	public function buildFakeObject () {
		if (!isset($this->prophecy)) {
			$this->initProphecy();
		}
		
		return $this->prophecy->reveal();
	}
	
	/**
	 * Prophecy initialization
	 */ 
	private function initProphecy () {
		$prophet = new \Prophecy\Prophet();
		$this->prophecy = $prophet->prophesize($this->classname);
		
		$this->initProphecyReturnValues ();
	}
	
	/**
	 * Tries to set a coherent return value for each of the faked method, using
	 * Reflection API.
	 */ 
	private function initProphecyReturnValues () {
		$reflection = new \Maybe\Util\Reflection($this->classname);
		
		$returnTypes = $reflection->getReturnTypes();
		foreach ($returnTypes as $method => $type) {
			$this->initProphecyReturnValueForMethod($method, $type);
		}
	}

	private function initProphecyReturnValueForMethod ($method, $type) {
		$me = $this;
		$this->prophecy->{$method}(\Prophecy\Argument::cetera())->will(new \Maybe\Util\TypeReturnValuePromise($type));
	}
}