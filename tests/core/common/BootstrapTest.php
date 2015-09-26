<?php

namespace WPMVC\Tests\Common;

use \WPMVC\Common\Bootstrap;
use \PHPUnit_Framework_TestCase;

class BootstrapTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests creating a app instance
     *
     * @access public
     * @return \WPMVC\Common\Bootstrap
     */
    public function testCreateInstance()
    {
        $app = new Bootstrap([
            'templatePath' => '/users/MyName/test/path',
            'templateDir' => '/test/path',
            'templateUrl' => 'http://mywebsite.com/test/path'
        ]);
        return $app;
    }

    /**
     * Tests creating a View instance
     *
     * @access public
     * @depends testCreateInstance
     * @param \WPMVC\Common\Bootstrap $app
     * @return void
     */
    public function testCreateView($app)
    {
        $view = $app->createView('test.php');
        $this->assertInstanceOf('\WPMVC\Models\View', $view);
    }
}
