<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\OrdersItem;
use App\Entity\User;
use App\Entity\Orders;
use App\Entity\Billets;

class OrdersItemTest extends TestCase
{
    public function testInitialValues()
    {
        $user = new User();
        $order = new Orders();
        $ordersItem = new OrdersItem($user, $order);

        $this->assertNull($ordersItem->getId());
        $this->assertEquals($user, $ordersItem->getUser ());
        $this->assertEquals($order, $ordersItem->getOrder());
        $this->assertEquals(0, $ordersItem->getQuantite());
    }

    public function testSettersAndGetters()
    {
        $user = new User();
        $order = new Orders();
        $ordersItem = new OrdersItem($user, $order);

        $ordersItem->setQuantite(5);
        $this->assertEquals(5, $ordersItem->getQuantite());

        $billet = new Billets();
        $ordersItem->setBillet($billet);
        $this->assertEquals($billet, $ordersItem->getBillet());

        $ordersItem->setOrderKey('order_key_123');
        $this->assertEquals('order_key_123', $ordersItem->getOrderKey());
    }

    public function testGenerateUniqueTicketKey()
    {
        $user = new User();
        $order = new Orders();
        $ordersItem = new OrdersItem($user, $order);
        
        $ordersItem->generateUniqueTicketKey();
        $this->assertNotEmpty($ordersItem->getUniqueTicketKey());
        $this->assertMatchesRegularExpression('/^.+-order_.+-[a-f0-9]{16}$/', $ordersItem->getUniqueTicketKey());
    }

    public function testQrCodePath()
    {
        $user = new User();
        $order = new Orders();
        $ordersItem = new OrdersItem($user, $order);

        $this->assertNull($ordersItem->getQrCodePath());
        $ordersItem->setQrCodePath('path/to/qrcode.png');
        $this->assertEquals('path/to/qrcode.png', $ordersItem->getQrCodePath());
    }
}