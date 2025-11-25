<?php
session_start();
include 'includes/db_connect.php'; 

$mensagem = '';
$tipo = '';
$token = $_GET['token'] ?? null;
$user_id = null; // Será definido se o token for válido
$token_valido = false;

// PARTE 1: VERIFICAÇÃO DO TOKEN (Método GET)
if ($token) {
    // 1. Busca o token no banco de dados e verifica a validade
    $sql = "SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Token encontrado e não expirado
        $data = $result->fetch_assoc();
        $user_id = $data['user_id'];
        $token_valido = true;
    } else {
        // Token inválido ou expirado
        $mensagem = "❌ Link de redefinição inválido ou expirado. Por favor, solicite uma nova redefinição de senha.";
        $tipo = "danger";
        // Limpa o token para não exibir o formulário
        $token = null; 
    }
} else {
    // Se o usuário acessar a página sem token
    $mensagem = "❌ Token de redefinição não fornecido. Acesse a página pelo link enviado no e-mail.";
    $tipo = "danger";
}


// PARTE 2: PROCESSAMENTO DO FORMULÁRIO DE NOVA SENHA (Método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token_form'])) {
    
    // Recupera os dados do formulário
    $token = $_POST['token_form'];
    $user_id = $_POST['user_id_form']; 
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Revalida o token *apenas* para garantir que não expirou entre o carregamento e o POST
    $sql_check = "SELECT user_id FROM password_resets WHERE token = ? AND user_id = ? AND expires_at > NOW()";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $token, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows !== 1) {
        $mensagem = "❌ O token de redefinição expirou ou é inválido. Tente novamente.";
        $tipo = "danger";
        $token = null; // Impede que o formulário seja reexibido
    } elseif ($nova_senha !== $confirmar_senha) {
        $mensagem = "❌ A nova senha e a confirmação não coincidem!";
        $tipo = "danger";
        $token_valido = true; // Mantém o formulário visível
    } elseif (strlen($nova_senha) < 6) {
        $mensagem = "❌ A senha deve ter pelo menos 6 caracteres!";
        $tipo = "danger";
        $token_valido = true; // Mantém o formulário visível
    } else {
        
        // 3. ATUALIZAÇÃO DA SENHA E EXCLUSÃO DO TOKEN
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        // Atualiza a senha
        $sql_update = "UPDATE usuarios SET senha=? WHERE id=?"; 
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $senha_hash, $user_id);

        if ($stmt_update->execute()) {
            
            // Exclui o token (CRUCIAL para a segurança)
            $sql_delete = "DELETE FROM password_resets WHERE token = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("s", $token);
            $stmt_delete->execute();
            
            // Sucesso
            $mensagem = "✅ Senha redefinida com sucesso! Você já pode fazer login.";
            $tipo = "success";
            
            // Redireciona para o login (index.php) após 3 segundos
            header("refresh:3;url=index.php"); 
            $token = null; // Impede que o formulário seja exibido novamente
        } else {
            $mensagem = "❌ Erro ao atualizar a senha no banco de dados.";
            $tipo = "danger";
            $token_valido = true; // Mantém o formulário visível
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">
    <h2 class="text-center mb-4">Definir Nova Senha</h2>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo; ?> text-center">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <?php 
    // Somente exibe o formulário se o token for válido
    if ($token && $token_valido): 
    ?>
    <form method="POST" action="redefinir_senha.php">
        <p class="text-muted text-center mb-4">
            Digite sua nova senha.
        </p>

        <input type="hidden" name="token_form" value="<?php echo htmlspecialchars($token); ?>">
        <input type="hidden" name="user_id_form" value="<?php echo htmlspecialchars($user_id); ?>">

        <div class="mb-3">
            <label class="form-label">Nova Senha</label>
            <input type="password" name="nova_senha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirme a Nova Senha</label>
            <input type="password" name="confirmar_senha" class="form-control" required>
        </div>

        <button class="btn btn-success w-100 mb-3">Salvar Nova Senha</button>
    </form>
    <?php endif; ?>
    
    <a href="index.php" class="btn btn-light w-100">Voltar ao Login</a>

</div>

</body>
</html>