<?php

namespace Maybe\Util;

class Reflection {
	
	/** @var string */ 
	private $classname;
	
	/**
	 * @param string $classname
	 */ 
	public function __construct ($classname) {
		$this->classname = $classname;
	}

	/**
	 * @return array [methodName => type]
	 */ 
	public function getReturnTypes () {
		$returnTypes = [];
		
		$reflection = new \ReflectionClass($this->classname);
		
		foreach ($reflection->getMethods() as $method) {
			$type = $this->getReturnTypeForMethod($method);
			$returnTypes[$method->getName()] = $type;
		}
		
		return $returnTypes;
	}

	private function getReturnTypeForMethod (\ReflectionMethod $method) {
		if ($method->isConstructor() 
			|| $method->isDestructor()
			|| $method->isStatic()) {
			return null;
		}
		
		$doc = $method->getDocComment();
		if (preg_match('/@return(?:s)?\s+([^\s]+)/', $doc, $matches)) {
			return $matches[1];
		} 
		
		return null;
	}
}