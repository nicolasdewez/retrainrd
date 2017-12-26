<?php

namespace App\Producer;

use App\Entity\User;
use App\Logger\Log;
use App\Serializer\Format;
use App\Serializer\Group;

class MailDisableAccountProducer extends AbstractProducer
{
    public function execute(User $user): void
    {
        $this->logger->info(sprintf('[%s] Publish message', Log::SUBJECT_DISABLE_ACCOUNT));

        $content = $this->serializer->serialize(
            $user,
            Format::JSON,
            $this->getSerializerContext([Group::EVENT_DISABLE_ACCOUNT])
        );

        $this->producer->publish($content);

        $this->logMessage($content, Log::SUBJECT_DISABLE_ACCOUNT);
    }
}
