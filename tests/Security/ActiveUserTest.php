<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\ActiveUser;
use App\Workflow\RegistrationWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ActiveUserTest extends TestCase
{
    public function testExecute()
    {
        $user = new User();
        $user->setEmail('email');
        $user->setNewPassword('newPassword');

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager
            ->expects($this->once())
            ->method('flush')
            ->withAnyParameters()
        ;

        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $encoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, 'newPassword')
            ->willReturn('newPasswordEncoded')
        ;

        $workflow = $this->createMock(RegistrationWorkflow::class);
        $workflow
            ->expects($this->once())
            ->method('applyActive')
            ->with($user)
        ;

        $activeUser = new ActiveUser($manager, $encoder, $workflow, new NullLogger());
        $activeUser->execute($user);

        $this->assertTrue($user->isEnabled());
        $this->assertSame('newPasswordEncoded', $user->getPassword());
    }
}
