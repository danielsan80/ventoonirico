<?php

namespace Dan\MainBundle\Test;
use Dan\CommonBundle\Test\MinkTestCase as BaseMinkTestCase;

class MinkTestCase extends BaseMinkTestCase
{
    protected function getFixturesToLoad()
    {
        return array(
            'Dan\UserBundle\DataFixtures\ORM\LoadUserData',
            'Dan\UserBundle\DataFixtures\ORM\LoadGroupData',
            'Dan\MainBundle\DataFixtures\ORM\LoadGameData',
            'Dan\MainBundle\DataFixtures\ORM\LoadDesireData',
            'Dan\MainBundle\DataFixtures\ORM\LoadJoinData',
        );
    }
    
    public function login($session, $username, $password)
    {
        $url = $this->getUrl('fos_user_security_login');
        $this->visit($session, $url);
        
        $this->find($session, 'input#username')->setValue($username);
        $this->find($session, 'input#password')->setValue($password);
        $this->find($session, '#_submit')->click();
    }
}

