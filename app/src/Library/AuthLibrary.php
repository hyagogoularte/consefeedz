<?php
namespace App\Library;

use Psr\Log\LoggerInterface;

final class AuthLibrary
{
    private $db;
    private $session;

    public function __construct($db, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function hashPassword($string, $hash_method = 'md5')
    {
        if (function_exists('hash')) {
            return hash($hash_method, UNIQUE_SALT . $string);
        }

        return sha1(UNIQUE_SALT . $string);
    }

    public function loginCheck($email, $password, $user_agent)
    {
        $user = null;
        $sql = "SELECT * FROM user WHERE email = :email AND password = :password LIMIT 1";

        try {
            $query = $this->db->prepare($sql);
            $query->bindParam("email", $email);
            $query->bindParam("password", $this->hashPassword($password));
            $query->execute();

            while ($row = $query->fetch()) {
                $user = $row;
            }

            if (isset($user)) {
                $sql = "UPDATE user SET session = :session WHERE id = :id";
                $query = $this->db->prepare($sql);
                $query->bindParam("id", $user['id']);
                $query->bindParam("session", $user_agent);
                $query->execute();

                return $user;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            $this->logger->info('MySQL\' Error in AuthLibrary (loginCheck): ' . $e);
            return false;
        }

    }

    public function sessionCheck($email, $id, $user_agent)
    {
        $sql = "SELECT id FROM user WHERE id = :id AND email = :email AND session = :session LIMIT 1";

        try {
            $query = $this->db->prepare($sql);
            $query->bindParam("id", $id);
            $query->bindParam("email", $email);
            $query->bindParam("session", $user_agent);
            $query->execute();

            if ($row = $query->fetch()) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            $this->logger->info('MySQL\' Error in AuthLibrary (sessionCheck): ' . $e);

            return false;
        }
    }
}
