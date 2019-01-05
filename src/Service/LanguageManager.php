<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 22/12/2018
 * Time: 02:32
 */

namespace App\Service;



use Symfony\Component\HttpFoundation\RequestStack;

class LanguageManager
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getLanguageUsingCookie(){
        $request = $this->requestStack;
        $languageCookie = $request->getCurrentRequest()->cookies->get('lg');
        if ($languageCookie === null || $languageCookie == 'fr'){
            return 'fr';
        }
        return 'en';
    }

}

