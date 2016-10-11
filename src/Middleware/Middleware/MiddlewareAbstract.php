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

use Psr\Http\Message\RequestInterface;
use Phossa2\Shared\Base\ObjectAbstract;
use Psr\Http\Message\ResponseInterface;
use Phossa2\Middleware\Interfaces\DelegateInterface;
use Phossa2\Middleware\Interfaces\MiddlewareInterface;

/**
 * MiddlewareAbstract
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     MiddlewareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class MiddlewareAbstract extends ObjectAbstract implements MiddlewareInterface
{
    /**
     * Make process logic clear !
     *
     * {@inheritDoc}
     */
    public function process(
        RequestInterface $request,
        ResponseInterface $response,
        DelegateInterface $next = null
    ) {
        // before
        $response = $this->before($request, $response);

        // next middleware
        if ($next) {
            $response = $next->next($request, $response);
        }

        // after
        return $this->after($request, $response);
    }

    /**
     * Doing stuff before calling next middleware
     *
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @access protected
     */
    protected function before(
        RequestInterface $request,
        ResponseInterface $response
    )/* : ResponseInterface */ {
        return $response;
    }

    /**
     * Doing stuff after next middleware called
     *
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @access protected
     */
    protected function after(
        RequestInterface $request,
        ResponseInterface $response
    )/* : ResponseInterface */ {
        return $response;
    }
}
