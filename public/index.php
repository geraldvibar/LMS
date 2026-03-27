<?php
/**
 * Library Management System - Entry Point
 * 
 * This is the main entry point for the application.
 * All requests should go through this file.
 */

// Start session
session_start();

// Load bootstrap (autoloader and init)
require_once __DIR__ . '/../App/Core/bootstrap.php';

// Load and run the router
use App\Core\Router;

new Router();

