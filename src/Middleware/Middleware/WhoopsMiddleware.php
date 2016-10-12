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
use Franzl\Middleware\Whoops\WhoopsRunner;
use Phossa2\Middleware\Interfaces\DelegateInterface;
use Phossa2\Middleware\Interfaces\MiddlewareInterface;

/**
 * WhoopsMiddleware
 *
 * Using "franzl/whoops-middleware" for Whoops
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.0
 * @since   2.1.0 added
 */
class WhoopsMiddleware extends ObjectAbstract implements MiddlewareInterface
{
    /**
     * Should be the very first middleware in the queue
     *
     * {@inheritDoc}
     */
    public function process(
        RequestInterface $request,
        ResponseInterface $response,
        DelegateInterface $next = null
    ) {
        try {
            if ($next) {
                return $next->next($request, $response);
            }
            return $response;
        } catch (\Exception $e) {
            return WhoopsRunner::handle($e, $request);
        }
    }
}
