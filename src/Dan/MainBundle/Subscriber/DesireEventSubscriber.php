<?php
namespace Dan\MainBundle\Subscriber;

class DesireEventSubscriber implements \JMS\Serializer\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.pre_deserialize', 'method' => 'onPreDeserialize'),
        );
    }

    public function onPreDeserialize(\JMS\Serializer\EventDispatcher\PreDeserializeEvent $event)
    {
        var_dump($event->getData());
        
    }
}