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
 * @version 2.0.1
 * @since   2.0.0 added
 * @since   2.0.1 added $terminate
 */
class Queue extends ObjectAbstract implements QueueInterface
{
    /**
     * @var    \SplQueue
     * @access protected
     */
    protected $queue;

    /**
     * @var    bool
     * @access protected
     */
    protected $terminate = false;

    /**
     * Constructor
     *
     * @param  array $middlewares
     * @access public
     */
    public function __construct(
        array $middlewares = []
    ) {
        // create the queue
        $this->queue = new \SplQueue();

        // fill the queue with middlewares
        $this->fillTheQueue($middlewares);
    }

    /**
     * Compatible with middlewares of the signature
     *
     * ```php
     * fn($request, $response, callable $next)
     * ```
     *
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @param  DelegateInterface $next
     * @return ResponseInterface
     * @access public
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        DelegateInterface $next = null
    )/*# : ResponseInterface */ {
        return $this->process($request, $response, $next);
    }

    /**
     * {@inheritDoc}
     */
    public function push($middleware, $condition = null)
    {
        $this->queue->push([$middleware, $condition]);
        $this->queue->rewind();
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
        // process the queue
        $response = $this->next($request, $response);

        // $next is parent queue
        return ($next && !$this->terminate) ?
            $next->next($request, $response) :
            $response;
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
        $this->queue->rewind();
        return $response;
    }

    /**
     * Fill the queue with middlewares
     *
     * @param  array $middlewares
     * @access protected
     */
    protected function fillTheQueue(array $middlewares)
    {
        foreach ($middlewares as $mw) {
            // with conditions specified
            if (is_array($mw)) {
                $this->push($mw[0], $mw[1]);

            // no condition
            } else {
                $this->push($mw);
            }
        }
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
        // instance of MiddlewareInterface
        if (is_object($middleware) && $middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $response, $this);

        // old style callable
        } elseif (is_callable($middleware)) {
            return $middleware($request, $response, $this);

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
        // instanceof ConditionInterface
        if (is_object($condition) && $condition instanceof ConditionInterface) {
            return $condition->evaluate($request, $response);

        // old style callable
        } elseif (is_callable($condition)) {
            return $condition($request, $response);

        // unknown type
        } else {
            throw new LogicException(
                Message::get(Message::CONDITION_INVALID, $condition),
                Message::CONDITION_INVALID
            );
        }
    }
}
