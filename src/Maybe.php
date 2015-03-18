<?php

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
	private function buildFakeObject () {
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
		$value = $this->getReturnValueForType($type);
		$this->prophecy->{$method}()->willReturn($value);
	}
	
	private function getReturnValueForType ($type) {
		switch ($type) {
			case 'bool' : return false;
			case 'int' : return 0;
			case 'float' : return 0.;
			case 'string' : return '';
			case 'array' : return [];
			default : return null;
		}
	}
}