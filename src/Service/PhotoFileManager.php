<?php

namespace App\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class PhotoFileManager
{
    private $targetDirectory;

    private $fileSystem;

    public function __construct(string $targetDirectory, Filesystem $fileSystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->fileSystem = $fileSystem;
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new Exception('Unable to upload file: \n'.var_dump($file). 'Exception:: '.$e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function deleteFile(string $fileName): void
    {
        try {
            $this->fileSystem->remove([$this->getTargetDirectory()."/".$fileName]);
        } catch (IOExceptionInterface $exception) {
            throw new Exception("Error: File can not be deleted.");
        }
    }
}
