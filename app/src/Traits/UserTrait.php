<?php
namespace App\Traits;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

trait UserTrait
{
    function redirectIfNotAllow() : ?RedirectResponse
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser()->getStatus() === "WAITING_FOR_PAYMENT") {
            return $this->redirectToRoute('app_home');
        }
        return null;
    }
}
