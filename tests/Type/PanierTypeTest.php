<?php

namespace App\Tests\Form;

use App\Entity\Panier;
use App\Form\PanierType;
use Symfony\Component\Form\Test\TypeTestCase;

class PanierTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = ['quantite' => 2];

        $model = new Panier();

        $form = $this->factory->create(PanierType::class, $model);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $this->assertSame(2, $model->getQuantite());
    }

    public function testSubmitInvalidData(): void
    {
        $formData = ['quantite' => 0]; // valeur invalide, min=1

        $model = new Panier();

        $form = $this->factory->create(PanierType::class, $model);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testFormViewContainsSubmitButton(): void
    {
        $form = $this->factory->create(PanierType::class);

        $view = $form->createView();
        $this->assertArrayHasKey('ajouter', $view->children);
        $this->assertEquals('Ajouter au panier', $view->children['ajouter']->vars['label']);
    }
}
