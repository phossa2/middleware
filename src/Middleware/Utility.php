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

use Psr\Http\Message\ResponseInterface;

/**
 * Utility
 *
 * Utilities under PSR-7, set cookie etc.
 *
 * Modified from Relay.Middleware/blob/1.x/src/SessionHeadersHandler.php
 *
 * @package Phossa2\Middleware
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.0
 * @since   2.1.0 added
 */
class Utility
{
    /**
     * Set a cookie in the response
     *
     * @param  ResponseInterface $response
     * @param  string $name
     * @param  string $value
     * @param  int $ttl
     * @param  string $path
     * @param  string $domain
     * @param  bool $secure
     * @param  bool $httponly
     * @return ResponseInterface
     * @access public
     */
    public static function setCookie(
        ResponseInterface $response,
        /*# string */ $name,
        /*# string */ $value = null,
        /*# int */ $ttl = null,
        /*# string */ $path = null,
        /*# string */ $domain = null,
        /*# bool */ $secure = false,
        /*# bool */ $httponly = true
    )/*# : ResponseInterface */ {
        $cookie = urlencode($name) . '=' . urlencode($value);

        self::addExpire($cookie, $ttl);

        self::addDomain($cookie, $domain);

        self::addPath($cookie, $path);

        self::addSecure($cookie, $secure);

        self::addHttpOnly($cookie, $httponly);

        return $response->withAddedHeader('Set-Cookie', $cookie);
    }

    /**
     * Unset a cookie
     *
     * @param  ResponseInterface $response
     * @param  string $name
     * @param  string $path
     * @return ResponseInterface
     * @access public
     */
    public static function unsetCookie(
        ResponseInterface $response,
        /*# string */ $name,
        /*# string */ $path = null
    )/*# : ResponseInterface */ {
        return self::setCookie($response, $name, '', time() - 86400, $path);
    }

    /**
     * Set public cache header
     *
     * @param  ResponseInterface $response
     * @param  int $cacheTime cache time in minutes
     * @return ResponseInterface
     * @access public
     */
    public static function publicCache(
        ResponseInterface $response,
        /*# int */ $cacheTime = 120
    )/*# : ResponseInterface */ {
        $maxAge = $cacheTime * 60;
        return $response
            ->withAddedHeader('Expires', self::timeStamp($maxAge))
            ->withAddedHeader('Cache-Control', "public, max-age={$maxAge}")
            ->withAddedHeader('Last-Modified', self::timeStamp());
    }

    /**
     * Set private_no_expire cache header
     *
     * @param  ResponseInterface $response
     * @param  int $cacheTime cache time in minutes
     * @return ResponseInterface
     * @access public
     */
    public static function privateNoExpireCache(
        ResponseInterface $response,
        /*# int */ $cacheTime = 120
    )/*# : ResponseInterface */ {
        $maxAge = $cacheTime * 60;
        return $response
            ->withAddedHeader('Cache-Control', "private, max-age={$maxAge}, pre-check={$maxAge}")
            ->withAddedHeader('Last-Modified', self::timeStamp());
    }

    /**
     * Set private cache header
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     * @access protected
     */
    public static function privateCache(
        ResponseInterface $response
    )/*# : ResponseInterface */ {
        return self::privateNoExpireCache(
            $response->withAddedHeader('Expires', self::timeStamp(-3153600))
        );
    }

    /**
     * Set no cache header
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     * @access public
     */
    public static function noCache(
        ResponseInterface $response
    )/*# : ResponseInterface */ {
        return $response
            ->withAddedHeader('Expires', self::timeStamp(-3153600))
            ->withAddedHeader(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
            )
            ->withAddedHeader('Pragma', 'no-cache');
    }

    protected static function timeStamp(/*# int */ $ttl= 0)
    {
        return gmdate('D, d M Y H:i:s T', time() + $ttl);
    }

    protected static function addExpire(/*# string */ &$cookie, $ttl)
    {
        if ($ttl) {
            $expires = self::timeStamp($ttl);
            $cookie .= "; expires={$expires}; max-age={$ttl}";
        }
    }

    protected static function addDomain(/*# string */ &$cookie, $domain)
    {
        if ($domain) {
            $cookie .= "; domain={$domain}";
        }
    }

    protected static function addPath(/*# string */ &$cookie, $path)
    {
        if ($path) {
            $cookie .= "; path={$path}";
        }
    }

    protected static function addSecure(/*# string */ &$cookie, $secure)
    {
        if ($secure) {
            $cookie .= '; secure';
        }
    }

    protected static function addHttpOnly(/*# string */ &$cookie, $httponly)
    {
        if ($httponly) {
            $cookie .= '; httponly';
        }
    }
}
