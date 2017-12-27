<?php

namespace App\Tests\Security;

use App\Security\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testGetRoles()
    {
        $this->assertSame([Role::USER, Role::ADMIN], Role::getRoles());
    }

    public function testGetTitleByRole()
    {
        $this->assertSame(null, Role::getTitleByRole(''));
        $this->assertSame(Role::TITLE_USER, Role::getTitleByRole(Role::USER));
        $this->assertSame(Role::TITLE_ADMIN, Role::getTitleByRole(Role::ADMIN));
    }
}
