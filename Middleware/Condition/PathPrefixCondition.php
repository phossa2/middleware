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
 * PathPrefixCondition
 *
 * Modified from https://github.com/woohoolabs/harmony
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ConditionInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class PathPrefixCondition extends ObjectAbstract implements ConditionInterface
{
    /**
     * @var    string[]
     * @access protected
     */
    protected $prefix;

    /**
     * @param  string|string[] $pathPrefix
     * @access public
     */
    public function __construct($pathPrefix)
    {
        $this->prefix = (array) $pathPrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(
        RequestInterface $request,
        ResponseInterface $response
    )/*# : bool */ {
        foreach ($this->prefix as $pref) {
            if (0 === strpos($request->getUri()->getPath(), $pref)) {
                return true;
            }
        }
        return false;
    }
}
