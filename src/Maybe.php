<?php

namespace Maybe;

class Maybe {
	
	/**
	 * @param string $classname - the name of the class/interface we want to wrap
	 */ 
	public function __construct ($classname) {
		
	}
	
	/**
	 * @param mixed $object - the object we want to wrap. May be null
	 */ 
	public function wrap ($object) {
		return $object;
	}
	
}