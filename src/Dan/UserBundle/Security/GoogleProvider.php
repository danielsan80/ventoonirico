<?php

namespace Dan\UserBundle\Security;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GoogleProvider implements UserProviderInterface
{

    /**
     * @var \GoogleApi
     */
    protected $googleApi;
    protected $userManager;
    protected $validator;
    protected $em;
    protected $imagesDir;

    public function __construct($googleApi, $userManager, $validator, $em, $kernel)
    {
        $this->googleApi = $googleApi;
        $this->userManager = $userManager;
        $this->validator = $validator;
        $this->em = $em;
        $this->imagesDir = $kernel->getRootDir().'/files/images';
    }

    public function supportsClass($class)
    {
        return $this->userManager->supportsClass($class);
    }

    public function findUserByGIdOrEmail($gId, $email = null)
    {
        $user = $this->userManager->findUserByUsernameOrEmail($email);
        if (!$user)
            $user = $this->userManager->findUserBy(array('googleId' => $gId));
        return $user;
    }

    public function loadUserByUsername($username)
    {
        try {
            $gData = $this->googleApi->getOAuth()->userinfo->get();
        } catch (\Exception $e) {
            $gData = null;
        }

        if (!empty($gData)) {

            $email = $gData->getEmail();
            $user = $this->findUserByGIdOrEmail($username, isset($email) ? $email : null );

            if (empty($user)) {
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->setPassword('');
//                $user->setSalt('');
            }

            $picture = $gData->getPicture();

            if (isset($picture)) {
                $pi = pathinfo($picture);
                file_put_contents($this->imagesDir.'/user/photo'.md5($email).'.'.$pi['extension'], file_get_contents($picture));
                $user->setImage($pi['basename']);
            }
            
            $id = $gData->getId();

            if (isset($id)) {
                $user->setGoogleId($id);
            }

            $name = $gData->getName();

//            if (isset($name)) {
//                $nameAndLastNames = explode(" ", $name);
//                if (count($nameAndLastNames) > 1) {
//                    $user->setFirstname($nameAndLastNames[0]);
//                    $user->setLastname($nameAndLastNames[1]);
//                    $user->setLastname2(( count($nameAndLastNames) > 2 ) ? $nameAndLastNames[2] : "" );
//                } else {
//                    $user->setFirstname($nameAndLastNames[0]);
//                    $user->setLastname("");
//                    $user->setLastname2("");
//                }
//            }

            if (isset($email)) {
                $user->setEmail($email);
                $user->setUsername($email);
            } else {
                $user->setEmail($id . "@google.com");
                $user->setUsername($id . "@google.com");
            }

            if (count($this->validator->validate($user, 'Google'))) {
                // TODO: the user was found obviously, but doesnt match our expectations, do something smart
                throw new UsernameNotFoundException('The google user could not be stored');
            }
            $this->userManager->updateUser($user);
        }

        if (empty($user)) {
            throw new UsernameNotFoundException('The user is not authenticated on google');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user) {
        if (!$this->supportsClass(get_class($user)) || !$user->getGoogleId()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getGoogleId());
    }

}