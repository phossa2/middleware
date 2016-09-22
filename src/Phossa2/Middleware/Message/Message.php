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

namespace Phossa2\Middleware\Message;

use Phossa2\Shared\Message\Message as BaseMessage;

/**
 * Message class for Phossa2\Middleware
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa2\Shared\Message\Message
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Message extends BaseMessage
{
    /*
     * Invalid condition "%s" supplied
     */
    const CONDITION_INVALID = 1609221327;

    /*
     * Invalid middleware "%s"
     */
    const MIDDLEWARE_INVALID = 1609221328;

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::CONDITION_INVALID => 'Invalid condition "%s" supplied',
        self::MIDDLEWARE_INVALID => 'Invalid middleware "%s"',
    ];
}
