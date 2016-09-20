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

namespace Phossa2\Middleware\Interfaces;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * ClientMiddlewareInterface
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     MiddlewareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ClientMiddlewareInterface extends MiddlewareInterface
{
    /**
     * Process a client request and return a response.
     *
     * Takes the incoming request and optionally modifies it before delegating
     * to the next frame to get a response.
     *
     * @param RequestInterface $request
     * @param DelegateInterface $next
     *
     * @return ResponseInterface
     */
    public function process(
        RequestInterface $request,
        DelegateInterface $next
    );
}
