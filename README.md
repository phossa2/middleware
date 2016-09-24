# phossa2/middleware
[![Build Status](https://travis-ci.org/phossa2/middleware.svg?branch=master)](https://travis-ci.org/phossa2/middleware)
[![Code Quality](https://scrutinizer-ci.com/g/phossa2/middleware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phossa2/middleware/)
[![PHP 7 ready](http://php7ready.timesplinter.ch/phossa2/middleware/master/badge.svg)](https://travis-ci.org/phossa2/middleware)
[![HHVM](https://img.shields.io/hhvm/phossa2/middleware.svg?style=flat)](http://hhvm.h4cc.de/package/phossa2/middleware)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa2/middleware.svg?style=flat)](https://packagist.org/packages/phossa2/middleware)
[![License](https://poser.pugx.org/phossa2/middleware/license)](http://mit-license.org/)

**phossa2/middleware** is another cool middleware runner library for PHP.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with [PSR-1][PSR-1],
[PSR-2][PSR-2], [PSR-3][PSR-3], [PSR-4][PSR-4], [PSR-7][PSR-7] and the proposed
[PSR-5][PSR-5]

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md "PSR-5: PHPDoc"
[PSR-7]: http://www.php-fig.org/psr/psr-7/ "PSR-7: HTTP Message Interfaces"
[Container Interop]: https://github.com/container-interop/container-interop "Container-Interop"

Why another middleware runner ?
---

- It was started to be a [PSR-15](https://github.com/php-fig/fig-standards/tree/master/proposed/http-middleware)
  compatible middleware runner. But we don't like the single-pass approach of
  PSR-15. So it turns out to be a double-pass and PSR-15ish library.

- Adopted nice feature of [`condition`](#condition) from
  [woohoolabs/harmony](https://github.com/woohoolabs/harmony). But we don't
  agree its tight-binding with dispatcher.

- A couple of cool [features](#features) unique to this library.

Installation
---
Install via the `composer` utility.

```bash
composer require "phossa2/middleware"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/middleware": "^2.0.0"
    }
}
```

<a name="features"></a>Features
---

- Able to [use](#comp) most of the double-pass middlewares out there.

- Able to use a middleware [queue](#queue) (a group of middlewares) as a
  generic middleware in another(or the main) queue.

- Able to conditionally execute a middleware or a sub queue base on a
  [condition](#condition).

- Able to [branching](#branch) into a subqueue and terminate when the subqueue
  finishes.

Usage
---

Create the middleware queue, then process all the middlewares.

```php
use Phossa2\Middleware\Queue;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

// create middleware queue
$mws = new Queue([
    new LoggerMiddleware(),
    new DispatcherMiddleware()
]);

// process the queue
$response = $mws->process(ServerRequestFactory::fromGlobals(), new Response());
```

Or push middlewares to the queue after its instantiation,

```php
$mws = (new Queue())
    ->push(new LoggerMiddleware())
    ->push(new DispatcherMiddleware());
```

Advanced
---

- <a name="comp"></a>Compatibility with PSR-7 middlewares.

  PSR-7 double-pass middleware with the following signature is supported,

  ```php
  use Psr\Http\Message\RequestInterface;
  use Psr\Http\Message\ResponseInterface;

  function (
      RequestInterface $request,
      ResponseInterface $response,
      callable $next
  ) : ResponseInterface {
      // ...
  }
  ```

  Lots of middlewares out there then can be used without modification, such as
  [psr7-middlewares](https://github.com/oscarotero/psr7-middlewares).

- <a name="queue"></a>Subqueue

  `Phossa2\Middleware\Queue` implements the `Phossa2\Middleware\Interfaces\MiddlewareInterface`,
  so the queue itself can be used as a generic middleware.

  ```php
  // subqueue
  $subQueue = new Queue([
      new ResponseTimeMiddleware(),
      new LoggingMiddleware(),
      // ...
  ]);

  // main middleware queue
  $mws = new Queue([
      $subQueue,
      new DispatcherMiddleware(),
      // ...
  ]);

  $response = $mws->process(ServerRequestFactory::fromGlobals(), new Response());
  ```

- <a name="condition"></a>Use of conditions

  A `condition` is a callable with the signature of,

  ```php
  function (RequestInterface $request, ResponseInterface $response) : bool
  {
      // ...
  }
  ```

  Or an instanceof `Phossa2\Middleware\Interfaces\ConditionInterface`.

  A condition can be attached to a middleware or a subqueue. And the
  middleware will be executed only if the condition is evaluated to `TRUE`.

  ```php
  // add condition during instantiation
  $mws = new Queue([
      [$subQueue, new DebugTurnedOnCondition()],
      new DispatcherMiddleware(),
  ]);

  // or during the push
  $mws->push(new AuthMiddleware(), new PathPrefixCondition('/user'));
  ```

- <a name="branch"></a>Subqueue termination - branching

  Sometimes, user wants the whole middleware processing terminate right after
  a subqueue finishes instead of continue processing the parent queue.

  ```php
  // use terminatable queue
  $tQueue = new TerminateQueue([...]);

  $mws = new Queue([
      [$tQueue, new SomeCondition()], // execute & terminate if condition true
      $mw2,
      $mw3,
      // ...
  ]);

  $response = $mws->process($request, $response);
  ```

Change log
---

Please see [CHANGELOG](CHANGELOG.md) from more information.

Testing
---

```bash
$ composer test
```

Contributing
---

Please see [CONTRIBUTE](CONTRIBUTE.md) for more information.

Dependencies
---
Requirements
---

- PHP >= 5.4.0

- phossa2/shared >= 2.0.21

- A PSR-7 HTTP message implementation, such as [zend-diactoros](https://github.com/zendframework/zend-diactoros)

License
---

[MIT License](http://mit-license.org/)
