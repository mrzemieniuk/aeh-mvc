<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class GeneralListener
{
    public function __construct(
        private readonly Security $security
    ) {}

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if(method_exists($entity, 'getCreatedBy') && !$entity->getCreatedBy())
            $entity->setCreatedBy($this->security->getUser());

        if(method_exists($entity, 'getCreatedAt') && !$entity->getCreatedAt())
            $entity->setCreatedAt(new \DateTimeImmutable());
    }
}
