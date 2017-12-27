<?php

namespace App\Security;

use App\Logger\Log;
use Psr\Log\LoggerInterface;

class GenerateRegistrationCode
{
    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $secret;

    public function __construct(LoggerInterface $logger, string $secret)
    {
        $this->logger = $logger;
        $this->secret = $secret;
    }

    public function execute(string $email): string
    {
        $this->logger->info(sprintf(
            '[%s] Generate registration code for email: %s',
            Log::SUBJECT_REGISTRATION,
            $email
        ));

        $validity = (new \DateTime())->add(new \DateInterval(sprintf('PT%dH', RegistrationCode::VALIDITY)));
        $md5 = md5(sprintf('%s.%s', $email, $this->secret));

        $registrationCode = sprintf('%s-%d-%s', $email, $validity->getTimestamp(), $md5);

        return base64_encode($registrationCode);
    }
}
