<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criar Conta</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">
    <h2 class="text-center mb-4">Criar Conta</h2>

    <form action="register.php" method="POST" onsubmit="return validarFormularioCadastro();">
        
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Confirmar Senha</label>
            <input type="password" name="confirmar" class="form-control" required>
        </div>

        <button class="btn btn-success w-100 mb-3">Cadastrar</button>
        <a href="index.php" class="btn btn-light w-100">Voltar</a>

    </form>
</div>

<script src="script.js"></script>

</body>
</html>