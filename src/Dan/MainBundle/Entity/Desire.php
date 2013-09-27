<?php

namespace Dan\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
//use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Dan\UserBundle\Entity\User;
use Dan\MainBundle\Entity\Game;

/**
 * Desire
 *
 * @ORM\Table(name="dan_desire")
 * @ORM\Entity(repositoryClass="Dan\MainBundle\Entity\DesireRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Desire
{
    
    /**
     * Constructor 
     */
    public function __construct(User $owner)
    {
        $this->owner = $owner;
        //$this->joinedUsers = new ArrayCollection();
    }
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     * @Serializer\Type("integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dan\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @Serializer\Expose
     * @Serializer\Type("Dan\UserBundle\Entity\User")
     */
    private $owner;

    
    /**
     * @ORM\ManyToOne(targetEntity="Dan\MainBundle\Entity\Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * @Serializer\Expose
     * @Serializer\Type("Dan\MainBundle\Entity\Game")
     */
    private $game;

    /**
     * @var datetime $cratedAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createAt;

    /**
     * @var datetime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updateAt;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="reward", type="integer", nullable=true)
     */
    private $reward;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
        return $this;
    }
    
    /**
     * Get user
     *
     * @return Dan\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
    
    /**
     * Set game
     *
     * @param Game $game
     * @return Desire
     */
    public function setGame(Game $game)
    {
        $this->game = $game;
    
        return $this;
    }

    /**
     * Get game_id
     *
     * @return string 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return Desire
     */
    public function setCreatedAt($createAt)
    {
        $this->createAt = $createAt;
    
        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return Desire
     */
    public function setUpdatedAt($updateAt)
    {
        $this->updateAt = $updateAt;
    
        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updateAt;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Desire
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set reward
     *
     * @param integer $reward
     * @return Desire
     */
    public function setReward($reward)
    {
        $this->reward = $reward;
    
        return $this;
    }

    /**
     * Get reward
     *
     * @return integer 
     */
    public function getReward()
    {
        return $this->reward;
    }
    
}
