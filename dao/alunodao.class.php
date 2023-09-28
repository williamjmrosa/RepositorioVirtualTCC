<?php
require_once '../persistencia/conexaobanco.class.php';

class AlunoDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Função para gerar uma matrícula numérica baseada em data e hora
    function gerarMatriculaNumerica(int $id) {
        // Obter o ano atual
        $ano = date('Y');
        // Obter o mes atual
        $mes = date('n');  
        // Obter o semestre atual (1 para primeiro semestre, 2 para segundo semestre)
        $semestre = ($mes <= 6) ? 1 : 2;
        // Obter o dia atual
        $dia = date('d');
    
        // Gerar a matrícula com base nos valores obtidos
        $matricula = $ano . $semestre . $mes . $dia . $id;

        return $matricula;
    }

    //Cadastrar Aluno
    public function cadastrarAluno(Aluno $aluno){
        try{

            $EndDAO = new EnderecoDAO();

            $aluno->end->idEndereco = $EndDAO->cadastrarEndereco($aluno->end);
            
            $stat = $this->conexao->prepare("insert into aluno(matricula,nome,rg,cpf,telefone,email,senha,idEndereco,campus,curso) values(?,?,?,?,?,?,?,?,?,?)");

            $aluno->matricula = $this->gerarMatriculaNumerica($aluno->end->idEndereco);

            $stat->bindValue(1,$aluno->matricula);
            $stat->bindValue(2,$aluno->nome);
            $stat->bindValue(3,$aluno->rg);
            $stat->bindValue(4,$aluno->cpf);
            $stat->bindValue(5,$aluno->telefone);
            $stat->bindValue(6,$aluno->email);
            $stat->bindValue(7,$aluno->senha);
            $stat->bindValue(8,$aluno->end->idEndereco);
            $stat->bindValue(9,$aluno->campus);
            $stat->bindValue(10,$aluno->curso);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

}
?>