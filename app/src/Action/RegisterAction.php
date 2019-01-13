<?php

namespace App\Action;

use App\Library\AuthLibrary;
use App\Library\UserLibrary;
use Psr\Log\LoggerInterface;
use SlimSession\Helper;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class RegisterAction
{
    private $view;
    private $logger;
    private $session;
    private $auth;
    private $user;

    public function __construct(Twig $view, LoggerInterface $logger, Helper $session, AuthLibrary $auth, UserLibrary $user)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->session = $session;
        $this->auth = $auth;
        $this->user = $user;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        $this->view->render($response, 'register.twig');

        return $response;
    }

    public function register(Request $request, Response $response, $args)
    {
        $req = $request->getParsedBody();
        $email = $req['new-email'];
        $pass = $req['new-password'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if ($this->user->create($email, $pass, $user_agent) && $user = $this->auth->loginCheck($email, $pass, $user_agent)) {
            $this->session->id = $user['id'];
            $this->session->email = $user['email'];
            $this->session->is_logged = true;

            return $response->withRedirect('/');
        }

        return $response->withRedirect('/register/fail');

    }
}
