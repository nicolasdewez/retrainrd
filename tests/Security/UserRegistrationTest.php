<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Mailer\RegistrationMailer;
use App\Security\GeneratePassword;
use App\Security\GenerateRegistrationCode;
use App\Security\UserRegistration;
use App\Workflow\RegistrationWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegistrationTest extends TestCase
{
    public function testExecute()
    {
        $user = new User();
        $user->setEmail('email');

        $workflow = $this->createMock(RegistrationWorkflow::class);
        $workflow
            ->expects($this->once())
            ->method('canApplyRegistration')
            ->with($user)
            ->willReturn(true)
        ;
        $workflow
            ->expects($this->once())
            ->method('applyRegistration')
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

        $mailer = $this->createMock(RegistrationMailer::class);
        $mailer
            ->expects($this->once())
            ->method('execute')
            ->with($user)
        ;

        $userRegistration = new UserRegistration(
            $workflow,
            $manager,
            $generateRegistrationCode,
            $mailer,
            new NullLogger()
        );

        $userRegistration->execute($user);

        $this->assertSame('code', $user->getRegistrationCode());
    }

    public function testExecuteUserInvalid()
    {
        $user = new User();
        $user->setEmail('email');

        $workflow = $this->createMock(RegistrationWorkflow::class);
        $workflow
            ->expects($this->once())
            ->method('canApplyRegistration')
            ->with($user)
            ->willReturn(false)
        ;
        $workflow
            ->expects($this->never())
            ->method('applyRegistration')
        ;

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager
            ->expects($this->never())
            ->method('flush')
        ;

        $generateRegistrationCode = $this->createMock(GenerateRegistrationCode::class);
        $generateRegistrationCode
            ->expects($this->never())
            ->method('execute')
        ;

        $mailer = $this->createMock(RegistrationMailer::class);
        $mailer
            ->expects($this->never())
            ->method('execute')
        ;

        $userRegistration = new UserRegistration(
            $workflow,
            $manager,
            $generateRegistrationCode,
            $mailer,
            new NullLogger()
        );

        $userRegistration->execute($user);
    }
}
