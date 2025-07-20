<?php
session_start();
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';

if(isset($_SESSION['usuario'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);
}
?>
<li>
    <a class="btn fundo-secundario fw-bold m-1" href="<?php echo isset($user) ? '../visao/perfil.php' : '../visao/telaLogin.php'; ?>"><i class="bi bi-person-circle me-2"></i><?php echo isset($user) ? explode(' ', $user->nome)[0] : 'Login'; ?></a>
    <?php if(isset($user)) {?>
    <ul class="fundo-secundario p-2 fw-bold text-start">
        <?php if($tipo == 'Bibliotecario' || $tipo == 'Adm') {?>
        <li>
            <a href="../visao/telaCadastroCurso.php">Cadastrar Curso</a>
        </li>
        <li>
            <a href="../visao/telaCadastroCampus.php">Cadastrar Campus</a>
        </li>
        <li>
            <a href="../visao/telaCadastroCategoria.php">Cadastrar Categoria</a>
        </li>
        <li>
            <a href="../visao/telaCadastroAluno.php">Cadastrar Aluno</a>
        </li>
        <li>
            <a href="../visao/telaCadastroProfessor.php">Cadastrar Professor</a>
        </li>
        <li>
            <a href="../visao/telaCadastroBibliotecario.php">Cadastrar Bibliotecário</a>
        </li>
        <li>
            <a href="../visao/telaCadastroVisitante.php">Cadastrar Visitante</a>
        </li>
        <li>
            <a href="../visao/telaCadastroTCC.php">Cadastrar TCC</a>
        </li>
        <?php
        }
        if($tipo == 'Adm'){?>
        <li>
            <a href="../visao/telaCadastroAdm.php">Cadastrar Administrador</a>
        </li>
        <?php
        }?>
        <li>
            <a href="../controle/login-controle.php?OP=6">Logout <i class="bi bi-box-arrow-right"></i></a>
        </li>
    </ul>
    <?php
        }?>
</li>