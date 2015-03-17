<?php

namespace Maybe\Examples\Log;

class Actor {
	
	private $log;
	
	public function __construct (Log $log) {
		$this->log = $log;
	}
	
	public function doSomething () {
		$this->log->log('Started doing something');
		sleep(3);
		$this->log->log('Finished doing something');
	}
	
}