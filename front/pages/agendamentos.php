<?php include('./header.php') ?>

<div class="content-wrapper" style="padding: 15px;">
    <h2>Agendamentos </h2>
    <?php
    if ($perfilId == 2) {
    ?>
        <button class="btn btn-success" style="width: 100px; margin: 7px 0px;" data-bs-toggle="modal" data-bs-target="#agendar" onclick="abrirModalAgendamento()">
            <i class="fas fa-plus"></i> Novo
        </button>
    <?php
    }
    ?>

    <?php
    if ($perfilId == 1) {
    ?>
    <?php
        $sqlRegistros = "SELECT a.id AS id, a.id_usuario, TO_CHAR(a.data, 'DD/MM/YYYY') AS data,   TO_CHAR(a.hora, 'HH24:MI') AS hora, e.nome, u.email AS email, 
                    s.nome AS status, s.id AS codigo_status
                    FROM agendamentos a
                    LEFT JOIN especialidades e ON e.id = a.especialidade
                    LEFT JOIN usuarios u ON u.id = a.id_usuario
                    LEFT JOIN status s ON s.id = a.status
                    order by a.id DESC";
                    
    } else if ($perfilId == 2) {
    ?>
    <?php
        $sqlRegistros = "SELECT a.id AS id, a.id_usuario, TO_CHAR(a.data, 'DD/MM/YYYY') AS data,   TO_CHAR(a.hora, 'HH24:MI') AS hora, e.nome, u.email AS email, 
                    s.nome AS status, s.id AS codigo_status
                    FROM agendamentos a
                    LEFT JOIN especialidades e ON e.id = a.especialidade
                    LEFT JOIN usuarios u ON u.id = a.id_usuario
                    LEFT JOIN status s ON s.id = a.status
                    WHERE a.id_usuario = $id AND s.id = 1
                    order by a.id DESC";
    } else {
    ?>
    <?php
        $sqlRegistros = "SELECT a.id AS id, a.email AS email, TO_CHAR(a.data, 'DD/MM/YYYY') AS data,   TO_CHAR(a.hora, 'HH24:MI') AS hora, e.nome AS nome, 
                    s.id AS codigo_status, s.nome AS status
                    FROM agendamentos a
                    LEFT JOIN perfil_especialidade pe ON pe.id_especialidade = a.especialidade
                    LEFT JOIN usuarios u ON u.id = a.id_usuario
                    LEFT JOIN especialidades e ON e.id = a.especialidade
                    LEFT JOIN status s ON s.id = a.status
                    WHERE pe.id_perfil = $perfilId AND s.id != 3
                    order by a.id DESC";
    }

    $dadosRegistros = pg_query($conn, $sqlRegistros);

    if (pg_num_rows($dadosRegistros) > 0) {
    ?>
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: center;">ID</th>
                        <th style="width: 15%;">EMAIL</th>
                        <th style="width: 15%;">DATA</th>
                        <th style="width: 10%;">HORÁRIO</th>
                        <th style="width: 15%;">ESPECIALIDADE</th>
                        <th style="width: 10%;">STATUS</th>
                        <th style="width: 10%;">AÇÕES</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while ($dadosAgendamentos = pg_fetch_assoc($dadosRegistros)) {
                        $idAgendamento = $dadosAgendamentos['id'];
                        $statusColor = getStatusColor($dadosAgendamentos['codigo_status']);
                    ?>
                        <tr>
                            <td style="text-align:center;"><?php echo htmlspecialchars($dadosAgendamentos['id']); ?></td>
                            <td><?php echo htmlspecialchars($dadosAgendamentos['email']); ?></td>
                            <td><?php echo htmlspecialchars($dadosAgendamentos['data']); ?></td>
                            <td><?php echo htmlspecialchars($dadosAgendamentos['hora']); ?></td>
                            <td><?php echo htmlspecialchars($dadosAgendamentos['nome']); ?></td>
                            <td><span style="color: <?php echo htmlspecialchars($statusColor); ?>;"> <?php echo htmlspecialchars($dadosAgendamentos['status']); ?></span></td>
                            <td>
                                <?php if ($perfilId == 1) { ?>
                                    <i title="Cancelar" class="fas fa-ban" style="cursor: pointer; margin: 0px 5px;" onclick="abrirAlert('<?php echo htmlspecialchars($idAgendamento); ?>', 'idCancelar')" data-bs-toggle="modal" data-bs-target="#cancelarAgendamento"></i>
                                    <i title="Finalizar" class="fas fa-times" style="cursor: pointer; margin: 0px 5px;" onclick="abrirAlert('<?php echo htmlspecialchars($idAgendamento); ?>', 'idFinalizar')" data-bs-toggle="modal" data-bs-target="#finalizarAgendamento"></i>
                                <?php } elseif ($perfilId == 2) { ?>
                                    <i title="Cancelar" class="fas fa-ban" style="cursor: pointer; margin: 0px 5px;" onclick="abrirAlert('<?php echo htmlspecialchars($idAgendamento); ?>', 'idCancelar')" data-bs-toggle="modal" data-bs-target="#cancelarAgendamento"></i>
                                <?php } else { ?>
                                    <i title="Finalizar" class="fas fa-times" style="cursor: pointer; margin: 0px 5px;" onclick="abrirAlert('<?php echo htmlspecialchars($idAgendamento); ?>', 'idFinalizar')" data-bs-toggle="modal" data-bs-target="#finalizarAgendamento"></i>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
        echo '
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Sem dados!</h5>
            </div>
            ';
    }
    ?>

    <?php
    function getStatusColor($statusColor)
    {
        switch ($statusColor) {
            case 1:
                return 'green';
            case 2:
                return 'red';
            default:
                return 'orange';
        }
    }
    ?>

    <!-- Modal para Agendar -->
    <div class="modal fade" id="agendar" tabindex="-1" aria-labelledby="modalAgendamento" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAgendamento">Agendar Consulta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="agendamentoForm" action="../../back/controller/agendamentoController.php?acao=agendar" method="post" style="padding: 20px;">
                    <div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                        <p style="padding: 10px auto;"><b>Especialidade:</b></p>
                        <select class="form-select" aria-label="Default select example" name="especialidade" id="especialidade" style="margin-bottom: 10px;">
                            <option selected>Selecione uma especialidade...</option>
                            <?php
                            $queryEspecialidades = "SELECT * FROM especialidades";
                            $especialidades = pg_query($conn, $queryEspecialidades);

                            if ($especialidades) {
                                while ($dadosEspecialidades = pg_fetch_assoc($especialidades)) {
                            ?>
                                    <option value="<?php echo htmlspecialchars($dadosEspecialidades['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($dadosEspecialidades['nome'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                            <?php
                                }
                            } else {
                                echo "<option value=''>Nenhuma especialidade encontrada</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <p><b>Dia:</b></p>
                        <p><input class="form-control" id="data" type="date" name="data" /></p>

                        <script>
                            const inputDate = document.getElementById('data');

                            inputDate.addEventListener('change', function() {
                                const selectedDate = new Date(inputDate.value);
                                const day = selectedDate.getDay();

                                if (day === 0 || day === 6) { // Se for domingo (0) ou sábado (6)
                                    alert("Por favor, selecione uma data de segunda a sexta-feira.");
                                    inputDate.value = '';
                                }
                            });
                        </script>
                    </div>
                    <div>
                        <p><b>Horário:</b></p>
                        <select class="form-select" name="hora" id="hora" style="margin-bottom: 10px;">
                            <option>Selecione um horário...</option>
                            <?php
                            $queryHorarios = "SELECT * FROM horarios";
                            $horarios = pg_query($conn, $queryHorarios);

                            if ($horarios) {
                                while ($dadosHorarios = pg_fetch_assoc($horarios)) {
                            ?>
                                    <option value="<?php echo htmlspecialchars($dadosHorarios['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($dadosHorarios['hora'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                            <?php
                                }
                            } else {
                                echo "<option value=''>Nenhum horário disponível</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-dark btn-block" style="background-color: black; border: 1px solid white;">Agendar</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Modal para Atender -->
    <div class="modal fade" id="cancelarAgendamento" tabindex="-1" aria-labelledby="cancelarAgendamento" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="display: flex; align-items: center; justify-content: center;padding: 40px 0px 0px;">
                    <form id="cancelarForm" action="../../back/controller/agendamentoController.php?acao=cancelarAgendamento" method="post" style="padding: 20px;">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fa fa-exclamation-circle" style="font-size: 60px; padding: 10px; color: orange;"></i>
                            <h3>Cancelar agendamento?</h3>
                        </div>
                        <p style="font-size: 20px;">Não é possível reverter essa ação!</p>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap:10px; padding-bottom: 20px;">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="width: 25%; font-size: 20px;">Fechar</button>
                    <input type="text" name="id" id="idCancelar" value="" readonly hidden>
                    <button type="submit" class="btn btn-success" style="width: 25%; font-size: 20px;">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Finalizar -->
    <div class="modal fade" id="finalizarAgendamento" tabindex="-1" aria-labelledby="finalizarAgendamento" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="display: flex; align-items: center; justify-content: center;padding: 40px 0px 0px;">
                    <form id="finalizarForm" action="../../back/controller/agendamentoController.php?acao=finalizarAgendamento" method="post" style="padding: 20px;">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fa fa-exclamation-circle" style="font-size: 60px; padding: 10px;color: red"></i>
                            <h3>Finalizar atendimento?</h3>
                        </div>
                        <p style="font-size: 20px;">Não é possível reverter essa ação!</p>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap:10px; padding-bottom: 20px;">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="width: 25%; font-size: 20px;">Fechar</button>
                    <input type="text" name="id" id="idFinalizar" value="" readonly hidden>
                    <button type="submit" class="btn btn-success" style="width: 25%; font-size: 20px;">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./footer.php') ?>