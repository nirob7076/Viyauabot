<?php
// error reporting on for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Supabase Database Credentials
$host = "db.ohviumjkxexxgoxlywwy.supabase.co"; 
$port = "5432"; 
$dbname = "postgres";
$user = "postgres"; 
$password = "gajarbotol."; // আপনার দেওয়া পাসওয়ার্ড

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>
