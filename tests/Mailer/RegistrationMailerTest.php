<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mail;
use App\Mailer\RegistrationMailer;
use App\Translation\Locale;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment as Twig;

class RegistrationMailerTest extends TestCase
{
    public function testExecute()
    {
        $user = new User();
        $user->setEmail('email@example.com');

        $swiftMailer = $this->createMock(\Swift_Mailer::class);
        $swiftMailer
            ->expects($this->once())
            ->method('send')
        ;

        $twig = $this->createMock(Twig::class);
        $twig
            ->expects($this->once())
            ->method('render')
            ->with('mailing/registration.html.twig', ['user' => $user])
            ->willReturn('body')
        ;

        $mailer = new RegistrationMailer(
            $swiftMailer,
            $twig,
            new NullLogger()
        );

        $mailer->execute($user);
    }
}
