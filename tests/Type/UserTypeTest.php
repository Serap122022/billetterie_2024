<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testFormFieldsExist()
    {
        $form = $this->factory->create(UserType::class);

        $this->assertTrue($form->has('username'));
        $this->assertTrue($form->has('firstName'));
        $this->assertTrue($form->has('email'));
        $this->assertTrue($form->has('plainPassword'));
        $this->assertTrue($form->has('terms'));
    }

    public function testSubmitValidData()
    {
        $formData = [
            'username' => 'Utilisateur123',
            'firstName' => 'Jean',
            'email' => 'jean@example.com',
            'plainPassword' => [
                'first' => 'Password123',
                'second' => 'Password123',
            ],
            'terms' => true,
        ];

        $form = $this->factory->create(UserType::class);

        $user = new User();

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        // Les données sont mappées dans l'objet User
        $data = $form->getData();
        $this->assertInstanceOf(User::class, $data);
        $this->assertSame('Utilisateur123', $data->getUsername());
        $this->assertSame('Jean', $data->getFirstName());
        $this->assertSame('jean@example.com', $data->getEmail());
        $this->assertSame('Password123', $data->getPlainPassword());
        $this->assertTrue($data->isTermsAccepted() ?? $formData['terms']); // Selon implémentation User
    }

    public function testSubmitInvalidDataEmptyUsername()
    {
        $formData = [
            'username' => '',
            'firstName' => 'Jean',
            'email' => 'jean@example.com',
            'plainPassword' => [
                'first' => 'Password123',
                'second' => 'Password123',
            ],
            'terms' => true,
        ];

        $form = $this->factory->create(UserType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

        $errors = $form->get('username')->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertSame("Le nom d'utilisateur ne peut pas être vide.", $errors[0]->getMessage());
    }

    public function testSubmitInvalidDataPasswordMismatch()
    {
        $formData = [
            'username' => 'Utilisateur123',
            'firstName' => 'Jean',
            'email' => 'jean@example.com',
            'plainPassword' => [
                'first' => 'Password123',
                'second' => 'Different123',
            ],
            'terms' => true,
        ];

        $form = $this->factory->create(UserType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

        $errors = $form->get('plainPassword')->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertSame('Les mots de passe ne correspondent pas.', $errors[0]->getMessage());
    }

    public function testSubmitInvalidDataTermsNotAccepted()
    {
        $formData = [
            'username' => 'Utilisateur123',
            'firstName' => 'Jean',
            'email' => 'jean@example.com',
            'plainPassword' => [
                'first' => 'Password123',
                'second' => 'Password123',
            ],
            'terms' => false,
        ];

        $form = $this->factory->create(UserType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

        $errors = $form->get('terms')->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertSame('Vous devez accepter les termes et conditions.', $errors[0]->getMessage());
    }
}
