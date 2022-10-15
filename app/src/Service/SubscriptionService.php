<?php

namespace App\Service;


use App\DTO\UserDto;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SubscriptionService
{
    protected MailerInterface $mailer;

    function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function SubscribeNewMember(UserDto $member)
    {
        return true;
    }

    public function RemoveMember(UserDto $member)
    {
        return true;
    }

    public function SubscribeNewStaff(UserDto $member)
    {
        return true;
    }

    public function RemoveStaff(UserDto $member)
    {
        return true;
    }

}