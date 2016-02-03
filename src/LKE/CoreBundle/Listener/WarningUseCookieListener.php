<?php

namespace LKE\CoreBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class WarningUseCookieListener
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param FilterResponseEvent $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cookieConfirm(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if (!$request->cookies->has("acceptCookie"))
        {
            $content = $response->getContent();

            $headband = $this->twig->render("LKECoreBundle::cookie_confirm.html.twig");

            $content = preg_replace(
                "#<!-- cookie_warning -->#",
                $headband,
                $content,
                1
            );

            $response->setContent($content);
        }

        return $response;
    }
}
