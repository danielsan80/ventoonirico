<?php

namespace Dan\MainBundle\Tests\Controller\Mink;
use Dan\MainBundle\Test\MinkTestCase;

/**
 * @group mink
 */
class ApiControllerTest extends MinkTestCase
{
    
    public function testRemoveOwnerDesireByOwner()
    {
        $session = $this->getMinkSession();
        $this->login($session, 'mario', 'mario');
        $this->visit($session, $this->getUrl('home'));
        
        $desiresBox = $this->waitFor($session, '#desired-game-list');
        
        $this->waitFor($session, '.player-main');
        
        $desires = $this->findAll($session, 'div.game', $desiresBox);
        $this->assertCount(1, $desires);

        $noOwnedDesireLinks = $this->findAll($session, '.player-main a.desire-take', $desires[0]);
        $this->assertCount(0, $noOwnedDesireLinks);
 
        $this->find($session, '.player-main a.desire-leave', $desires[0])->click();
        
        $this->waitFor($session, '.desire-take');
        $noOwnedDesireLinks = $this->findAll($session, '.player-main a.desire-take', $desires[0]);
        $this->assertCount(1, $noOwnedDesireLinks);
        
        
        $this->visit($session, $this->getUrl('home'));
        
        $desiresBox = $this->waitFor($session, '#desired-game-list');
        
        $this->waitFor($session, '.player-main');
        
        $desires = $this->findAll($session, 'div.game', $desiresBox);
        $this->assertCount(1, $desires);

        
        $noOwnedDesireLinks = $this->findAll($session, '.player-main a.desire-take', $desires[0]);
        $this->assertCount(1, $noOwnedDesireLinks);
 
        $this->find($session, '.player-main a.desire-take', $desires[0])->click();
        
        $this->waitFor($session, '.desire-leave');
        $ownedDesireLinks = $this->findAll($session, '.player-main a.desire-leave', $desires[0]);
        $this->assertCount(1, $ownedDesireLinks);
        
    }
        
}
