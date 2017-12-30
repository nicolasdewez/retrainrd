<?php

namespace App\Tests\Session;

use App\Session\FlashMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashMessageTest extends TestCase
{
    public function testAdd()
    {
        $flashBag = $this->createMock(FlashBagInterface::class);
        $flashBag
            ->expects($this->once())
            ->method('add')
            ->with('type', 'new message')
        ;

        $session = $this->createMock(Session::class);
        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->willReturn($flashBag)
        ;

        $flashMessage = new FlashMessage($session);
        $flashMessage->add('type', 'new message');
    }
}
