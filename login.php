<?php
session_start();
include 'includes/db_connect.php'; 

// Se já estiver logado, não precisa processar o login
if (isset($_SESSION['usuario_id'])) {
    header("Location: home.php");
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos.';
    } else {
        // Usa Prepared Statement (Correto e Seguro)
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($senha, $user['senha'])) {
                // SUCESSO
                $_SESSION['usuario'] = $user['nome'];
                $_SESSION['usuario_id'] = $user['id']; // Chave Padronizada
                header('Location: home.php'); // Redirecionamento Consistente
                exit();
            } else {
                $erro = 'Senha incorreta.';
            }
        } else {
            $erro = 'Usuário não encontrado.';
        }
    }
}

// Se houver erro, armazena na sessão e redireciona para index.php exibir
if ($erro) {
    $_SESSION['erro'] = $erro;
}

// Redireciona para index.php (volta para a tela de login)
header("Location: index.php");
exit();