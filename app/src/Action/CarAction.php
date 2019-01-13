<?php

namespace App\Action;

use App\Library\CarLibrary;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class CarAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger, CarLibrary $car)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->car = $car;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Car page action dispatched");

        $carId = $request->getAttribute('route')->getArgument('id');

        if ($carId) {
            $car = $this->fetchCarById($carId);
            $this->view->render($response, 'car/car-edit.twig', array(
                'getCar' => $car,
            ));
        } else {
            $this->view->render($response, 'car/car-new.twig');
        }

        return $response;
    }

    public function createCar(Request $request, Response $response)
    {
        $req = $request->getParsedBody();
        if ($this->car->insert($req['brand'], $req['model'], $req['color'], $req['year'])) {
            return $response->withRedirect('/');
        }

        return $response->withRedirect('/fail-to-create-new-car');

    }

    public function editCar(Request $request, Response $response)
    {
        $req = $request->getParsedBody();
        if ($this->car->update($req['id'], $req['brand'], $req['model'], $req['color'], $req['year'])) {
            return $response->withRedirect('/');
        }

        return $response->withRedirect('/fail-to-update-car');
    }

    public function deleteCar(Request $request, Response $response)
    {
        $req = $request->getParsedBody();

        if ($this->car->delete($red['id'])) {
            return $response->withRedirect('/');
        }

        return $response->withRedirect('/fail-to-delete-car');
    }

    public function fetchCarById($id)
    {
        return $this->car->fetchById($id);
    }
}
