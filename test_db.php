<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

include 'includes/db_connect.php';

if (!isset($conn)) {
    die("Conexão \$conn não definida.");
}

if ($conn->connect_error) {
    die("Erro conexão: " . $conn->connect_error);
}

echo "Conexão OK! MySQL server version: " . $conn->server_info;
