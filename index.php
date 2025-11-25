<?php
session_start();

// Redireciona para a home se o usuário já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: home.php");
    exit();
}

// Captura a mensagem de erro da sessão e a limpa
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['erro']); 

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acesso ao Sistema</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">
    <h2 class="text-center mb-4">Acessar Sistema</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" onsubmit="return validarFormularioLogin();">

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100 mb-3">Entrar</button>

        <div class="d-flex justify-content-between mb-3 flex-wrap">
            <a href="esqueci_email.php" class="text-decoration-none small text-secondary">Esqueci E-mail</a>
            <a href="esqueci_senha.php" class="text-decoration-none small text-secondary">Esqueci a Senha</a>
        </div>
        
        <div class="dropdown-divider border-top border-light mb-3"></div>

        <a href="cadastro.php" class="btn btn-light w-100">Criar Conta</a>

    </form>
</div>

<script src="script.js"></script>

</body>
</html>