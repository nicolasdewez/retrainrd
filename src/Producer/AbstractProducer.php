<?php

namespace App\Producer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractProducer
{
    /** @var ProducerInterface */
    protected $producer;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(ProducerInterface $producer, SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->producer = $producer;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    protected function logMessage(string $content, string $subject)
    {
        $this->logger->info(sprintf('[%s] Message sent: %s', $subject, $content));
    }

    protected function getSerializerContext(array $groups): SerializationContext
    {
        return SerializationContext::create()->setGroups($groups);
    }
}
