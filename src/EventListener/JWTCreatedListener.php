<?php
namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

// Toegevoegd om de userid in de header van de JWT te krijgen.
// https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/2-data-customization.rst
class JWTCreatedListener {
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();

        $event->setData($payload);

        //https://www.rfc-editor.org/rfc/rfc7519#section-4.1
        $header = $event->getHeader();
        $header['sub'] =  $event->getUser()->id;
        $header['iss'] = 'memory backend';

        $event->setHeader($header);
    }
}



