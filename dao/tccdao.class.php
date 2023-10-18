<?php
require_once '../persistencia/conexaobanco.class.php';

class TCCDAO{
    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    public function gerenciarErros($erro){
        if(isset($_SESSION['erro'])){
            $erros = array();
            $erros[] = $_SESSION['erro'];
            unset($_SESSION['erro']);
            $erros[] = $erro;
            $_SESSION['erros'] = serialize($erros);
        }elseif(isset($_SESSION['erros'])){
            $erros = unserialize($_SESSION['erros']);
            $erros[] = $erro;
            $_SESSION['erros'] = serialize($erros);
        }else{
            $_SESSION['erro'] = $erro;
        }
        
    }

    //Cadastrar TCC
    public function cadastrarTCC(TCC $tcc){
        try{
            $stat = $this->conexao->prepare("Insert into tcc(idTCC,titulo,descricao,idCurso,idCampus,matricula) values (null,?,?,?,?,?)");

            $stat->bindValue(1,$tcc->titulo);
            $stat->bindValue(2,$tcc->descricao);
            $stat->bindValue(3,$tcc->curso->idCurso);
            $stat->bindValue(4,$tcc->campus->idCampus);
            $stat->bindValue(5,$tcc->aluno->matricula);

            $stat->execute();

            $tcc->idTCC = $this->conexao->lastInsertId();

            if($this->cadastrarOrientadores($tcc) && $this->cadastrarCategorias($tcc)){
                
                return $tcc;
            }else{
                $this->deletarCategorias($tcc->idTCC);
                $this->deletarOrientador($tcc->idTCC);
                $this->deletarTCC($tcc->idTCC);
            }


        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

    //Update localPDF do TCC
    public function updateLocalPDF($idTcc, $localPDF){
        try{
            $stat = $this->conexao->prepare("Update tcc set localPDF = ? where idTCC = ?");
            
            $stat->bindValue(1,$localPDF);
            $stat->bindValue(2,$idTcc);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

    //Cadastrar Orientador do TCC
    public function cadastrarOrientadores(TCC $tcc){
        try{
            foreach($tcc->orientador as $v){
                $stat = $this->conexao->prepare("Insert into orientador(idTCC,matricula) values (?,?)");
            
                $stat->bindValue(1,$tcc->idTCC);
                $stat->bindValue(2,$v);
                
                $stat->execute();

                return true;
            }
        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

    //Cadastrar Categorias do TCC
    public function cadastrarCategorias(TCC $tcc){
        try{
            foreach($tcc->categorias as $v){
                $stat = $this->conexao->prepare("Insert into categorias(idCategoria,idTCC) values (?,?)");
            
                $stat->bindValue(1,$v);
                $stat->bindValue(2,$tcc->idTCC);
                
                $stat->execute();

                return true;

            }
        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

    // Deletar TCC
    public function deletarTCC($id){
        try{
            $stat = $this->conexao->prepare("Delete from tcc where idTCC = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            $_SESSION['erro'] =  $ex->getMessage();
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

    // Deletar Orientador do TCC
    public function deletarOrientador($id){
        try{
            $stat = $this->conexao->prepare("Delete from orientador where idTCC = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

    // Deletar Categorias do TCC
    public function deletarCategorias($id){
        try{
            $stat = $this->conexao->prepare("Delete from categorias where idTCC = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            //$_SESSION['erro'] =  $ex->getMessage();
        }
    }

}
?>