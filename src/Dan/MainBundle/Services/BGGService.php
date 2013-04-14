<?php
namespace Dan\MainBundle\Services;

use Orkestra\Bundle\GuzzleBundle\Services\Service as AbstractService;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Command;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Doc;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Param;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Headers;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Type;

class BGGService extends AbstractService
{
    /**
     * @Command(name="collection", method="GET", uri="collection/{username}")
     * @Doc("Get the collection of a user")
     * Param(name="username", type="string", required="true")
     * @Param(name="username", type="string", required="true", location="uri")
     * Param(name="command.response_processing", type="string", required="false", default="raw")
     */
    public function bggCollectionCommand()
    {
        return $this->getResponse();
    }
    
}