<?php

namespace App\Tests\Entity;

use App\Entity\Admin;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    public function testAdminProperties(): void
    {
        $admin = new Admin();

        $admin->setUsername('adminuser');
        $admin->setFirstName('Jean');
        $admin->setEmail('admin@example.com');
        $admin->setPlainPassword('Admin123!');
        $admin->setActive(true);
        $admin->setRoles(['ROLE_ADMIN']);

        $this->assertEquals('adminuser', $admin->getUsername());
        $this->assertEquals('Jean', $admin->getFirstName());
        $this->assertEquals('admin@example.com', $admin->getEmail());

        // ✅ Vérifie que le mot de passe est bien hashé et correct
        $hashedPassword = $admin->getPassword();

        $this->assertNotEmpty($hashedPassword, 'Le mot de passe ne doit pas être vide.');
        $this->assertMatchesRegularExpression('/^\$2y\$/', $hashedPassword, 'Le mot de passe doit être un hash bcrypt.');
        $this->assertTrue(password_verify('Admin123!', $hashedPassword), 'Le mot de passe fourni doit correspondre au hash.');

        $this->assertTrue($admin->isActive());
        $this->assertContains('ROLE_ADMIN', $admin->getRoles());

        $this->assertInstanceOf(\DateTimeInterface::class, $admin->getCreatedAt());
    }
}
