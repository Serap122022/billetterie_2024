<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Item;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAddAndRemoveItemsFromPanier()
    {
        // Créer un utilisateur
        $user = new User();

        // Créer des items à ajouter au panier
        $item1 = new Item();
        $item1->setNom("Billet A")->setPrix(20.00);

        $item2 = new Item();
        $item2->setNom("Billet B")->setPrix(30.00);

        // Ajouter des items au panier de l'utilisateur
        $user->addItemToPanier($item1);
        $user->addItemToPanier($item2);

        // Vérifier que le panier contient 2 items
        $this->assertCount(2, $user->getPanier()->getItems());

        // Retirer un item du panier
        $user->removeItemFromPanier($item1);

        // Vérifier que le panier contient 1 item
        $this->assertCount(1, $user->getPanier()->getItems());

        // Vérifier que l'item retiré n'est plus dans le panier
        $this->assertNotContains($item1, $user->getPanier()->getItems());
    }
}
