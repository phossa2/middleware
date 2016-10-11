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

use Phossa2\Session\Carton;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phossa2\Session\Interfaces\SessionInterface;

/**
 * Phossa2SessionMiddleware
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     MiddlewareAbstract
 * @version 2.1.0
 * @since   2.1.0 added
 */
class Phossa2SessionMiddleware extends MiddlewareAbstract
{
    /**
     * @var    SessionInterface
     * @access protected
     */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        // set as default session
        Carton::setDefaultSession($this->session);
    }

    /**
     * {@inheritDoc}
     */
    protected function before(
        RequestInterface $request,
        ResponseInterface $response
    )/* : ResponseInterface */ {
        return $response;
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
        $this->session->close();
        return $response;
    }
}
