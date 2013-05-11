<?php
namespace Dan\UserBundle\Service;

use Symfony\Component\Security\Core\SecurityContext;

class User {
    
    private $securityContext;
    
    public function __construct(SecurityContext $securityContext) {
        $this->securityContext = $securityContext;
    }
    
    public function get() {
        if ((!$this->securityContext)) {
            return null;
        }
        $token = $this->securityContext->getToken();
        if (!$token) {
            return null;
        }
        $user = $token->getUser();
        if ($user == 'anon.') {
            return null;
        }

        return $user;
    }
}
