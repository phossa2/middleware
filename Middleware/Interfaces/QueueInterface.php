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

/**
 * QueueInterface
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     MiddlewareInterface
 * @see     DelegateInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface QueueInterface extends MiddlewareInterface, DelegateInterface
{
    /**
     * Push to the end of the queue
     *
     * @param  MiddlewareInterface|callable $middleware
     * @param  ConditionInterface|callable|null $condition
     * @return $this
     * @access public
     * @api
     */
    public function push($middleware, $condition = null);
}
