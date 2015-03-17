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
	
	private function buildFakeObject () {
		if (!isset($this->prophecy)) {
			$this->initProphecy();
		}
		
		return $this->prophecy->reveal();
	}
	
	private function initProphecy () {
		$prophet = new \Prophecy\Prophet();
		$this->prophecy = $prophet->prophesize($this->classname);
	}
	
}