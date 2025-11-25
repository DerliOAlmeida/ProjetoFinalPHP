<?php
session_start();

// Se não tiver login ativo (usuario_id), volta ao index.php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['usuario'] ?? "Usuário";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> 

</head>

<body class="d-flex justify-content-center align-items-center vh-100">

<div class="container container-box">
    <div class="content-card">

        <?php if (isset($_GET['atualizado'])): ?>
            <div class="alert alert-success text-center">
                Dados atualizados com sucesso!
            </div>
        <?php endif; ?>

        <h1 class="mb-3">Olá, <?php echo htmlspecialchars($usuario); ?>!</h1>

        <p class="text-muted mb-4">
            Seja bem-vindo(a) ao sistema! Escolha uma opção abaixo.
        </p>

        <div class="d-grid gap-3">
            <a href="alterar_dados.php" class="btn btn-primary btn-lg">
                Alterar Meus Dados
            </a>
            <a href="configuracoes.php" class="btn btn-secondary btn-lg">
                Configurações
            </a>
            <a href="logout.php" class="btn btn-danger btn-lg">
                Sair
            </a>
        </div>

    </div>
</div>

</body>
</html>