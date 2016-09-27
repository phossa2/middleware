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

namespace Phossa2\Middleware\Condition;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Middleware\Interfaces\ConditionInterface;

/**
 * HttpMethodCondition
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ConditionInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class HttpMethodCondition extends ObjectAbstract implements ConditionInterface
{
    /**
     * @var    string[]
     * @access protected
     */
    protected $methods;

    /**
     * @param  string|string[] $methods
     * @access public
     */
    public function __construct($methods)
    {
        $this->methods = (array) $methods;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(
        RequestInterface $request,
        ResponseInterface $response
    )/*# : bool */ {
        return in_array($request->getMethod(), $this->methods);
    }
}
