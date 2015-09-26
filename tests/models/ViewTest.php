<?php

namespace WPMVC\Tests\Models;

use \WPMVC\Models\View;
use \PHPUnit_Framework_TestCase;

class ViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests create the View instance
     *
     * @access public
     * @return void
     */
    public function testCreateInstance()
    {
        $view = new View;
        $this->assertInstanceOf('\WPMVC\Models\View', $view);
    }

    /**
     * Tests setting and getting the paths
     *
     * @access public
     * @return void
     */
    public function testPath()
    {
        $view = new View;
        $view->setPath('/test/path');
        $this->assertSame('/test/path', $view->getPath());
    }

    /**
     * Tesst setting and getting the file
     *
     * @access public
     * @return void
     */
    public function testFile()
    {
        $view = new View;
        $view->setFile('test');
        // Assert without extension
        $this->assertSame('test', $view->getFile());
        $this->assertSame('test', $view->getFile(false));
        // Assert with extension
        $this->assertSame('test.php', $view->getFile(true));
    }

    /**
     * Tests setting a single variable
     *
     * @access public
     * @return void
     */
    public function testSetGetVar()
    {
        $view = new View;
        $view->setVar('foo', 'bar');
        $this->assertSame('bar', $view->getVar('foo'));
    }

    /**
     * Tests setting multiple variables
     *
     * @access public
     * @return void
     */
    public function testSetGetVars()
    {
        $view = new View;
        $view->setVars([
            'foo' => 'bar',
            'baz' => 'qux'
        ]);
        $this->assertSame('bar', $view->getVar('foo'));
        $this->assertSame('qux', $view->getVar('baz'));
    }

    /**
     * Tests the set() method by passing in 2 strings (key, and value)
     *
     * @access public
     * @return void
     */
    public function testSetGetString()
    {
        $view = new View;
        $view->set('foo', 'bar');
        $this->assertSame('bar', $view->getVar('foo'));
    }

    /**
     * Tests the set() method by passing in an array of variables
     *
     * @access public
     * @return void
     */
    public function testSetGetArray()
    {
        $view = new View;
        $view->set([
            'foo' => 'bar',
            'baz' => 'qux'
        ]);
        $this->assertSame('bar', $view->getVar('foo'));
        $this->assertSame('qux', $view->getVar('baz'));
    }
}
