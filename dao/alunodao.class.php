<?php
require '../persistencia/conexaobanco.class.php';

class AlunoDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    //Cadastrar Aluno
    public function cadastrarAluno(Aluno $aluno){
        try{

            $stat = $this->conexao->prepare("insert into aluno(matricula,nome,rg,cpf,telefone,email,senha,idEndereco) values(null,?,?,?,?,?,?,?");

            $stat->bindValue(1,$aluno->nome);
            $stat->bindValue(2,$aluno->rg);
            $stat->bindValue(3,$aluno->cpf);
            $stat->bindValue(4,$aluno->telefone);
            $stat->bindValue(5,$aluno->email);
            $stat->bindValue(6,$aluno->senha);
            $stat->bindValue(7,$aluno->end->idEndereco);

            $stat->execute();

        }catch(PDOException $ex){
            return $ex->getMessage();
        }
    }

}
?>