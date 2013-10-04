<?php
namespace Dan\UserBundle\Security;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use FOS\UserBundle\Model\UserManagerInterface;
 
class FOSUBUserProvider extends BaseClass implements UserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function connect($user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
 
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
 
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
 
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
 
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $email = $response->getEmail();
        
        
        $service = $response->getResourceOwner()->getName();
        $getServiceId = 'get'.ucfirst($service).'Id';
        $setServiceId = 'set'.ucfirst($service).'Id';
        $getServiceAccessToken = 'get'.ucfirst($service).'AccessToken';
        $setServiceAccessToken = 'set'.ucfirst($service).'AccessToken';
        
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        if (null === $user) {
            $user = $this->userManager->findUserBy(array('email' => $email));
        }
        
        //when the user is registrating
        if (null === $user) {
            
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setServiceId($username);
            $user->$setServiceAccessToken($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            if (!($email = $response->getEmail())) {
                $email = $response->getUsername();
            }

            $pos = strpos($email,'@');
            $pos = $pos!==false?$pos:null;
            $username = substr($email,0,$pos);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword('');
            $user->setEnabled(true);
            
            $picture = $response->getProfilePicture();
            if (isset($picture)) {
                $this->userManager->setUserImage($user, $picture);
            }
            
            $this->userManager->updateUser($user);
            return $user;
        }
        
        if (!$user->$getServiceId()) {
            $user->$setServiceId($username);            
            $user->$setServiceAccessToken($response->getAccessToken());
            if (!$user->getImage()) {
                $this->userManager->setUserImage($user, $response->getProfilePicture());
            }
            
            $this->userManager->updateUser($user);
            return $user;
        }
        
 
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
 
        $serviceName = $response->getResourceOwner()->getName();
        $setService = 'set' . ucfirst($serviceName) . 'AccessToken';
 
        //update access token
        $user->$setService($response->getAccessToken());
 
        return $user;
    }
 
}