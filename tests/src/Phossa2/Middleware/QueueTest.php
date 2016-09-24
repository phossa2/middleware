<?php

namespace Phossa2\Middleware;

require_once __DIR__ . '/Common.php';

/**
 * Queue test case.
 */
class QueueTest extends Common
{
    /**
     * @var Queue
     */
    private $object;
    private $data;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new Queue([
        ]);

        $this->data = [
            function($request, $response, $next) {
                echo "MW_1_S ";
                $response = $next($request, $response);
                echo "MW_1_E ";
                return $response;
            },
            function($request, $response, $next) {
                echo "MW_2_S ";
                $response = $next($request, $response);
                echo "MW_2_E ";
                return $response;
            },
        ];
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * Tests Queue->__construct()
     *
     * @cover Phossa2\Middleware\Queue::__construct()
     */
    public function test__construct()
    {
        // construct with array of middlewares
        $object = new Queue($this->data);
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E ");
        $object->process($this->createRequest('/test'), $this->createResponse());
    }

    /**
     * Tests Queue->__invoke()
     *
     * @cover Phossa2\Middleware\Queue::__invoke()
     */
    public function test__invoke()
    {
        // invoke as callable
        $object = new Queue($this->data);
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E ");
        $object($this->createRequest('/test'), $this->createResponse());
    }

    /**
     * Tests Queue->push()
     *
     * @cover Phossa2\Middleware\Queue::push()
     */
    public function testPush()
    {
        // push one by one
        foreach ($this->data as $mw) {
            $this->object->push($mw);
        }

        // process
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E ");
        $this->object->process($this->createRequest('/test'), $this->createResponse());
    }

    /**
     * process with conditions
     *
     * @cover Phossa2\Middleware\Queue::process()
     */
    public function testProcess1()
    {
        // add conditions
        $data = $this->data;
        $data[0] = [$data[0], function($request, $response) {return false;} ];

        // process
        $object = new Queue($data);
        $this->expectOutputString("MW_2_S MW_2_E ");
        $object($this->createRequest('/test'), $this->createResponse());
    }

    /**
     * Tests queue in queue
     *
     * @cover Phossa2\Middleware\Queue::process()
     */
    public function testProcess2()
    {
        $data = [
            new Queue($this->data), // queue as middleware
            function($request, $response, $next) {
                echo "MW_3_S ";
                $response = $next($request, $response);
                echo "MW_3_E ";
                return $response;
            },
        ];

        $object = new Queue($data);
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E MW_3_S MW_3_E ");
        $object->process($this->createRequest('/test'), $this->createResponse());
    }
}
