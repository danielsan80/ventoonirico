<?php
namespace Dan\CommonBundle\Helper;

class PeriodHelper
{
    public function getStartDate($year, $month=null)
    {
        if ($month) {
            return new \DateTime($year.'-'.$month.'-01');
        }
        if (preg_match('/(?P<year>\d{4})-(?P<month>\d{2})/', $year, $matches)){
            $year = $matches['year'];
            $month = $matches['month'];
            return new \DateTime($year.'-'.$month.'-01');
        }

        return new \DateTime($year.'-01-01');
    }
    
    public function getEndDate($year, $month=null)
    {
        if ($month) {
            return new \DateTime($year.'-'.$month.'-01 +1 month');
        }
        if (preg_match('/(?P<year>\d{4})-(?P<month>\d{2})/', $year, $matches)){
            $year = $matches['year'];
            $month = $matches['month'];
            return new \DateTime($year.'-'.$month.'-01 +1 month');
        }
        return new \DateTime($year.'-01-01 +1 year');
    }
}