<?php 
session_start(); 

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclui a conexão com o banco para verificar o e-mail
include 'includes/db_connect.php'; 

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "❌ Por favor, insira um e-mail válido.";
        $tipo = "danger";
    } else {
        // 1. VERIFICAÇÃO DE E-MAIL
        $sql = "SELECT id, nome FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // MENSAGEM DE SEGURANÇA
            $mensagem = "✅ Se o e-mail estiver cadastrado, você receberá um link de redefinição em breve.";
            $tipo = "success";
        } else {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            
            // 2. GERAÇÃO E ARMAZENAMENTO DO TOKEN
            $token = bin2hex(random_bytes(32)); // Token seguro de 64 caracteres
            
            // Define a expiração para 30 minutos a partir de agora
            $expiracao = new DateTime('+30 minutes');
            $expires_at = $expiracao->format('Y-m-d H:i:s');
            
            // Exclui tokens antigos (garante que apenas o último link enviado funcione)
            $sql_delete = "DELETE FROM password_resets WHERE user_id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $user_id);
            $stmt_delete->execute();
            
            // Insere o novo token
            $sql_insert = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("iss", $user_id, $token, $expires_at);
            
            if (!$stmt_insert->execute()) {
                // Se o token não puder ser salvo, interrompe o processo.
                $mensagem = "❌ Erro ao gerar o token de redefinição. Tente novamente.";
                $tipo = "danger";
                // É fundamental não prosseguir com o envio do e-mail se o token não foi salvo
                goto end_process; 
            }
            
            // 3. MONTAGEM DO LINK DE REDEFINIÇÃO (agora com o token real)
            $base_url = "http://localhost/Projeto4"; 
            $link_redefinicao = $base_url . "/redefinir_senha.php?token=" . $token;
            // NOTA: Não é necessário mais enviar o 'id' na URL, pois o token já aponta para o user_id

            // 4. ENVIO DE E-MAIL COM PHPMailer
            $mail = new PHPMailer(true);

            try {
                // ... (Suas configurações de SMTP com Senha de Aplicativo) ...
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = 'derlialmeida7@gmail.com'; 
                $mail->Password   = 'xveftddpgsgdkkkj'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
                $mail->Port       = 587; 

                // Destinatários e Conteúdo
                $mail->setFrom('no-reply@seusistema.com', 'Sistema de Login');
                $mail->addAddress($email, $user['nome']);
                $mail->isHTML(true);
                $mail->Subject = 'Redefinicao de Senha';
                
                $mail->Body    = "Olá, {$user['nome']},<br><br>Você solicitou a redefinição de sua senha.<br>Clique no link abaixo para criar uma nova senha:<br><a href='{$link_redefinicao}'>{$link_redefinicao}</a><br><br>O link expirará em 30 minutos.<br><br>Se você não solicitou, ignore este e-mail.";
                $mail->AltBody = "Para redefinir sua senha, acesse o link: " . $link_redefinicao . ". O link expira em 30 minutos.";

                $mail->send();
                
                $mensagem = "✅ Um link de redefinição de senha foi enviado para seu e-mail. Verifique a sua caixa de entrada e SPAM.";
                $tipo = "success";
                
            } catch (Exception $e) {
                // Erro de SMTP
                $mensagem = "❌ Não foi possível enviar o e-mail. Detalhe do Erro: {$mail->ErrorInfo}";
                $tipo = "danger";
                error_log("PHPMailer Error: " . $e->getMessage());
            }
        }
    }
}
end_process: // Label para o 'goto' em caso de erro de inserção no banco
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">
    <h2 class="text-center mb-4">Recuperar Senha</h2>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo; ?> text-center">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="esqueci_senha.php">
        <p class="text-muted text-center mb-4">
            Digite seu e-mail para receber o link de redefinição de senha.
        </p>
        <div class="mb-3">
            <label class="form-label">E-mail de Cadastro</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <button class="btn btn-warning w-100 mb-3">Solicitar Redefinição</button>
        <a href="index.php" class="btn btn-light w-100">Voltar</a>
    </form>
</div>

</body>
</html>