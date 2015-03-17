<?php

namespace Maybe\Examples\Log;

class Log {
	
	public function log ($msg) {
		echo date('c') . ' ' . $msg . "\n";
	}
	
}