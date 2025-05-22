<?php

namespace App\Tests\Form;

use App\Entity\Admin;
use App\Form\AdminType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class AdminTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        // Retourne l'extension du formulaire à tester
        return [
            new PreloadedExtension([new AdminType()], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'username' => 'adminuser',
            'firstName' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_ADMIN'],
        ];

        $model = new Admin();
        $form = $this->factory->create(AdminType::class, $model);

        $form->submit($formData);

        // Assure que le formulaire est valide
        $this->assertTrue($form->isSynchronized());

        // Vérifie que les données sont bien mappées
        $this->assertSame('adminuser', $model->getUsername());
        $this->assertSame('Alice', $model->getFirstName());
        $this->assertSame('alice@example.com', $model->getEmail());

        
        $this->assertSame('password123', $model->getPassword());

        $this->assertSame(['ROLE_ADMIN'], $model->getRoles());


        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $field) {
            $this->assertArrayHasKey($field, $children);
        }
    }
}
