<?php

namespace App\Workflow;

use App\Entity\User;
use App\Workflow\RegistrationDefinitionWorkflow as Definition;
use Symfony\Component\Workflow\StateMachine;

class RegistrationWorkflow
{
    /** @var StateMachine */
    private $stateMachine;

    public function __construct(StateMachine $stateMachineRegistration)
    {
        $this->stateMachine = $stateMachineRegistration;
    }

    public function canApplyRegistration(User $user): bool
    {
        return $this->stateMachine->can($user, Definition::TRANSITION_REGISTRATION);
    }

    public function canApplyActive(User $user): bool
    {
        return $this->stateMachine->can($user, Definition::TRANSITION_ACTIVE);
    }

    public function canApplyPasswordLost(User $user): bool
    {
        return $this->stateMachine->can($user, Definition::TRANSITION_PASSWORD_LOST);
    }

    public function applyRegistration(User $user)
    {
        $this->stateMachine->apply($user, Definition::TRANSITION_REGISTRATION);
    }

    public function applyActive(User $user)
    {
        $this->stateMachine->apply($user, Definition::TRANSITION_ACTIVE);
    }

    public function applyPasswordLost(User $user)
    {
        $this->stateMachine->apply($user, Definition::TRANSITION_PASSWORD_LOST);
    }
}
