<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Orders;
use App\Entity\User;
use App\Entity\OrdersItem;
use Doctrine\Common\Collections\ArrayCollection;

class OrdersTest extends TestCase
{
    public function testInitialValues()
    {
        $order = new Orders();
        $this->assertNull($order->getId());
        $this->assertInstanceOf(ArrayCollection::class, $order->getOrderItems());
        $this->assertEquals(0, $order->getOrderItems()->count());
        $this->assertFalse($order->isPaid());
        $this->assertFalse($order->isScanned());
    }

    public function testAddOrderItem()
    {
        $order = new Orders();
        $user = new User();
        $orderItem = new OrdersItem($user, $order);
        
        $order->addOrderItem($orderItem);
        $this->assertTrue($order->getOrderItems()->contains($orderItem));
    }

    public function testRemoveOrderItem()
    {
        $order = new Orders();
        $user = new User();
        $orderItem = new OrdersItem($user, $order);
        
        $order->addOrderItem($orderItem);
        $this->assertTrue($order->getOrderItems()->contains($orderItem));

        $order->removeOrderItem($orderItem);
        $this->assertFalse($order->getOrderItems()->contains($orderItem));
    }

    public function testGenerateOrderKey()
    {
        $order = new Orders();
        $order->generateOrderKey();
        $this->assertStringStartsWith('order_', $order->getOrderKey());
    }

    public function testSettersAndGetters()
    {
        $order = new Orders();
        $order->setTotalPrice(100.00);
        $this->assertEquals(100.00, $order->getTotalPrice());

        $order->setFirstName('John');
        $this->assertEquals('John', $order->getFirstName());

        $order->setLastName('Doe');
        $this->assertEquals('Doe', $order->getLastName());

        $order->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $order->getEmail());

        $order->setAddress('123 Main St');
        $this->assertEquals('123 Main St', $order->getAddress());

        $order->setPostalCode('12345');
        $this->assertEquals('12345', $order->getPostalCode());

        $order->setCity('Anytown');
        $this->assertEquals('Anytown', $order->getCity());

        $order->setCountry('Country');
        $this->assertEquals('Country', $order->getCountry());
    }
}