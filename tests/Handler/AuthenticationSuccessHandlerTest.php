<?php

namespace App\Tests\Handler;

use App\Entity\User;
use App\Handler\AuthenticationSuccessHandler;
use App\Security\Role;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessHandlerTest extends TestCase
{
    public function testOnAuthenticationSuccessInvalidUser()
    {
        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('generate')
            ->with(AuthenticationSuccessHandler::ROUTE_LOGIN)
            ->willReturn('route')
        ;

        $handler = new AuthenticationSuccessHandler(
            $router,
            new NullLogger()
        );

        $user = $this->createMock(UserInterface::class);
        $user
            ->expects($this->once())
            ->method('getUsername')
            ->withAnyParameters()
            ->willReturn('')
        ;

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->withAnyParameters()
            ->willReturn($user)
        ;

        $expected = new RedirectResponse('route');

        $this->assertEquals($expected, $handler->onAuthenticationSuccess(new Request(), $token));
    }

    public function testOnAuthenticationSuccessAdmin()
    {
        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('generate')
            ->with(AuthenticationSuccessHandler::DEFAULT_ROUTE_ADMIN)
            ->willReturn('route')
        ;

        $handler = new AuthenticationSuccessHandler(
            $router,
            new NullLogger()
        );

        $user = new User();
        $user->setRoles([Role::ADMIN]);

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->withAnyParameters()
            ->willReturn($user)
        ;

        $expected = new RedirectResponse('route');

        $this->assertEquals($expected, $handler->onAuthenticationSuccess(new Request(), $token));
    }

    public function testOnAuthenticationSuccessFirstConnection()
    {
        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('generate')
            ->with(AuthenticationSuccessHandler::DEFAULT_ROUTE_USER)
            ->willReturn('route')
        ;

        $handler = new AuthenticationSuccessHandler(
            $router,
            new NullLogger()
        );

        $user = new User();
        $user->setRoles([Role::USER]);

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->withAnyParameters()
            ->willReturn($user)
        ;

        $expected = new RedirectResponse('route');

        $this->assertEquals($expected, $handler->onAuthenticationSuccess(new Request(), $token));
    }
}
