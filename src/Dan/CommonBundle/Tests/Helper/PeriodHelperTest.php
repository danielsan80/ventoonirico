<?php

namespace Dan\NoleaksBundle\Tests\Helper;

use Dan\CommonBundle\Test\WebTestCase;
use Dan\CommonBundle\Helper\PeriodHelper;

class PeriodHelperTest extends WebTestCase
{
    public function getFixturesToLoad()
    {
        return array();
    }
    
    public function test_get_start_and_end_date()
    {
        $helper = new PeriodHelper();
        $this->assertEquals($helper->getStartDate('2012')->format('Y-m-d H:i:s'), '2012-01-01 00:00:00');
        $this->assertEquals($helper->getEndDate('2012')->format('Y-m-d H:i:s'), '2013-01-01 00:00:00');

        $this->assertEquals($helper->getStartDate('2012','01')->format('Y-m-d H:i:s'), '2012-01-01 00:00:00');
        $this->assertEquals($helper->getEndDate('2012','01')->format('Y-m-d H:i:s'), '2012-02-01 00:00:00');
        
        $this->assertEquals($helper->getStartDate('2012-01')->format('Y-m-d H:i:s'), '2012-01-01 00:00:00');
        $this->assertEquals($helper->getEndDate('2012-01')->format('Y-m-d H:i:s'), '2012-02-01 00:00:00');
    }
        
}