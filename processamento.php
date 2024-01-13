<?php
session_start(); // Certifique-se de iniciar a sessão antes de acessar $_SESSION

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];

        // Conexão com o banco de dados (substitua os valores conforme sua configuração)
        $host = 'localhost';
        $usuario = 'root';
        $senha = '1234';
        $banco = 'novoBD';

        // Use a função mysqli para estabelecer a conexão
        $conexao = mysqli_connect($host, $usuario, $senha, $banco);

        // Verifica sde a conexão foi estabelecida corretamente
        if (!$conexao) {
            echo 'Falha na conexão com o banco de dados: ' . mysqli_connect_error();
            exit;
        }

        function obterDiaSemana() {
            $diaDaSemana = date("N");

            // Verifica o dia da semana e retorna o nome correspondente
            $dias = [
                1 => "Segunda-feira",
                2 => "Terça-feira",
                3 => "Quarta-feira",
                4 => "Quinta-feira",
                5 => "Sexta-feira",
                6 => "Sábado",
                7 => "Domingo"
            ];

            return $dias[$diaDaSemana] ?? "Dia inválido";
        }

        $idUsuario = $_SESSION["id_usuario"];
        $dia = date('Y-m-d'); // Adicione a obtenção da data
        $diaSemana = obterDiaSemana(); // Adicione a obtenção do dia da semana
        $horaAtual = date('H:i:s'); // Adicionde a obtenção da hora atual

        function somarHorarios($horario1, $horario2) {
            $time1 = new DateTime($horario1);
            $time2 = new DateTime($horario2);

            $resultado = $time1->sub($time2->diff(new DateTime('00:00:00')));

            return $resultado->format('H:i:s');
        }

        $acao = mysqli_real_escape_string($conexao, $acao);

        if ($acao === "Registrar Entrada") {
            $query = "INSERT INTO pontoRegistro (dia, diaSemana, hora_entrada, idUsuario) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $dia, $diaSemana, $horaAtual, $idUsuario);

            if (mysqli_stmt_execute($stmt)) {
                echo 'Registro de ' . $acao . ' realizado com sucesso!';
            } else {
                echo 'Erro ao realizar o registro de ' . $acao . ': ' . mysqli_error($conexao);
            }
        } elseif ($acao === 'Registrar Inicio Almoço' || $acao === 'Registrar Saída' || $acao === 'Registrar Fim Almoço') {
            // Adicione a obtenção da data, dia da semana e hora atual como feito anteriormente

            $campoHora = '';
            switch ($acao) {
                case 'Registrar Inicio Almoço':
                    $query = "UPDATE pontoRegistro SET hora_almoço_entrada = ? WHERE dia = ?";
                    break;
                case 'Registrar Saída':
                    $query = "UPDATE pontoRegistro SET hora_saida = ? WHERE dia = ?";
                    break;
                case 'Registrar Fim Almoço':
                    $query = "UPDATE pontoRegistro SET hora_almoço_saida = ? WHERE dia = ?";
                    break;
                default:
                    break;
            }

            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_bind_param($stmt, "ss", $horaAtual, $dia);

            if (mysqli_stmt_execute($stmt)) {
                echo 'Registro de ' . $acao . ' realizado com sucesso!';
            } else {
                echo 'Erro ao realizar o registro de ponto ' . $acao . ': ' . mysqli_error($conexao);
            }
        } else {
            echo 'Ação desconhecida!';
        }

        // Fechar a conexão com o banco de dados
        mysqli_close($conexao);
    }
}
?>
