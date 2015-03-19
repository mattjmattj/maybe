<?php

namespace Maybe\Util;

use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophecy\MethodProphecy;

class TypeReturnValuePromise implements \Prophecy\Promise\PromiseInterface
{
	private $type;
	
	public function __construct($type)
	{
		$this->type = $type;
	}

	public function execute(array $args, ObjectProphecy $object, MethodProphecy $method)
	{
		return $this->getReturnValueForType($this->type);
	}
	
	private function getReturnValueForType ($type) {
		switch ($type) {
			case 'bool' : return false;
			case 'int' : return 0;
			case 'float' : return 0.;
			case 'string' : return '';
			case 'array' : return [];
			default : return $this->getReturnValueForClassname ($type);
		}
	}
	
	private function getReturnValueForClassname ($classname) {
		if (class_exists($classname) || interface_exists($classname)) {
			$maybe = new \Maybe\Maybe($classname);
			return $maybe->buildFakeObject();
		}
		
		return null;
	}
}
