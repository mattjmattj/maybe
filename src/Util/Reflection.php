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


namespace Maybe\Util;

class Reflection extends \ReflectionClass {
	
	private static $internalTypes = [
		'int', 'bool', 'string', 'float', 'binary', 'array'
	];
	
	/**
	 * @return array [methodName => type]
	 */ 
	public function getReturnTypes() {
		$returnTypes = [];
		
		foreach ($this->getMethods() as $method) {
			if (!$this->isMethodValid($method)) {
				continue;
			}
			$type = $this->getReturnTypeForMethod($method);
			$returnTypes[$method->name] = $type;
		}
		
		return $returnTypes;
	}

	private function isMethodValid (\ReflectionMethod $method) {
		return !($method->isConstructor() 
			|| $method->isDestructor()
			|| $method->isStatic()
			|| $method->isFinal());
	}

	private function getReturnTypeForMethod(\ReflectionMethod $method) {
		$type = $this->extractReturnTypeFromAnnotations($method);
		
		if (is_null($type) || $this->isTypeInternal($type)) {
			return $type;
		}
		
		return $this->resolveTypeAsClass($type);
	}
	
	private function extractReturnTypeFromAnnotations(\ReflectionMethod $method) {
		$doc = $method->getDocComment();
		if (preg_match('/@return(?:s)?\s+([^\s]+)/', $doc, $matches)) {
			return $matches[1];
		}
		return null;
	}
	
	private function isTypeInternal ($type) {
		return in_array($type, self::$internalTypes);
	}
	
	private function resolveTypeAsClass($type) {
		if ($this->isExistingClassOrInterface($type)) {
			//$type is already a class
			return $this->normalizeClassname($type);
		}
		
		$namespace = $this->getNamespaceName();
		$namespacedType = "$namespace\\$type";
		if ($this->isExistingClassOrInterface($namespacedType)) {
			//$type is a classname within the namespace
			return $this->normalizeClassname($namespacedType);
		}
		
		return null;
	}
	
	private function isExistingClassOrInterface ($name) {
		return class_exists($name) || interface_exists($name);
	}
	
	private function normalizeClassname($classname) {
		return $classname[0] === '\\' ? $classname : '\\' . $classname;
	}
}