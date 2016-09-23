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

use Zend\Diactoros\Uri;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

abstract class Common extends \PHPUnit_Framework_TestCase
{
    protected function createRequest($uri, array $headers = [], array $server = [])
    {
        return (new ServerRequest($server, [], $uri, null, 'php://temp', $headers))->withUri(new Uri($uri));
    }

    protected function createResponse(array $headers = array())
    {
        return new Response('php://temp', 200, $headers);
    }

    protected function createStream($content = '')
    {
        $stream = new Stream('php://temp', 'r+');
        if ($content) {
            $stream->write($content);
        }
        return $stream;
    }
}
