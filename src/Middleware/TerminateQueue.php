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

/**
 * TerminateQueue
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     Queue
 * @version 2.0.1
 * @since   2.0.1 added
 */
class TerminateQueue extends Queue
{
    /**
     * @var    bool
     * @access protected
     */
    protected $terminate = true;
}
