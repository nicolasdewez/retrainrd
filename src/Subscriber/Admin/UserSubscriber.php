<?php

namespace App\Subscriber\Admin;

use App\Entity\User;
use App\Producer\MailDisableAccountProducer;
use App\Producer\MailEnableAccountProducer;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserSubscriber implements EventSubscriberInterface
{
    /** @var MailEnableAccountProducer */
    private $mailEnableAccountProducer;

    /** @var MailDisableAccountProducer */
    private $mailDisableAccountProducer;

    public function __construct(MailEnableAccountProducer $mailEnableAccountProducer, MailDisableAccountProducer $mailDisableAccountProducer)
    {
        $this->mailEnableAccountProducer = $mailEnableAccountProducer;
        $this->mailDisableAccountProducer = $mailDisableAccountProducer;
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_UPDATE => ['sendMail'],
        ];
    }

    public function sendMail(GenericEvent $event): void
    {
        $entity = $event->getSubject();
        $entityName = $event->getArgument('request')->query->get('entity');

        if (!$this->supports($entity, $entityName)) {
            return;
        }

        /** @var User $entity */
        if (!$entity->isEnabled()) {
            $this->mailDisableAccountProducer->execute($entity);

            return;
        }

        $this->mailEnableAccountProducer->execute($entity);
    }

    /**
     * @param object $entity
     * @param string $entityName
     *
     * @return bool
     */
    private function supports($entity, string $entityName): bool
    {
        return $entity instanceof User && 'User' === $entityName;
    }
}