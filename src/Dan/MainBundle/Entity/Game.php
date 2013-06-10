<?php

namespace Dan\MainBundle\Entity;

use Symfony\Component\Yaml\Yaml;

class Game
{

    private $id;
    private $name;
    private $owners = array();
    private $thumbnail;
    private $minPlayers;
    private $maxPlayers;

    public function __construct($item, $options = null)
    {
        if (isset($options)) {
            if (isset($options['user'])) {
                $attributes = $item->status[0]->attributes();
                if ( (bool)(int)$attributes['own']) {
                    $this->addOwner($options['user']);
                }
            }
            if (isset($options['owners'])) {
                $this->setOwners($options['owners']);
            }

            if (isset($options['owner'])) {
                $this->addOwner($options['owner']);
            }
        }
        
        $comment = trim((string)$item->comment);
        if ($comment) {
            try {
                $data = Yaml::parse($comment);
                if (is_array($data)) {
                    $owner = null;
                    if (isset($data['Owner'])) {
                        $owner = $data['Owner'];                    
                    }
                    if (isset($data['owner'])) {
                        $owner = $data['owner'];                    
                    }
                    if ($owner) {
                        $this->setOwners(array($owner));
                    }
                }
            } catch (\Exception $e) {}
        }
        $attributes = $item->attributes();
        $this->setId((int) $attributes['objectid']);

        $this->setName((string) $item->name);
        $this->setThumbnail((string) $item->thumbnail);
        $attributes = $item->stats[0]->attributes();
        $this->setMinPlayers((int) $attributes['minplayers']);
        $this->setMaxPlayers((int) $attributes['maxplayers']);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getOwner()
    {
        return $this->owners[0];
    }

    public function addOwner($owner)
    {
        $this->owners[] = $owner;
    }

    public function getOwners()
    {
        return $this->owners;
    }

    public function setOwners($owners)
    {
        $this->owners = $owners;
    }
    
    public function isOwned()
    {
        return (bool)count($this->owners);
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getMinPlayers()
    {
        return $this->minPlayers;
    }

    public function setMinPlayers($pax)
    {
        $this->minPlayers = $pax;
    }

    public function getMaxPlayers()
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers($pax)
    {
        $this->maxPlayers = $pax;
    }
    
    public function getAsArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'minPlayers' => $this->getMinPlayers(),
            'maxPlayers' => $this->getMaxPlayers(),
            'owners' => $this->getOwners(),
            'thumbnail' => $this->getThumbnail()
        );
    }

}