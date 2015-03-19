Maybe
=========

[![Build Status](https://travis-ci.org/mattjmattj/maybe.svg)](https://travis-ci.org/mattjmattj/maybe)

Maybe wraps a class and provides a way to abstract away error handling when dealing with undefined instances. It might help implement feature switches, dev/prod environments switches, etc.

# Installation

with composer

```
composer.phar require mattjmattj/maybe ~1.0
```

# Basic usage

```php
use Maybe\Maybe;

/*
 * Create a Maybe instance for the desired class or interface.
 */
$maybe = new Maybe('Some\Class');

/*
 * Wrap some object that you don't know much about : 
 * might be null or an actual instance of Some\Class.
 */
$wrapped = $maybe->wrap($someContainer->getSomeClassInstance());

/*
 * Call whatever you want on the wrapped object without having
 * to worry about whether $someContainer->getSomeClassInstance()
 * return something or not.
 */
$wrapped->doSomeInterestingThing();

/*
 * You can also call a method at a deeper level. Maybe will wrap returned types
 * if it finds proper @return annotations
 */
$wrapped->getSomeService()->doSomeJob();
```

# Example

An example is provided in the "example" folder.

# Warning

Not every kind of object should be wrapped. Typically you will want to wrap classes that provide logs, debug, cache, events, etc. You don't want to wrap "useful" classes that actually alter the logic of your code.

# License

Maybe is licensed under BSD-2-Clause license.