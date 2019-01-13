<?php

namespace App\Action;

use App\Library\CarLibrary;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class HomeAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger, CarLibrary $car)
    {
        $this->view = $view;
        $this->car = $car;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $typeToSearch = $request->getAttribute('route')->getArgument('type');
        $textToSearch = $request->getAttribute('route')->getArgument('search');

        $options = array('fetchCars' => $this->fetchCars());

        if ($typeToSearch != '' && $textToSearch != '') {
            $options = array('fetchCars' => $this->filterCarsBy($typeToSearch, $textToSearch));
        }

        $this->view->render($response, 'home.twig', $options);
        return $response;
    }

    public function fetchCars()
    {
        return $this->car->list();
    }

    public function filterCarsBy($type, $search)
    {
        return $this->car->filterBy($type, $search);
    }

    public function fetchCarById($id)
    {
        return $this->car->fetchById($id);
    }
}
