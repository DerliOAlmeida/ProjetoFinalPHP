<?php
session_start();

// Se já estiver logado, redireciona para home.php
if (isset($_SESSION['usuario_id'])) { 
    header('Location: home.php');
    exit();
}

include 'includes/db_connect.php'; 

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Código de processamento de cadastro (mantido, pois já estava seguro e correto)
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // ... (Lógica de validação e inserção no banco) ...
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
        $mensagem = "❌ Preencha todos os campos.";
        $tipo = "danger";
    } elseif ($senha !== $confirmar) {
        $mensagem = "❌ As senhas não coincidem!";
        $tipo = "danger";
    } else {
        try {
            $sql_check = "SELECT id FROM usuarios WHERE email = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $res = $stmt_check->get_result();

            if ($res->num_rows > 0) {
                $mensagem = "❌ Este e-mail já está cadastrado.";
                $tipo = "danger";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);

                if ($stmt_insert->execute()) {
                    $mensagem = "✅ Usuário cadastrado com sucesso! Redirecionando para o login...";
                    $tipo = "success";
                    // Redireciona automaticamente após 3 segundos para o login (index.php)
                    header("refresh:3;url=index.php"); 
                } else {
                    $mensagem = "❌ Erro ao cadastrar. Tente novamente.";
                    $tipo = "danger";
                }
            }
        } catch (Exception $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $mensagem = "❌ Erro interno no servidor.";
            $tipo = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">
    <h3 class="text-center mb-4">Criar Conta</h3>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo; ?>" role="alert">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>
    
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Nome Completo</label>
            <input type="text" name="nome" class="form-control"
                   value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar Senha</label>
            <input type="password" name="confirmar" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>

        <p class="text-center mt-3">
            Já tem conta? <a href="index.php">Entrar</a>
        </p>
    </form>
</div>
</body>
</html>