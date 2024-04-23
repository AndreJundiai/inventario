<?php
session_start();

// Função responsável por criar uma conexão com o banco de dados
function abrirBanco() {
    $conexao = new mysqli("localhost", "root", "1234", "novoBD");
    if ($conexao->connect_error) {
        die("Erro de conexão com o banco de dados: " . $conexao->connect_error);
    }
    return $conexao;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = md5($_POST["senha"]); // Criptografar a senha em MD5

    $banco = abrirBanco();

    // Consulta para verificar se o email e a senha correspondem a um usuário
    $sql = "SELECT * FROM usuarius WHERE email = ? AND senha = ?";
    $stmt = $banco->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $banco->error);
    }
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // ...

    if ($result->num_rows === 1) {
        // O login foi bem-sucedido, armazene informações do usuário na sessão (opcional)
        $row = $result->fetch_assoc();
        $_SESSION["id_usuario"] = $row["id"]; // Você pode armazenar informações adicionais conforme necessário
        $_SESSION["nome_usuario"] = $row["nome"];
        $_SESSION["opcao"] = $row["opcao"];

        // Redirecione para a página de perfil ou página inicial
        header("Location: index4.php"); // Substitua "perfil.php" pelo destino desejado
        exit();
    } else {
        // O login falhou, exiba uma mensagem de erro
        $mensagemErro = "Email ou senha incorretos. <a href='login.html'>Tente novamente</a>";
    }

    $banco->close();
} else {
    // Se alguém acessar diretamente esta página sem enviar o formulário, redirecione de volta para a página de login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Incluir o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Login</h2>
                        
                        <?php
                        // Exibir mensagem de erro (se houver)
                        if (isset($mensagemErro)) {
                            echo '<div class="alert alert-danger" role="alert">' . $mensagemErro . '</div>';
                        }
                        ?>

                        <form action="validar_login.php" method="POST">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" class="form-control" name="senha" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir o JS do Bootstrap e Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
