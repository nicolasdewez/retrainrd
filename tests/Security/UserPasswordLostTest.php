<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Mailer\PasswordLostMailer;
use App\Security\GeneratePassword;
use App\Security\GenerateRegistrationCode;
use App\Security\UserPasswordLost;
use App\Workflow\RegistrationWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordLostTest extends TestCase
{
    public function testExecute()
    {
        $user = new User();
        $user->setEmail('email');

        $workflow = $this->createMock(RegistrationWorkflow::class);
        $workflow
            ->expects($this->once())
            ->method('canApplyPasswordLost')
            ->with($user)
            ->willReturn(true)
        ;
        $workflow
            ->expects($this->once())
            ->method('applyPasswordLost')
            ->with($user)
        ;

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager
            ->expects($this->once())
            ->method('flush')
            ->withAnyParameters()
        ;

        $generateRegistrationCode = $this->createMock(GenerateRegistrationCode::class);
        $generateRegistrationCode
            ->expects($this->once())
            ->method('execute')
            ->with('email')
            ->willReturn('code')
        ;

        $mailer = $this->createMock(PasswordLostMailer::class);
        $mailer
            ->expects($this->once())
            ->method('execute')
            ->with($user)
        ;

        $userPasswordLost = new UserPasswordLost(
            $workflow,
            $manager,
            $generateRegistrationCode,
            $mailer,
            new NullLogger()
        );

        $userPasswordLost->execute($user);

        $this->assertSame('code', $user->getRegistrationCode());
        $this->assertSame('', $user->getPassword());
        $this->assertFalse($user->isEnabled());
    }
}
