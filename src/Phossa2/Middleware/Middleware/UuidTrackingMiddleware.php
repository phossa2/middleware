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
use Psr\Http\Message\ResponseInterface;

/**
 * UuidTrackingMiddleware
 *
 * Tracking user with followings.
 *
 * - unique user uuid
 * - current session uuid
 * - current request uuid
 *
 * @package Phossa2\Middlewaer
 * @author  Hong Zhang <phossa@126.com>
 * @see     MiddlewareAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class UuidTrackingMiddleware extends MiddlewareAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function before(
        RequestInterface $request,
        ResponseInterface $response
    )/* : ResponseInterface */ {
    }

    /**
     * Doing nothing afterwards
     *
     * {@inheritDoc}
     */
    protected function after(
        RequestInterface $request,
        ResponseInterface $response
    )/* : ResponseInterface */ {
        return $response;
    }
}
