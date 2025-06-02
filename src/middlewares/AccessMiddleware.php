<?php

namespace Surfsail\middlewares;

use Explt13\Nosmi\Interfaces\LightResponseInterface;
use Explt13\Nosmi\Interfaces\LightServerRequestInterface;
use Explt13\Nosmi\Middleware\Middleware;
use Surfsail\access\Roles;

class AccessMiddleware extends Middleware
{
    /**
     * @var Roles[] $roles
     */
    private array $roles;
 
    /**
     * @param Roles[] $roles allowed roles for accessing a route
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    protected function processRequest(LightServerRequestInterface $request): ?LightServerRequestInterface
    {
        $role = $_SESSION['user']['role'] ?? 'anonymous';
        foreach ($this->roles as $r) {
            if ($role === $r->name) {
                return $request;
            } 
        }
        if (in_array(Roles::user, $this->roles)) {
            $response = $this->createEarlyResponse(303)->withRedirect('/auth?r_link=/cart');
            $this->earlyResponse($response);
            return null;
        }
        $this->createEarlyResponse(403);
        return null;
    }

    protected function processResponse(LightResponseInterface $response, LightServerRequestInterface $request): LightResponseInterface
    {
        return $response;
    }
}