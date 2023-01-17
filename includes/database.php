<?php
require "autoload.php";

class Database
{
    private $connection;

    public function getConnection() {
        $this->connection = null;
        try {
            $this->connection = new PDO("mysql:host=" . env("DB_HOST") . ";dbname=" . env("DB_NAME"), env("DB_USERNAME"), env("DB_PASSWORD"));
        } catch (PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }
        return $this->connection;
    }
}

$conn = null;
$databaseService = new Database();
$conn = $databaseService->getConnection();
