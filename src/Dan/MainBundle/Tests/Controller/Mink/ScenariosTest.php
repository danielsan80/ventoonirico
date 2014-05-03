<?php

namespace Dan\MainBundle\Tests\Controller\Mink;
use Dan\MainBundle\Test\MinkTestCase;

/**
 * @group mink
 */
class ScenariosTest extends MinkTestCase
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
        $desire = $desires[0];

        $noOwnedDesireLinks = $this->findAll($session, '.player-main a.desire-take', $desire);
        $this->assertCount(0, $noOwnedDesireLinks);
 
        $this->find($session, '.player-main a.desire-leave', $desire)->click();
        
        $this->waitFor($session, '.desire-take');
        $noOwnedDesireLinks = $this->findAll($session, '.player-main a.desire-take', $desire);
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
        $this->close($session);
        
    }
    
    public function testRemoveOwnerDesireByAdmin()
    {
        $session = $this->getMinkSession();
        $this->login($session, 'admin', 'admin');
        $this->visit($session, $this->getUrl('home'));
        
        $desiresBox = $this->waitFor($session, '#desired-game-list');
        
        $this->waitFor($session, '.player-main');
        
        $desires = $this->findAll($session, 'div.game', $desiresBox);
        $this->assertCount(1, $desires);
        $desire = $desires[0];

        $noOwnedDesireLinks = $this->findAll($session, '.player-main a.desire-take', $desire);
        $this->assertCount(0, $noOwnedDesireLinks);
 
        $this->find($session, '.player-main a.desire-leave', $desire)->click();
        
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
        $this->close($session);
        
    }
    
    public function testRemoveJoinedUserByAdmin()
    {
        $session = $this->getMinkSession();
        $this->login($session, 'admin', 'admin');
        $this->visit($session, $this->getUrl('home'));
        
        $desiresBox = $this->waitFor($session, '#desired-game-list');
        
        $this->waitFor($session, '.player-joined');
        
        $desires = $this->findAll($session, 'div.game', $desiresBox);
        $this->assertCount(1, $desires);
        $desire = $desires[0];

        $this->waitFor($session, '.join-add');
        $addJoinLinks = $this->findAll($session, '.player-nobody a.join-add', $desire);
        $this->assertCount(3, $addJoinLinks);
 
        $this->find($session, '.player-joined a.join-remove', $desire)->click();
        
        $session->wait(10000, "$('#desired-game-list div.game::first-child .player-nobody a.join-add').length != 3");
        $addJoinLinks = $this->findAll($session, '.player-nobody a.join-add', $desire);
        $this->assertCount(4, $addJoinLinks);
        
        $this->visit($session, $this->getUrl('home'));
        
        $desiresBox = $this->waitFor($session, '#desired-game-list');
        
        $this->waitFor($session, '.player-nobody');
        
        $desires = $this->findAll($session, 'div.game', $desiresBox);
        $this->assertCount(1, $desires);
        $desire = $desires[0];

        $addJoinLinks = $this->findAll($session, '.player-nobody a.join-add', $desire);
        $this->assertCount(4, $addJoinLinks);
        $this->close($session);
        
    }
        
}
