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
	
	/**
	 * @return array [methodName => type]
	 */ 
	public function getReturnTypes () {
		$returnTypes = [];
		
		foreach ($this->getMethods() as $method) {
			$type = $this->getReturnTypeForMethod($method);
			$returnTypes[$method->getName()] = $type;
		}
		
		return $returnTypes;
	}

	private function getReturnTypeForMethod (\ReflectionMethod $method) {
		if ($method->isConstructor() 
			|| $method->isDestructor()
			|| $method->isStatic()
			|| $method->isFinal()) {
			return null;
		}
		
		$doc = $method->getDocComment();
		if (preg_match('/@return(?:s)?\s+([^\s]+)/', $doc, $matches)) {
			return $matches[1];
		} 
		
		return null;
	}
}