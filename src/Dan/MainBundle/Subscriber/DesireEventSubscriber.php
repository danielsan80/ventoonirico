<?php

namespace Dan\MainBundle\Subscriber;

class DesireEventSubscriber implements \JMS\Serializer\EventDispatcher\EventSubscriberInterface
{

    private $em;

    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.pre_deserialize', 'method' => 'onPreDeserialize'),
            array('event' => 'serializer.post_deserialize', 'method' => 'onPostDeserialize'),
        );
    }

    public function onPreDeserialize(\JMS\Serializer\EventDispatcher\PreDeserializeEvent $event)
    {
        $type = $event->getType();
        if ($type['name']=='Dan\MainBundle\Entity\Desire') {
            $data = $event->getData();
            if (isset($data['game']) && is_int($data['game'])) {
                $data['game_id'] = $data['game'];
                unset($data['game']);
            }
            if (isset($data['owner']) && !is_array($data['owner'])) {
                $data['owner'] = array('id' => $data['owner']);
            }
            $event->setData($data);
        }
    }
    
    public function onPostDeserialize(\JMS\Serializer\EventDispatcher\ObjectEvent $event)
    {
        $type = $event->getType();
        if ($type['name']=='Dan\MainBundle\Entity\Desire') {
            $desire = $event->getObject();
            $user = $desire->getOwner();
            $user = $this->em->merge($user);
            $this->em->refresh($user);
            $desire->setOwner($user);
        }
    }

    public function setEntityManager($em)
    {
        $this->em = $em;
    }

}