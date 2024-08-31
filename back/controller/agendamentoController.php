<?php
include '../conexao.php';
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

if ($acao == 'agendar') {

    $idUsuario = (int)$_POST['id'];
    $email = pg_escape_string($_POST['email']);
    $idHora = (int)$_POST['hora'];
    $idEspecialidade = (int)$_POST['especialidade'];
    $data = pg_escape_string($_POST['data']);

    $pegaHora = "SELECT * FROM horarios WHERE id = $idHora";
    $sqlhora = pg_query($conn, $pegaHora);
    $resultadoHorario = pg_fetch_assoc($sqlhora);
    $hora = $resultadoHorario['hora'];

    // Verifica se já existe agendamento para o usuário
    $sqlAgendamentoAtivo = "SELECT * FROM agendamentos WHERE id_usuario = $idUsuario AND status = 1";
    $agendamentoAtivo = pg_query($conn, $sqlAgendamentoAtivo);

    if (pg_num_rows($agendamentoAtivo) == 0) {
        $validaHorario = "SELECT * FROM agendamentos WHERE especialidade = $idEspecialidade AND data = '$data' AND hora = '$hora'";
        $sqlValidaHorario = pg_query($conn, $validaHorario);

        // Se não existir agendamento ativo, verifica se já existe agendamento do mesmo horário e especialidade
        if (pg_num_rows($sqlValidaHorario) > 0) {
            echo "<script>
                alert('Desculpe, este horário já está ocupado. Por favor, escolha outro horário.');
                window.location.href = '../../front/pages/home.php';
                </script>";
        } else {
            // Caso horário esteja livre, insere no banco
            $sql = "INSERT INTO agendamentos (id_usuario, email, especialidade, data, hora, status) VALUES ($idUsuario, '$email', $idEspecialidade, '$data', '$hora', 1)";
            
            if (pg_query($conn, $sql)) {
                echo "<script>
                    alert('Agendado com sucesso!');
                    window.location.href = '../../front/pages/home.php';
                    </script>";
            } else {
                echo "Erro ao cadastrar agendamento: " . pg_last_error($conn);
                echo "<script>
                    window.location.href = '../../front/pages/home.php';
                    </script>";
            }
        }
    } else {
        echo "<script>
            alert('Não é possível ter mais de um agendamento ativo!');
            window.location.href = '../../front/pages/home.php';
            </script>";
    }
}

if ($acao == 'cancelarAgendamento') {
    $status = 3;
    $id = (int)$_POST['id'];

    $sqlPegaSeStatusFinalizado = "SELECT * FROM agendamentos WHERE id = $id AND status = 2";
    $valida = pg_query($conn, $sqlPegaSeStatusFinalizado);

    if (pg_num_rows($valida) == 0) {
        $sql = "UPDATE agendamentos SET status = $status WHERE id = $id";
        
        if (pg_query($conn, $sql)) {
            echo "<script>
                alert('Agendamento cancelado!');
                window.location.href = '../../front/pages/agendamentos.php';
                </script>";
            exit;
        } else {
            echo "Erro ao cancelar agendamento: " . pg_last_error($conn);
            echo "<script>
                window.location.href = '../../front/pages/agendamentos.php';
                </script>";
        }
    } else {
        echo "<script>
            alert('Não é possível cancelar um agendamento finalizado!');
            window.location.href = '../../front/pages/agendamentos.php';
            </script>";
    }
}

if ($acao == 'finalizarAgendamento') {
    $status = 2;
    $id = (int)$_POST['id'];

    $sqlPegaSeStatusCancelado = "SELECT * FROM agendamentos WHERE id = $id AND status = 3";
    $valida = pg_query($conn, $sqlPegaSeStatusCancelado);

    if (pg_num_rows($valida) == 0) {
        $sql = "UPDATE agendamentos SET status = $status WHERE id = $id";
        
        if (pg_query($conn, $sql)) {
            echo "<script>
                alert('Agendamento finalizado!');
                window.location.href = '../../front/pages/agendamentos.php';
                </script>";
            exit;
        } else {
            echo "Erro ao finalizar agendamento: " . pg_last_error($conn);
            echo "<script>
                window.location.href = '../../front/pages/agendamentos.php';
                </script>";
        }
    } else {
        echo "<script>
            alert('Não é possível finalizar um agendamento cancelado!');
            window.location.href = '../../front/pages/agendamentos.php';
            </script>";
    }
}
?>
