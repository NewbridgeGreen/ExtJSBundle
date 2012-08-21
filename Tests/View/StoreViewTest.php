<?php

namespace NewbridgeGreen\ExtJSBundle\Tests\View;

use NewbridgeGreen\ExtJSBundle\View\StoreView;

class StoreViewTest extends \PHPUnit_Framework_TestCase
{

    public function testStoreConstructorData()
    {
        $item = new \StdClass();
        $items = array($item);
        $view = StoreView::create($items);
        $this->assertEquals(1, $view->getCount());
    }

    public function testStoreSetData()
    {
        $item = new \StdClass();
        $items = array($item);
        $view = StoreView::create()->setData($items);
        $this->assertEquals(1, $view->getCount());
    }

    public function testSuccessCode()
    {
        $view = StoreView::create()
            ->setStatusCode(200);
        $this->assertTrue($view->getSuccess());

        $view = StoreView::create()
            ->setStatusCode(400);
        $this->assertFalse($view->getSuccess());
    }

    public function testMessage()
    {
        $view = StoreView::create()
            ->setMessage('Success');
        $this->assertEquals('Success', $view->getMessage());
    }
}
