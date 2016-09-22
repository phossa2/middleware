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
 * ConditionInterface
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ConditionInterface
{
    /**
     * Evalute to true/false
     *
     * @param  RequestInterface $request
     * @param  ResponseInterface $response
     * @return bool
     * @access public
     * @api
     */
    public function evaluate(
        RequestInterface $request,
        ResponseInterface $response
    )/*# : bool */;
}
