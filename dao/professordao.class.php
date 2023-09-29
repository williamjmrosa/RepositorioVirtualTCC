<?php
require_once '../persistencia/conexaobanco.class.php';

class ProfessorDAO{
    
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

    //Cadastrar Professor
    public function cadastrarProfessor(Professor $professor){
        try{

            $EndDAO = new EnderecoDAO();

            $professor->end->idEndereco = $EndDAO->cadastrarEndereco($professor->end);

            $stat = $this->conexao->prepare("insert into professor(matricula,nome,rg,cpf,telefone,email,senha,idEndereco) values(?,?,?,?,?,?,?,?)");

            $professor->matricula = $this->gerarMatriculaNumerica($professor->end->idEndereco);

            $stat->bindValue(1,$professor->matricula);
            $stat->bindValue(2,$professor->nome);
            $stat->bindValue(3,$professor->rg);
            $stat->bindValue(4,$professor->cpf);
            $stat->bindValue(5,$professor->telefone);
            $stat->bindValue(6,$professor->email);
            $stat->bindValue(7,$professor->senha);
            $stat->bindValue(8,$professor->end->idEndereco);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

}
?>