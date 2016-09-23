<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Middleware
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa2\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Middleware\Message\Message;
use Phossa2\Middleware\Exception\LogicException;
use Phossa2\Middleware\Interfaces\QueueInterface;
use Phossa2\Middleware\Interfaces\DelegateInterface;
use Phossa2\Middleware\Interfaces\ConditionInterface;
use Phossa2\Middleware\Interfaces\MiddlewareInterface;

/**
 * Queue
 *
 * Middleware queue
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Queue extends ObjectAbstract implements QueueInterface
{
    /**
     * @var    \SplQueue
     * @access protected
     */
    protected $queue;

    /**
     * Constructor
     *
     * @param  array $middlewares
     * @access public
     */
    public function __construct(array $middlewares = [])
    {
        $this->queue = new \SplQueue();

        foreach ($middlewares as $mw) {
            if (is_array($mw)) { // with condition
                $this->push($mw[0], $mw[1]);
            } else { // without condition
                $this->push($mw);
            }
        }
    }

    /**
     * For compatiblity with other middlewares
     *
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @return ResponseInterface
     * @access public
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response
    )/*# : ResponseInterface */ {
        return $this->next($request, $response);
    }

    /**
     * {@inheritDoc}
     */
    public function push($middleware, $condition = null)
    {
        $this->queue->push([$middleware, $condition]);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function process(
        RequestInterface $request,
        ResponseInterface $response,
        DelegateInterface $next = null
    )/*# : ResponseInterface */ {
        // rewind
        $this->queue->rewind();

        // process the queue
        $response = $this->next($request, $response);

        if ($next) { // queue is part of another queue
            return $next->next($request, $response);
        } else {
            return $response;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function next(
        RequestInterface $request,
        ResponseInterface $response
    )/*# : ResponseInterface */ {
        if ($this->queue->valid()) {
            list($middleware, $condition) = $this->queue->current();
            $this->queue->next();

            if (null === $condition ||
                $this->evalCondition($condition, $request, $response)
            ) { // run this mw
                return $this->runMiddleware($middleware, $request, $response);
            } else { // skip this mw
                return $this->next($request, $response);
            }
        }
        return $response; // end of the queue reached
    }

    /**
     * Process/run this middleware
     *
     * @param  MiddlewareInterface|callable $middleware
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @return ResponseInterface
     * @throws LogicException if invalid middleware type
     * @access protected
     */
    protected function runMiddleware(
        $middleware,
        RequestInterface $request,
        ResponseInterface $response
    )/*# : ResponseInterface */ {
        // old style callable
        if (is_callable($middleware)) {
            return $middleware($request, $response, $this);

        // instance of MiddlewareInterface
        } elseif (is_object($middleware) && $middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $response, $this);

        // unknown middleware type
        } else {
            throw new LogicException(
                Message::get(Message::MIDDLEWARE_INVALID, $middleware),
                Message::MIDDLEWARE_INVALID
            );
        }
    }

    /**
     * Evaluate the condition
     *
     * support both a callable returns bool value or an object instance of
     * ConditionInterface.
     *
     * @param  ConditionInterface|callable $condition
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @return bool
     * @throws LogicException if condition is invalid
     * @access protected
     */
    protected function evalCondition(
        $condition,
        RequestInterface $request,
        ResponseInterface $response
    )/*# : bool */ {
        // old style callable
        if (is_callable($condition)) {
            return $condition($request, $response);

        // instanceof ConditionInterface
        } elseif (is_object($condition) && $condition instanceof ConditionInterface) {
            return $condition->evaluate($request, $response);

        // unknown type
        } else {
            throw new LogicException(
                Message::get(Message::CONDITION_INVALID, $condition),
                Message::CONDITION_INVALID
            );
        }
    }
}
