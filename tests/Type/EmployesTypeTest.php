<?php

namespace App\Tests\Form;

use App\Entity\Employes;
use App\Form\EmployesType;
use Symfony\Component\Form\Test\TypeTestCase;

class EmployesTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_EMPLOYE'],
            'isActive' => true,
        ];

        $employe = new Employes();
        $form = $this->factory->create(EmployesType::class, $employe);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
        $this->assertEquals('Dupont', $employe->getNom());
        $this->assertEquals('Jean', $employe->getPrenom());
        $this->assertEquals('jean.dupont@example.com', $employe->getEmail());
        $this->assertEquals('password123', $employe->getPassword());
    }
}