<?php include('./header.php') ?>

<div class="content-wrapper" style="padding: 15px;">
    <h2>Especialistas </h2>
    <button class="btn btn-success" style="width: 100px; margin: 7px 0px;" data-bs-toggle="modal" data-bs-target="#cadastraOuEditaEspecialista" onclick="abrirModalEspecialista()"><i class="fas fa-plus"></i> Novo</button>

    <?php
    $sqlEspecialistas = "SELECT u.id AS id_especialista, u.email AS email_especialista, p.nome AS perfil_nome, u.perfil_id as perfil_id
                        FROM usuarios AS u
                        LEFT JOIN perfil p ON p.id = u.perfil_id
                        WHERE u.perfil_id != 2 AND u.perfil_id != 1
                        order by u.id DESC";

    $dadosEspecialistas = pg_query($conn, $sqlEspecialistas);

    if ($dadosEspecialistas && pg_num_rows($dadosEspecialistas) > 0) {
    ?>
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align:center;">ID</th>
                        <th style="width: 25%; text-align:center;">EMAIL</th>
                        <th style="width: 25%; text-align:center;">PERFIL</th>
                        <th style="width: 10%; text-align:center;">ACOES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($especialista = pg_fetch_assoc($dadosEspecialistas)) {
                        $idEspecialista = htmlspecialchars($especialista['id_especialista'], ENT_QUOTES, 'UTF-8');
                        $emailEspecialista = htmlspecialchars($especialista['email_especialista'], ENT_QUOTES, 'UTF-8');
                        $perfilEspecialista = htmlspecialchars($especialista['perfil_id'], ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr>
                            <td style="text-align:center;"><?php echo htmlspecialchars($especialista['id_especialista'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="text-align:center;"><?php echo htmlspecialchars($especialista['email_especialista'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="text-align:center;"><?php echo htmlspecialchars($especialista['perfil_nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="text-align:center;">
                                <i class="fas fa-edit" onclick="abrirModalEspecialista('<?php echo $idEspecialista; ?>', '<?php echo $emailEspecialista; ?>', '<?php echo $perfilEspecialista; ?>')" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#cadastraOuEditaEspecialista"></i>
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


    <!--Cadastra ou edita de Usuário Especialista-->
    <div class="modal fade" id="cadastraOuEditaEspecialista" tabindex="-1" aria-labelledby="cadastraOuEditaEspecialista" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cadastraOuEditaEspecialista">Cadastra/Edita Especialista</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cadastraOuEditaEspecialistaForm" action="../../back/controller/especialistaController.php?acao=cadastraOuEditaEspecialista" method="post" style="padding: 20px;">

                    <input type="hidden" name="id" id="id_especialista" value="" readonly>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email_especialista">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Senha" name="senha" id="senha">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-select" aria-label="Default select example" name="id_perfil" id="perfil_especialista" style="margin-bottom: 10px;">
                            <?php
                            $queryPerfilEspecialidades = "SELECT * from perfil
                            WHERE id != 1 AND id != 2";

                            $perfilEspecialidades = pg_query($conn, $queryPerfilEspecialidades);

                            while ($dadosEspecialidades = pg_fetch_assoc($perfilEspecialidades)) {
                            ?>
                                <option value=<?php echo $dadosEspecialidades['id'] ?>><?php echo $dadosEspecialidades['nome'] ?></option>
                            <?php
                            }
                            ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-dark btn-block" style="background-color: black; border: 1px solid white;">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('./footer.php') ?>