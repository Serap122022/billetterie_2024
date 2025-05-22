<?php

namespace App\Tests\Form;

use App\Form\ResetPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class ResetPasswordTypeTest extends TypeTestCase
{
    public function testFormFieldsExist()
    {
        $form = $this->factory->create(ResetPasswordType::class);

        $this->assertTrue($form->has('password'));
        $this->assertTrue($form->has('confirm_password'));
    }

    public function testSubmitValidData()
    {
        $formData = [
            'password' => 'Abcdef1!',
            'confirm_password' => 'Abcdef1!',
        ];

        $form = $this->factory->create(ResetPasswordType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertSame('Abcdef1!', $form->get('password')->getData());
        $this->assertSame('Abcdef1!', $form->get('confirm_password')->getData());
    }

    public function testSubmitInvalidDataEmptyPassword()
    {
        $formData = [
            'password' => '',
            'confirm_password' => '',
        ];

        $form = $this->factory->create(ResetPasswordType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('password')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertSame('Le mot de passe est obligatoire.', $errors[0]->getMessage());
    }

    public function testSubmitInvalidDataShortPassword()
    {
        $formData = [
            'password' => 'Ab1!',
            'confirm_password' => 'Ab1!',
        ];

        $form = $this->factory->create(ResetPasswordType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('password')->getErrors();
        $foundLengthError = false;
        foreach ($errors as $error) {
            if (strpos($error->getMessage(), 'au moins 8 caractères') !== false) {
                $foundLengthError = true;
            }
        }
        $this->assertTrue($foundLengthError, 'Expected length validation error.');
    }

    public function testSubmitInvalidDataMissingPattern()
    {
        $formData = [
            'password' => 'abcdefgh', // pas de majuscule, pas de chiffre, pas de caractère spécial
            'confirm_password' => 'abcdefgh',
        ];

        $form = $this->factory->create(ResetPasswordType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('password')->getErrors();
        $foundRegexError = false;
        foreach ($errors as $error) {
            if (strpos($error->getMessage(), 'majuscule, un chiffre et un caractère spécial') !== false) {
                $foundRegexError = true;
            }
        }
        $this->assertTrue($foundRegexError, 'Expected regex validation error.');
    }

    public function testSubmitPasswordMismatch()
    {
        $formData = [
            'password' => 'Abcdef1!',
            'confirm_password' => 'Different1!',
        ];

        $form = $this->factory->create(ResetPasswordType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

        $errors = $form->get('confirm_password')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertSame('Les mots de passe ne correspondent pas.', $errors[0]->getMessage());
    }
}
