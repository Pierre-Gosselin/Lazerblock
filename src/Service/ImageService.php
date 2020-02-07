<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class ImageService
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    function saveToDisk(UploadedFile $image, $dir)
    {
        $path = $this->kernel->getProjectDir() . $dir;
        $imageName = uniqid() . '.' . $image->guessExtension();
        $image->move($path,$imageName);
        return $imageName;
    }
}