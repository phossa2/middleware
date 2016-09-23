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
 * MultipleCondition
 *
 * Combine multiple conditions into one
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ConditionInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class MultipleCondition extends ObjectAbstract implements ConditionInterface
{
    /**
     * @var    array
     * @access protected
     */
    protected $conditions;

    /**
     * @param  array $conditions
     * @access public
     */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(
        RequestInterface $request,
        ResponseInterface $response
    )/*# : bool */ {
        foreach ($this->conditions as $cond) {
            if (is_callable($cond)) {
                $res = $cond($request, $response);
            } else {
                $res = $cond->evaluate($request, $response);
            }
            if (!$res) {
                return false;
            }
        }
        return true;
    }
}
