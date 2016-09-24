<?php

namespace Phossa2\Middleware;

require_once __DIR__ . '/Common.php';

/**
 * TerminateQueue test case.
 */
class TerminateQueueTest extends Common
{
    /**
     * @var Queue
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new TerminateQueue();

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
     * Tests queue terminate
     *
     * @cover Phossa2\Middleware\TerminateQueue::process()
     */
    public function testProcess()
    {
        $data = [
            // queue branching (end here)
            [new TerminateQueue($this->data, true), function($request, $response) {return true;}],

            function($request, $response, $next) {
                echo "MW_3_S ";
                $response = $next($request, $response);
                echo "MW_3_E ";
                return $response;
            },
        ];

        $object = new Queue($data);
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E ");
        $object->process($this->createRequest('/test'), $this->createResponse());
    }
}
