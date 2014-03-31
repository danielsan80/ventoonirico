<?php

namespace Dan\CommonBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    
    protected function getFixturesToLoad()
    {
        return array();
    }
    
    public function setUp()
    {
        
        parent::setUp();
        $executor = $this->loadFixtures($this->getFixturesToLoad());
        $this->referenceRepository = $executor->getReferenceRepository();
    }
    
    public function getReference($key)
    {
        return $this->referenceRepository->getReference($key);
    }
    
    protected function showInBrowser($client)
    {
        $kernel = $client->getKernel();
        file_put_contents($kernel->getRootDir().'/cache/output.html', $client->getResponse()->getContent());
        exec('chromium-browser '.$kernel->getRootDir().'/cache/output.html');
    }
}