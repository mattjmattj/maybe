<?php

namespace Maybe\Examples\Log;

require __DIR__ . '/../../vendor/autoload.php';

use Maybe\Maybe;

/*
 * Actor wants an instance of Log to log what it does. But if we don't care, or
 * if we don't know if an actual instance of Log is available, we can wrap Log 
 * with Maybe and be safe without having to change anything in Actor.
 *
 * In this example we pretend to have a Pimple container providing both Log and
 * Actor, but we don't actually have a Log instance
 */
 
$container = new \Pimple\Container();

$container['Actor'] = $container->factory(function ($c) {
	return new Actor ($c['Log']);
});

//we have no actual Log
$container['__Log'] = null;

// MaybeLog wraps Log
$container['MaybeLog'] = function($c) {
	return new Maybe('Maybe\Examples\Log\Log');
};
// and we define a factory to wrap actual instances of Log, if any, using MaybeLog
$container['Log'] = $container->factory(function($c) {
	return $c['MaybeLog']->wrap($c['__Log']);
});


$actor = $container['Actor'];
$actor->doSomething();
// doSomething worked fine without logging anything

/*
 * Now we create an actual Log and get another Actor
 */
 
$container['__Log'] = function($c) {
	return new Log();	
};

$actor = $container['Actor'];
$actor->doSomething();
//now doSomething logs some stuff
