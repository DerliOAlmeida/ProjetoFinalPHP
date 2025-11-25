<?php
session_start();
// Verifica login usando usuario_id
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/db_connect.php';
$usuario_id = $_SESSION['usuario_id'];
$nome_atual = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $novo_nome = trim($_POST['nome']);
    $nova_senha = $_POST['senha'];

    if ($novo_nome !== "") {

        if ($nova_senha !== "") {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome=?, senha=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            // Assumindo que o campo nome e senha podem ser strings
            $stmt->bind_param("ssi", $novo_nome, $senha_hash, $usuario_id); 
        } else {
            $sql = "UPDATE usuarios SET nome=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $novo_nome, $usuario_id);
        }

        if ($stmt->execute()) {
            $_SESSION['usuario'] = $novo_nome;
            // Redireciona para home.php com aviso de sucesso
            header("Location: home.php?atualizado=1"); 
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alterar Dados</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card-glass">

    <h2 class="text-center mb-4">Alterar Meus Dados</h2>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($nome_atual); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nova Senha (opcional)</label>
            <input type="password" name="senha" class="form-control">
        </div>

        <button class="btn btn-success w-100 mb-3">Salvar Alterações</button>
        <a href="home.php" class="btn btn-light w-100">Cancelar</a>

    </form>
</div>

</body>
</html>