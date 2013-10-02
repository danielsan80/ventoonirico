<?php

namespace Dan\CommonBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    
    protected function getFixturesToLoad()
    {
        return array(
             'Dan\UserBundle\DataFixtures\ORM\LoadUserData',
             'Dan\UserBundle\DataFixtures\ORM\LoadGroupData',
        );
    }
    
    public function setUp()
    {
        $this->loadFixtures($this->getFixturesToLoad());
    }
    
    /**
     * Show the current response in Chrome
     *
     * @param Client $client 
     */
    protected function showInBrowser($client)
    {
        $kernel = $client->getKernel();
        file_put_contents($kernel->getRootDir().'/cache/output.html', $client->getResponse()->getContent());
        exec('chromium-browser '.$kernel->getRootDir().'/cache/output.html');
    }
}