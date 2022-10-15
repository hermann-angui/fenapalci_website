<?php

namespace App\Helper;

use App\Entity\User;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\RouterInterface;

class UserHelper
{
    /**
     * @var FileUploadHelper
     */
    protected FileUploadHelper $fileUploadHelper;

    /**
     * @var string
     */
    protected string $uploadDirectory;

    /**
     * @var Packages
     */
    protected Packages $assetsManager;


    public function __construct(string           $uploadDirectory,
                                FileUploadHelper $fileUploadHelper
    )
    {
        $this->uploadDirectory = $uploadDirectory;
        $this->fileUploadHelper = $fileUploadHelper;
    }

    public function getUserUploadDirectory(?User $user): ?string
    {
        try {
            $path = $this->uploadDirectory . '/public/users/' . $user->getId() . '/';
            if (!file_exists($path)) mkdir($path, 0777, true);
            return $path;
        } catch (\Exception $e) {
            return null;
        }

    }

    public function getPublicDirectory(): ?string
    {
        return $this->uploadDirectory . "/plublic/";
    }
}