<?php

namespace App\Library;

use Psr\Log\LoggerInterface;

final class CarLibrary
{
    private $auth;
    private $db;
    private $logger;

    public function __construct($db, AuthLibrary $auth, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->logger = $logger;
    }

    function list() {
        try {
            $sql = "SELECT * FROM car;";
            $query = $this->db->query($sql);

            return $query;
        } catch (\Exception $e) {
            $this->logger->info('MySQL\'s Error in CarLibrary (list): ' . $e);
            return false;
        }
    }

    public function fetchById($id)
    {

        $car = null;
        $sql = "SELECT * FROM car WHERE id = :id";

        try {
            $query = $this->db->prepare($sql);
            $query->bindParam("id", $id);
            $query->execute();

            while ($row = $query->fetch()) {
                $car = $row;
            }

            if (isset($car)) {
                return $car;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            $this->logger->info('MySQL\'s Error in CarLibrary (fetchById): ' . $e);
            return false;
        }
    }

    public function filterBy($type, $search)
    {
        $car = null;
        $sql = "SELECT * FROM car WHERE :brand LIKE :search;";

        try {
            $query = $this->db->prepare($sql);
            $query->bindParam("brand", $type);
            $query->bindParam("search", $search);
            $query->execute();

            while ($row = $query->fetch()) {
                $car = $row;
            }

            if (isset($car)) {
                return $car;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            $this->logger->info('MySQL\'s Error in CarLibrary (filterBy): ' . $e);
            return false;
        }
    }

    public function insert($brand, $model, $year, $color)
    {
        try {
            $sql = "INSERT INTO car (brand, model, year, color) VALUES (:brand, :model, :year, :color)";
            $query = $this->db->prepare($sql);

            $query->bindParam("brand", $brand);
            $query->bindParam("model", $model);
            $query->bindParam("year", $year);
            $query->bindParam("color", $color);

            $query->execute();

            return true;
        } catch (\Exception $e) {
            $this->logger->info('MySQL\'s Error in CarLibrary (insert): ' . $e);
            return false;
        }
    }

    public function update($id, $brand, $model, $color, $year)
    {
        try {
            $sql = "UPDATE car SET brand=?, model=?, color=?, year=? WHERE id=?";
            $query = $this->db->prepare($sql);

            $query->execute([$brand, $model, $color, $year, $id]);

            return true;
        } catch (\Exception $e) {
            $this->logger->info('MySQL\'s Error in CarLibrary (update): ' . $e);
            return false;
        }

    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM car WHERE id=:id";
            $query = $this->db->prepare($sql);
            $query->bindParam("id", $args['id']);
            $query->execute();

            return true;
        } catch (\Exception $e) {
            $this->logger->info('MySQL\'s Error in CarLibrary (delete): ' . $e);
            return false;
        }

    }

}
