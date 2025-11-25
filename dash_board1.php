<?php
session_start();
// Verificação de login corrigida para usuario_id
if (!isset($_SESSION['usuario_id'])) {
    // Redirecionamento corrigido para index.php
    header('Location: index.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"> </head>
<body class="bg-light">
<div class="container mt-5">
<h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>

<?php if (isset($_SESSION['msg_sucesso'])): ?>
    <div class="alert alert-success mt-3">
        <?php 
            echo $_SESSION['msg_sucesso']; 
            unset($_SESSION['msg_sucesso']);
        ?>
    </div>
<?php endif; 
?>

<a href="alterar_dados.php" class="btn btn-info mt-3">Alterar Meus Dados</a>
<a href="home.php" class="btn btn-secondary mt-3">Ir para Home</a> 
<a href="logout.php" class="btn btn-danger mt-3">Sair</a>
</div>
</body>
</html>