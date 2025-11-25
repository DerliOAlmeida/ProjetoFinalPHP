<?php 
session_start(); 
// Variável para feedback ao usuário
$mensagem = '';
$tipo = '';

// Simulação de processamento (Você implementará a lógica de envio de e-mail aqui)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    
    if (empty($nome)) {
        $mensagem = "❌ Por favor, preencha seu Nome Completo.";
        $tipo = "danger";
    } else {
        // Aqui deve ir a lógica para ENVIAR um e-mail ao SUPORTE
        // com o Nome e Documento do usuário, solicitando o e-mail de cadastro.
        
        $mensagem = "✅ Sua solicitação foi enviada com sucesso! O suporte entrará em contato com você em breve pelo seu e-mail alternativo ou telefone.";
        $tipo = "success";
        
        // Limpar variáveis para evitar reenvio
        $nome = '';
        $documento = '';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar E-mail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">
    <h2 class="text-center mb-4">Recuperar E-mail</h2>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo; ?> text-center">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="esqueci_email.php">
        <p class="text-muted text-center mb-4">
            Para que possamos localizar seu cadastro, informe os dados abaixo.
        </p>

        <div class="mb-3">
            <label class="form-label">Nome Completo</label>
            <input type="text" name="nome" class="form-control" 
                   value="<?php echo htmlspecialchars($nome ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">CPF / RG (Opcional)</label>
            <input type="text" name="documento" class="form-control" 
                   value="<?php echo htmlspecialchars($documento ?? ''); ?>">
        </div>

        <button class="btn btn-warning w-100 mb-3">Enviar Solicitação</button>
        <a href="index.php" class="btn btn-light w-100">Voltar ao Login</a>
    </form>
</div>

</body>
</html>