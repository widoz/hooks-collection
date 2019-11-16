# WP Hooks

[![Travis](https://img.shields.io/travis/widoz/wp-hooks.svg?style=flat-square)](https://travis-ci.org/widoz/wp-hooks)
[![Codecov](https://img.shields.io/codecov/c/github/widoz/wp-hooks.svg?style=flat-square)](https://codecov.io/gh/widoz/wp-hooks)
[![Packagist](https://img.shields.io/packagist/l/widoz/wp-hooks.svg?style=flat-square)](https://packagist.org/packages/widoz/wp-hooks)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/widoz/wp-hooks.svg?style=flat-square)](https://packagist.org/packages/widoz/wp-hooks)

WP Hooks is a PHP library which abstract the hook concept from WordPress in order to create an interop standard.

The library provide some hook dispatchers but it is also possible to create custom ones.

## Advantages

**An hook created in a library may be removed by another one**

Let's say you add an hook from a dependency, another dependency of the application may later on remove that hook because when you create an hook you get back an instance of a dispatcher you can   

## Requirements

PHP >= 7.1

## Introduction

WP Hooks introduce the concept of _Hook Dispatcher_.

When you create a new Hook you create a dispatcher which get a callback to which pass the parameters when the hook will be fired by the system.

That's it, the Dispatcher forward the parameters passed to it to the inner callback and will return whathever the callback returns.

Currently the library implements the following dispatchers:

- SingleHookTypeAwareDispatcher
- NTimesHookTypeAwareDispatcher
- RemovableHookTypeAwareDispatcher

But you can create more.

### Filter and Actions

WP Hooks remove the concept of filters and actions from the contracts and hide it in the implementation.

This is because not all systems may have differences between filters and actions.

WordPress it self define _actions_ as decorators for _filters_ and because basically every function returns something even when it's declare `void` because `null` is returned, WP Hooks define just one concept
*Hooks*. It is up to the implementation to return something or not.

## How to use

You can create new hooks in two ways, by using the functional api or via OOP.

### OOP Way

Every dispatcher has a Factory related, this is because you may want to add new hooks in the system at runtime and not only at configuration. 

Factories are named like the dispatcher implementation and suffixed with `Factory`.

To get an instance of an hook dispatcher you may simply need to inject the factory and then call the `create` method on it by passing the hook instance and the additional parameters.

```php
$dispatcher = $this->hookDispatcherFactory->create(new WpHook(
	'hook_name',
	function(...$parameters) {
		// Do something with $parameters
	},
	10,
	2
));
```

When you create a new hook dispatcher the hook is automatically injected into the system (this is up to the factory). 

Since the dispatcher instance is returned by the factory, you may also call it manually and pass parameters to it which will be forwarded to the hook callback you passed to `WpHook`.

```php
$dispatcher($parameter, $anotherParameter);
```

### Functional Way

Every hook factory, WP Hooks provide a functional implementation which allow to create new hooks.

For example

```php
use Widoz\WpHooks\addActionOnce;

$dispatcher = addActionOnce(
	'hook_name',
	function(...$parameters) {
		// Do something with $parameters
	},
	10,
	2
);
```

The rest is practical identically to the OOP way because the function uses the factories internally.

## Hooks, Dispatchers & Factories

### Hook

An Hook is a data holding class which is injected into Dispachers and Factories.

Basically an hook contains a *name*, *priority*, *callback* and the *arguments number* the callback
can get passed.

The hook is usually consumed by Dispatchers, Factories and Hook Removers.

You do not consume the Hooks directly but you work with Dispatchers instead.

### Dispatcher

A Dispatcher is basically a wrapper for an Hook callback, it holds the Hook on which it call the callback and it remove or perform additional tasks on that.

To create an hook dispatcher you have to declare a class which implements the `HookDispatcher`.

```php
final class MyHookDispatcher implements HookDispatcher
{
	public function __invoke(...$parameters) {
		// Here you dispatch the hook
	}
}
```

Even though is not strictly necessary to create a class because you can simply define another callback since the `HookDispatcher` has to implement an invokable, it is strongly suggested to stick with OOP because you may want to introduce additional logic to *remove* or handle the dispatch based on a hook type or other stuffs.

For example to allow to remove the hook programmatically you have to implements a `RemoveCapableHookDispatcher` or if you want to remove it automatically based on a condition you may want to inject an `HookRemover`.

More over, since Hooks are usually created at Runtime you may want to create a *Factory* for you class instances you can inject at construction time.

To create an Hook Dispatcher Factory you have to implements `HookDispatcherFactory` which expose a method `create` that get passed an `Hook` instace and extra arguments you may need to inject into your Hook Dispatcher at construction time.

```php
class SingleHookDispatcher implements HookDispatcher
{
    /**
     * @var Hook
     */
    private $hook;

    /**
     * @var HookRemover
     */
    private $hookRemover;

    /**
     * SingleFilter constructor
     * @param Hook $hook
     * @param HookRemover $hookRemover
     */
    public function __construct(Hook $hook, HookRemover $hookRemover)
    {
        $this->hook = $hook;
        $this->hookRemover = $hookRemover;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(...$parameters)
    {
        // phpcs:enable

        $this->hookRemover->remove($this->hook, $this);

        return $this->hook->callback()(...$parameters);
    }
}
```

The implementation is pretty straightfoward, the hook is removed before get executed.
This means the filter can run only once.

Note that in order to be able to detach the hook you have to pass the instance of the current class plus the instance of the hook because otherwise WordPress will not be able to recognize which callback associated to the hook you want to remove.

### Factory

```php
class SingleHookTypeAwareDispatcherFactory implements HookDispatcherFactory
{
    /**
     * @var HookType
     */
    protected $hookType;

    /**
     * @var HookInjector
     */
    protected $hookInjector;

    /**
     * SingleHookTypeAwareFactory constructor
     * @param HookType $hookType
     * @param HookInjector $hookInjector
     */
    public function __construct(HookType $hookType, HookInjector $hookInjector)
    {
        $this->hookType = $hookType;
        $this->hookInjector = $hookInjector;
    }

    /**
     * @inheritDoc
     */
    public function create(Hook $hook, array $extraArguments): HookDispatcher
    {
        $hookRemover = new HookTypeAwareRemover($this->hookType);
        $singleHookDispatcher = new SingleHookDispatcher($hook, $hookRemover);

        $this->hookInjector->addHook($hook, $singleHookDispatcher);

        return $singleHookDispatcher;
    }
}
```

## Injector

## HookFactory

## HookRemover
