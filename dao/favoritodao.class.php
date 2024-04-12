<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';

class FavoritoDAO{
    
    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Favorito
    public function cadastrarFavorito($idUsuario, $idTcc,$tipo){
        
        try {
            if($this->verificarFavorito($idTcc) == false){
                
            
                $stat = $this->conexao->prepare("insert into favoritos(idFavorito,idTcc) values(null,?)");

                $stat->bindValue(1,$idTcc);

                $stat->execute();

                $idFavorito = $this->conexao->lastInsertId();

                if($idFavorito > 0 && $idFavorito != null){
                    switch($tipo){
                        case 'Aluno':
                            $stat = $this->conexao->prepare("insert into favorito_aluno(idFavorito,matricula) values(?,?)");
            
                        break;
                        case 'Professor':
                            $stat = $this->conexao->prepare("insert into favorito_professor(idFavorito,matricula) values(?,?)");
                        break;
                        case 'Visitante':
                            $stat = $this->conexao->prepare("insert into favorito_visitante(idFavorito,email) values(?,?)");
                        break;
                        default:
                            return false;

                    }

                    $stat->bindValue(1,$idFavorito);
                    $stat->bindValue(2,$idUsuario);

                    $stat->execute();

                    return true;
                }else{
                    return false;
                }
            }else{
                echo json_encode("Este favorito ja existe");
                return false;
            }

        }catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    //Remover Favorito
    public function removerFavorito($idUsuario, $idTcc, $tipo){
        try{
            if($this->verificarFavorito($idTcc) == true){
                switch($tipo){
                    case 'Aluno':
                        $tabela = 'favorito_aluno';

                    break;
                    case 'Professor':
                        $tabela = 'favorito_professor';

                    break;

                    case 'Visitante':
                        $tabela = 'favorito_visitante';

                    break;

                    default:
                        return false;
                }

                $stat = $this->conexao->prepare("SELECT f.idFavorito FROM $tabela as ft inner join favoritos as f on f.idFavorito = ft.idFavorito where ft.matricula = ? and f.idTCC = ?");

                $stat->bindValue(1,$idUsuario);
                $stat->bindValue(2,$idTcc);

                $stat->execute();

                $idFavorito = $stat->fetch(PDO::FETCH_ASSOC)['idFavorito'];

                $stat = $this->conexao->prepare("delete from $tabela where idFavorito = ?");

                $stat->bindValue(1,$idFavorito);

                $stat->execute();

                $stat = $this->conexao->prepare("delete from favoritos where idFavorito = ?");

                $stat->bindValue(1,$idFavorito);

                $stat->execute();

                return true;
            }
        }catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    //Listar Favoritos do Aluno
    public function listarFavoritosAluno($id){
        try{
            $stat = $this->conexao->prepare("SELECT * FROM tcc WHERE idTCC in (SELECT f.idTCC FROM favoritos as f INNER JOIN favorito_aluno as fa ON f.idFavorito = fa.idFavorito WHERE fa.matricula = ?)");
            
            $stat->bindValue(1,$id);
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'TCC');
            
            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }

    }

    //Verificar se o favorito existe
    public function verificarFavorito($idTcc){
        try{
            if(isset($_SESSION['usuario'])){
                $user = unserialize($_SESSION['usuario']);
                $tipo = get_class($user);
                
                switch($tipo){
                    case 'Aluno':
                        $stat = $this->conexao->prepare("select * from favorito_aluno as fa inner join favoritos as f on f.idFavorito = fa.idFavorito where fa.matricula = ? and f.idTCC = ?");
                        $idUsuario = $user->matricula;
                    break;
                    case 'Professor':
                        $stat = $this->conexao->prepare("select * from favorito_professor as fp inner join favoritos as f on f.idFavorito = fp.idFavorito where fp.matricula = ? and f.idTCC = ?");
                        $idUsuario = $user->matricula;
                    break;
                    case 'Visitante':
                        $stat = $this->conexao->prepare("select * from favorito_visitante as fv inner join favoritos as f on f.idFavorito = fv.idFavorito where fv.email = ? and f.idTCC = ?");
                        $idUsuario = $user->email;
                        
                    break;
                    default:
                        return false;
                }

            }else{
                return false;
            }

            $stat->bindValue(1,$idUsuario);
            $stat->bindValue(2,$idTcc);

            $stat->execute();

            $result = $stat->fetch();

            if($result){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }
}
?>