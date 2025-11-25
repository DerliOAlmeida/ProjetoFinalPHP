<?php 
$conn = new mysqli('localhost', 'root', 'Senac', 'sistema_login'); 

if ($conn->connect_error) {
    die('Erro: ' . $conn->connect_error);
}
?>