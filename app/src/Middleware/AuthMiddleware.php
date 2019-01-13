<?php

namespace App\Middleware;

use App\Library\AuthLibrary;
use Psr\Log\LoggerInterface;
use SlimSession\Helper;

final class AuthMiddleware
{
    private $auth;
    private $logger;
    private $session;

    public function __construct(AuthLibrary $auth, LoggerInterface $logger, Helper $session)
    {
        $this->auth = $auth;
        $this->logger = $logger;
        $this->session = $session;
    }

    public function __invoke($request, $response, $next)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (isset($this->session->is_logged, $this->session->id, $this->session->email) && $this->session->is_logged == true) {
            if ($this->auth->sessionCheck($this->session->email, $this->session->id, $user_agent)) {
                return $next($request, $response);
            }
        }

        return $response->withRedirect('/auth/required');
    }
}
