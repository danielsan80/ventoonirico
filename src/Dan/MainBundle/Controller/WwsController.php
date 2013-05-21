<?php

namespace Dan\MainBundle\Controller;

use Dan\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Yaml\Yaml;

/**
 * @Route("/wws") 
 */
class WwsController extends Controller
{
    
    /**
     * Home page
     * 
     * @Route("", name="wws")
     * @return html
     */
    public function wwsAction()
    {
        
        $n = 20;
        $r = 50;
        $max = 100;
        
        $stars = array();
        for ($i=0; $i<$n; $i++) {
            $stars[str_pad($i, 3, '0', STR_PAD_LEFT)] = array(
                'coord' => array(
                    'r'  => $this->rand(),
                    'azi' => $this->rand()*2*pi(),
                    'alt' => ($this->rand()*pi()),
                 ),
            );
        }
        
        foreach($stars as $i => $star1) {
            $distances = array();
            foreach ($stars as $j => $star2) {
                if ($i != $j) {
                    $distance = round($this->distance($star1, $star2)*$r);
                    if ($distance <= $max) {
                        $distances[$j] = $distance;
                    }
                }
            }
            asort($distances);
            $stars[$i]['distances'] = $distances;
        }
        
        foreach($stars as $i => $star) {
            $stars[$i]['coord']['r'] = round($stars[$i]['coord']['r'],2);
            $stars[$i]['coord']['alt'] = round($stars[$i]['coord']['alt'],2);
            $stars[$i]['coord']['azi'] = round($stars[$i]['coord']['azi'],2);
        }
        
        file_put_contents($this->get('kernel')->getCacheDir().'/stars.yml',Yaml::dump($stars,9));
        
    }
    
    private function rand() {
        $precision = 1000;
        return rand(0,$precision)/$precision;
    }
    
    private function distance($star1, $star2) {
        $r1 = $star1['coord']['r'];
        $teta1 = $star1['coord']['alt'];
        $fi1 = $star1['coord']['azi'];
        
        $r2 = $star2['coord']['r'];
        $teta2 = $star2['coord']['alt'];
        $fi2 = $star2['coord']['azi'];
        
        $x = pow($r1 * sin($teta1) * cos($fi1) - $r2 * sin($teta2) * cos($fi2), 2);
        $y = pow($r1 * sin($teta1) * sin($fi1) - $r2 * sin($teta2) * sin($fi2), 2);
        $z = pow($r1 * cos($teta1) - $r2 * cos($teta2), 2);
        
        return sqrt($x+$y+$z);
        
    }
}
