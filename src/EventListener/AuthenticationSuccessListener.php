<?php

namespace App\EventListener;

// src/App/EventListener/AuthenticationSuccessListener.php
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $resp = $event->getResponse();

//        if (!$user instanceof UserInterface) {
//            return;
//        }

        $data['data'] = array(
            'roles' => $user->getRoles(),
            'foo' => $user
        );

        $event->setData($data);
    }
}