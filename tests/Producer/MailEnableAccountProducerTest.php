<?php

namespace App\Tests\Producer;

use App\Entity\User;
use App\Producer\MailEnableAccountProducer;
use App\Serializer\Format;
use App\Serializer\Group;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class MailEnableAccountProducerTest extends TestCase
{
    public function testExecute()
    {
        $user = new User();

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($user, Format::JSON, SerializationContext::create()->setGroups([Group::EVENT_ENABLE_ACCOUNT]))
            ->willReturn('content')
        ;

        $producer = $this->createMock(ProducerInterface::class);
        $producer
            ->expects($this->once())
            ->method('publish')
            ->with('content')
            ->willReturn(true)
        ;

        $registrationProducer = new MailEnableAccountProducer(
            $producer,
            $serializer,
            new NullLogger()
        );

        $registrationProducer->execute($user);
    }
}
