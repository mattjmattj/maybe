<?php

namespace Maybe\Examples\Email;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * This example illustrate another cool feature of Maybe : deep wrapping.
 */
 
interface Email {}
 
interface EmailSender {
	public function send (Email $email);
}

interface EmailBuilder {
	/**
	 * @return Email
	 */ 
	public function build ($from, $to, $msg);
}

interface EmailFactory {
	/**
	 * @return EmailBuilder
	 */ 
	public function getBuilder ();
	
	/**
	 * @return EmailSender
	 */ 
	public function getSender ();
}

/**
 * We have defined some interfaces that rely on each other.
 * 
 * EmailFactory is supposed to build EmailBuilder and EmailSender instances
 * EmailBuilder is supposed to build Email instances
 * EmailSender is meant to deal with Email instances
 * 
 * Now, let's define a class that uses thoses interfaces
 */ 

class Spammer {
	
	private $emailFactory;
	
	public function __construct (EmailFactory $factory) {
		$this->emailFactory = $factory;
	}
	
	public function spam () {
		$builder = $this->emailFactory->getBuilder();
		$email = $builder->build('you@example.com','me@example.com','Spammed you!!');
		$sender = $this->emailFactory->getSender();
		$sender->send($email);
	}	
}

/**
 * Maybe we did not implement any concrete Email* classes, or maybe we just want
 * to disable e-mail sending : how to do that without if/else trees and feature
 * switches ? 
 * 
 * Just wrap the factory with Maybe !
 */
 
$emailFactory = (new \Maybe\Maybe('Maybe\Examples\Email\EmailFactory'))->buildFakeObject();

$spammer = new Spammer($emailFactory);
$spammer->spam();

