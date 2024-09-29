<?php
include '../conexao.php';
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

if ($acao == 'cadastraOuEditaEspecialista') {

    $email = pg_escape_string($_POST['email']);
    $senha = md5($_POST['senha']);
    $perfilEspecialidade = (int)$_POST['id_perfil']; 

    if (!$_POST['id']) {
   
        $sql = "INSERT INTO usuarios (email, senha, perfil_id) VALUES 
        ('$email', '$senha', $perfilEspecialidade)";

        if (pg_query($conn, $sql)) {
            echo "<script> alert('Especialista cadastrado com sucesso!');
            window.location.href = '../../front/pages/especialistas.php';
            </script>";
        } else {
            echo "Não foi possível cadastrar: " . pg_last_error($conn);
        }
    } else {
        
        $id = (int)$_POST['id']; 

        $sql = "UPDATE usuarios SET email='$email', senha='$senha', perfil_id=$perfilEspecialidade WHERE id = $id";

        if (pg_query($conn, $sql)) {
            echo "<script> alert('Especialista editado com sucesso!');
            window.location.href = '../../front/pages/especialistas.php';
            </script>";
            exit;
        } else {
            echo "Não foi possível editar: " . pg_last_error($conn);
        }
    }
}
