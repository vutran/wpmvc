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
     * Tests setting and getting the template path
     *
     * @access public
     * @return void
     */
    public function testSetGetTemplatePath()
    {
        $app = new Bootstrap;
        $app->setTemplatePath('/users/MyName/test/path');
        $this->assertSame('/users/MyName/test/path', $app->getTemplatePath());
    }

    /**
     * Tests setting and getting the template directory
     *
     * @access public
     * @return void
     */
    public function testSetGetTemplateDir()
    {
        $app = new Bootstrap;
        $app->setTemplateDir('/test/dir');
        $this->assertSame('/test/dir', $app->getTemplateDir());
    }

    /**
     * Tests setting and getting the template URL
     *
     * @access public
     * @return void
     */
    public function testSetGetTemplateUrl()
    {
        $app = new Bootstrap;
        $app->setTemplateUrl('http://mywebsite.com/test/dir');
        $this->assertSame('http://mywebsite.com/test/dir', $app->getTemplateUrl());
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
        $view = $app->createView('test');
        $this->assertInstanceOf('\WPMVC\Models\View', $view);
    }
}
