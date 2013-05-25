<?php
namespace Dan\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dan_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="google_id", type="string", length=40, nullable=true)
     */
    protected $googleId;
    
    /**
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    protected $image;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setGoogleId( $googleId )
    {
        $this->googleId = $googleId;
    }

    public function getGoogleId()
    {
        return $this->googleId;
    }
    
    public function setImage( $url )
    {
        $this->image = $url;
    }

    public function getImage()
    {
        return $this->image;
    }
    
    public function getAsJson()
    {
        return json_encode(array(
           'id' => $this->getId(),
           'image' => $this->getImage(),
           'username' => $this->getUsername(),
        ));
    }
}