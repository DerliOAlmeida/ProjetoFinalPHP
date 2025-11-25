<?php
session_start();

// Verifica login usando usuario_id
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        /* Estilos específicos para esta página */
        .config-card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 12px;
            padding: 30px;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        a.option {
            text-decoration: none;
            font-size: 17px;
            display: block; /* Garante que o link preencha o item da lista */
            padding: 5px 0;
        }

        a.option:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="config-card">

    <h3 class="text-center mb-4">⚙ Configurações</h3>

    <ul class="list-group">

        <li class="list-group-item">
            <a class="option" href="alterar_dados.php">Alterar meus dados</a>
        </li>

        <li class="list-group-item">
            <a class="option" href="home.php">Voltar ao painel</a>
        </li>

        <li class="list-group-item">
            <a class="option text-danger" href="logout.php">Sair da conta</a>
        </li>

    </ul>

</div>

</body>
</html>