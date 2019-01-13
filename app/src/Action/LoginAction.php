<?php

namespace App\Action;

use App\Library\AuthLibrary;
use Psr\Log\LoggerInterface;
use SlimSession\Helper;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class LoginAction
{
    private $view;
    private $logger;
    private $auth;
    private $session;

    public function __construct(Twig $view, LoggerInterface $logger, AuthLibrary $auth, Helper $session)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->session = $session;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Login page action dispatched");
        $this->view->render($response, 'login.twig', ["option" => $args['option']]);
        return $response;
    }

    public function doLogin(Request $request, Response $response, $args)
    {
        $req = $request->getParsedBody();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if ($user = $this->auth->loginCheck($req['email'], $req['password'], $user_agent)) {
            $this->session->id = $user['id'];
            $this->session->email = $user['email'];
            $this->session->is_logged = true;

            return $response->withRedirect('/');
        }

        return $response->withRedirect('/auth/fail');
    }
}
