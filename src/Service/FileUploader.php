<?php
namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class FileUploader
{
    const UPLOAD_DIR = '/public/asset/img/upload';

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function upload($file)
    {
        $fileName = uniqid().'.'.$file->guessExtension();
        if(copy($file->getPathName(), $this->kernel->getProjectDir().self::UPLOAD_DIR.'/'.$fileName)) {
            return $fileName;
        } 
        return false;
    }

    public function remove(string $filename)
    {
        $fs = new Filesystem();
        $fs->remove($this->kernel->getProjectDir().self::UPLOAD_DIR.'/'.$filename);
    }

    // Check si l'image est inferieur a 2mb (Valeur d'upload par défaut du php.ini)
    public function isLessThan2Mb(int $imgSize) { 
        if ($imgSize >= 2097152) {
            return false;
        } else {
            return true;
        }
    } 
}