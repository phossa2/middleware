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

namespace Phossa2\Middleware\Middleware;

use Phossa2\Route\Dispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Phossa2RouteMiddleware
 *
 * Using phossa2/route to dispatching
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     MiddlewareAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Phossa2RouteMiddleware extends MiddlewareAbstract
{
    /**
     * @var    Dispatcher
     * @access protected
     */
    protected $dispatcher;

    /**
     * @param  Dispatcher $dispatcher
     * @access public
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    protected function before(
        RequestInterface $request,
        ResponseInterface $response
    )/* : ResponseInterface */ {
        $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath(),
            ['request' => $request, 'response' => $response]
        );

        // response in the result
        return $this->dispatcher->getResult()->getParameters()['response'];
    }
}
