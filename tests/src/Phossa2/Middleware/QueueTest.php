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
        $object = new Queue($this->data);
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E ");
        $object->process($this->createRequest('/test'), $this->createResponse());
    }

    /**
     * Tests Queue->__invoke()
     */
    public function test__invoke()
    {
    }

    /**
     * Tests Queue->push()
     *
     * @cover Phossa2\Middleware\Queue::push()
     */
    public function testPush()
    {
        // no condition
        foreach ($this->data as $mw) {
            $this->object->push($mw);
        }
        $this->expectOutputString("MW_1_S MW_2_S MW_2_E MW_1_E ");
        $this->object->process($this->createRequest('/test'), $this->createResponse());
    }

    /**
     * Tests Queue->process()
     */
    public function testProcess()
    {
    }

    /**
     * Tests Queue->next()
     */
    public function testNext()
    {
    }
}

