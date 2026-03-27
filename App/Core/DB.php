<?php

namespace App\Core;

use App\Config\Database;
use PDO;
use PDOException;

class DB
{
    // This property will store the database connection.
    // It is static so only ONE connection is created (Singleton pattern).
    private static $connection = null;

    /**
     * Get database connection
     * 
     * If connection does not exist yet, create it.
     * Otherwise, return the existing connection.
     */
    public static function getDB()
    {
        // Check if connection is not yet created
        if (self::$connection === null) {

            // Create DSN (Data Source Name)
            // This tells PDO what type of database and which database to connect to
            $dsn = "mysql:host=" . Database::HOST .
                ";dbname=" . Database::DBNAME .
                ";charset=" . Database::CHARSET;

            try {
                // Create new PDO connection
                self::$connection = new PDO(
                    $dsn,
                    Database::USER,
                    Database::PASS,
                    [
                        // Throw exceptions when errors occur
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                        // Fetch results as objects (e.g., $user->username)
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                    ]
                );
            } catch (PDOException $e) {

                // Stop the program if connection fails
                die("Database connection failed: " . $e->getMessage());
            }
        }

        // Return the database connection
        return self::$connection;
    }
}
