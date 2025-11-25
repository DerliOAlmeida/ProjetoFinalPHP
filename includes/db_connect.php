<?php
$host = 'localhost';
$user = 'root';
$pass = 'Senac'; // ATENÇÃO: Troque 'Senac' por uma senha forte em produção!
$db = 'sistema_login';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Erro ao conectar: ' . $conn->connect_error);
}
?>