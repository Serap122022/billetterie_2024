<?php

namespace App\Tests\Form;

use App\Entity\Billets;
use App\Form\BilletsType;
use Symfony\Component\Form\Test\TypeTestCase;

class BilletsTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'type' => 'solo (1 personne)',
            'tarif' => 25,
            'customType' => '',
            'stock' => 100,
        ];

        $model = new Billets();
        $form = $this->factory->create(BilletsType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertSame('solo (1 personne)', $model->getType());
        $this->assertSame(25, $model->getTarif());
        $this->assertSame('', $model->getCustomType());
        $this->assertSame(100, $model->getStock());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $field) {
            $this->assertArrayHasKey($field, $children);
        }
    }

    public function testInvalidTarif(): void
    {
        $formData = [
            'type' => 'duo (2 personnes)',
            'tarif' => 'abc',
            'customType' => '',
            'stock' => 50,
        ];

        $form = $this->factory->create(BilletsType::class);
        $form->submit($formData);

        
        $this->assertFalse($form->isValid());
    }

    public function testInvalidStock(): void
    {
        $formData = [
            'type' => 'family (4 personnes)',
            'tarif' => 50,
            'customType' => '',
            'stock' => 'invalid', 
        ];

        $form = $this->factory->create(BilletsType::class);
        $form->submit($formData);

        
        $this->assertFalse($form->isValid());
    }

    public function testCustomTypeValidation(): void
    {
        $formData = [
            'type' => 'other',
            'tarif' => 35,
            'customType' => 'VIP@#$', 
            'stock' => 10,
        ];

        $form = $this->factory->create(BilletsType::class);
        $form->submit($formData);

       
        $this->assertFalse($form->isValid());
    }
}
