<?php
namespace Dan\UserBundle\Model;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;

use Dan\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\KernelInterface;

class UserManager extends BaseUserManager
{
    private $kernel;
    
    private $imagesDir = '/files/images/users';

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function getImagesDir()
    {
        return $this->kernel->getRootDir().$this->imagesDir;
    }
    
    public function setUserImage(User $user, $image) {
        $content = file_get_contents($image);
        $filename = 'user_'.md5($user->getEmail()).'.jpg';
        file_put_contents($this->getImagesDir().'/'.$filename, $content );
        $user->setImage($filename);
    }
    
}