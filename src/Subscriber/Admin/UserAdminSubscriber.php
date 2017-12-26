<?php

namespace App\Subscriber\Admin;

use App\Entity\User;
use App\Security\Role;
use App\Workflow\RegistrationDefinitionWorkflow;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdminSubscriber implements EventSubscriberInterface
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['initAdminFields'],
        ];
    }

    public function initAdminFields(GenericEvent $event): void
    {
        $entity = $event->getSubject();
        $entityName = $event->getArgument('request')->query->get('entity');

        if (!$this->supports($entity, $entityName)) {
            return;
        }

        /** @var User $entity */
        $entity->setPassword($this->encoder->encodePassword($entity, $entity->getPassword()));
        $entity->setRegistrationState(RegistrationDefinitionWorkflow::PLACE_ACTIVATED);
        $entity->setRegistrationCode('');
        $entity->setRoles([Role::ADMIN]);
        $entity->setEmailNotification(false);

        $event['entity'] = $entity;
    }

    /**
     * @param object $entity
     * @param string $entityName
     *
     * @return bool
     */
    private function supports($entity, string $entityName): bool
    {
        return $entity instanceof User && 'UserAdmin' === $entityName;
    }
}