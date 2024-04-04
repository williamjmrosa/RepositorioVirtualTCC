<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';

class IndicacaoDAO{
    
    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Indicação
    public function cadastrarIndicacao($idTCC, $idUsuario, $instituicao, $curso){
        try{
            if($this->foiIndicado($idTCC, $idUsuario, $instituicao, $curso)){
                return false;
            }else{
                $stat = $this->conexao->prepare("insert into indicacao(idIndicacao,idTCC,matricula,idInstituicao,idCurso) values(null,?,?,?,?)");

                $stat->bindValue(1,$idTCC);
                $stat->bindValue(2,$idUsuario);
                $stat->bindValue(3,$instituicao);
                $stat->bindValue(4,$curso);

                $stat->execute();

                return true;
            }
        }catch(Exception $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Foi Indicado
    public function foiIndicado($idTCC, $idUsuario, $instituicao, $curso){
        try{
            $stat = $this->conexao->prepare("select count(*) from indicacao where idTCC = ? and matricula = ? and idInstituicao = ? and idCurso = ?");

            $stat->bindValue(1,$idTCC);
            $stat->bindValue(2,$idUsuario);
            $stat->bindValue(3,$instituicao);
            $stat->bindValue(4,$curso);

            $stat->execute();

            $res = $stat->fetch();

            if($res[0] > 0){
                return true;
            }else{
                return false;
            }
        }catch(Exception $ex){
            echo $ex->getMessage();
            return false;
        }
        
    }

}

?>