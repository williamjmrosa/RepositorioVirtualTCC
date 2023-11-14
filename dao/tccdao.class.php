<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../Modelo/Aluno.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/curso.class.php';

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

            }

            return true;

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

            }

            return true;

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

    // Listar TCC com paginação
    public function listarTCC($paginaAtual){
        try{

            $con = $this->conexao->prepare("Select count(*) as total from tcc");

            $con->execute();

            $totalRegistros = $con->fetch(PDO::FETCH_ASSOC)['total'];

            $registrosPorPagina = 2;

            $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

            if($paginaAtual > $totalPaginas){
                $paginaAtual = $totalPaginas;
            }

            $offset = ($paginaAtual - 1) * $registrosPorPagina;

            $_SESSION['totalPaginas'] = $totalPaginas;

            $con->closeCursor();

            $stat = $this->conexao->prepare("Select * from tcc LIMIT :offset,:registrosPorPagina");

            $stat->bindValue(':offset',$offset,PDO::PARAM_INT);

            $stat->bindValue(':registrosPorPagina',$registrosPorPagina,PDO::PARAM_INT);

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'TCC');

            $array = $this->listarOrientador($array);

            $array = $this->listarCategorias($array);

            $array = $this->buscarAlunoPorTCC($array);

            for($i = 0; $i < count($array); $i++){
                
                $array[$i]->campus = $this->buscarCampusPorTCC($array[$i]->idCampus)[0];
                $array[$i]->curso = $this->buscarCursoPorTCC($array[$i]->idCurso)[0];
            }

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            //$this->gerenciarErros($ex->getMessage());
        }


    }

    // Listar TCC todos os registros
    public function listarTodosTCC(){
        try{

            $stat = $this->conexao->prepare("Select * from tcc");

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'TCC');

            $array = $this->buscarAlunoPorTCC($array);

            return $array;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            return false;
        }
           
    }

    // Listar Orientador do TCC
    public function listarOrientador($lista){
        try{

            for($i = 0; $i < count($lista); $i++){

                $stat = $this->conexao->prepare("Select p.nome from orientador as o inner join professor as p on o.matricula = p.matricula where o.idTCC = ?");
                $stat->bindValue(1,$lista[$i]->idTCC);
                $stat->execute();

                $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Professor');

                $lista[$i]->orientador = $array;

            }

            return $lista;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
        }
        
    }

    // Listar Categorias do TCC
    public function listarCategorias($lista){
        try{
            for($i = 0; $i < count($lista); $i++){

                $stat = $this->conexao->prepare("Select ca.nome from categorias as c inner join categoria as ca on c.idCategoria = ca.idCategoria where c.idTCC = ?");
                $stat->bindValue(1,$lista[$i]->idTCC);
                $stat->execute();

                $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

                $lista[$i]->categorias = $array;

            }

            return $lista;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
        }

    }

    // Buscar Aluno do TCC
    public function buscarAlunoPorTCC($lista){
        try{

            for($i = 0; $i < count($lista); $i++){

            $stat = $this->conexao->prepare("Select a.nome from tcc as t inner join aluno as a on t.matricula = a.matricula where t.idTCC = ?");
            $stat->bindValue(1,$lista[$i]->idTCC);
            $stat->execute();

            $aluno = $stat->fetchAll(PDO::FETCH_CLASS, 'Aluno');
            
        
            $lista[$i]->aluno = $aluno[0];
        }

            return $lista;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
        }
    }

    // Buscar TCC por id
    public function buscarTCCID($id,$alterar = false){
        try{
            $stat = $this->conexao->prepare("Select * from tcc where idTCC = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            $array = $stat->fetch(PDO::FETCH_ASSOC);

            if($alterar){
                $stat = $this->conexao->prepare("Select matricula, nome, email, campus, curso, rg, cpf, telefone from aluno where matricula = ?");
                $stat->bindValue(1,$array['matricula']);
                $stat->execute();
                $array['aluno'] = $stat->fetch(PDO::FETCH_ASSOC);
                $stat = $this->conexao->prepare("Select matricula from orientador where idTCC = ?");
                $stat->bindValue(1,$array['idTCC']);
                $stat->execute();
                $array['orientador'] = $stat->fetchAll(PDO::FETCH_ASSOC);
                $stat = $this->conexao->prepare("Select idCategoria from categorias where idTCC = ?");
                $stat->bindValue(1,$array['idTCC']);
                $stat->execute();
                $array['categorias'] = $stat->fetchAll(PDO::FETCH_ASSOC);
                $array['campus'] = get_object_vars($this->buscarCampusPorTCC($array['idCampus'])[0]);
                $array['curso'] = get_object_vars($this->buscarCursoPorTCC($array['idCurso'])[0]);
            }

            return $array;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());

        }
    }

    // Buscar TCC por tipo
    public function buscarTCCPorTipo($busca,$tipo){
        
        try{

            $stat = $this->conexao->prepare("Select t.idTCC, t.titulo ,a.nome from tcc as t inner join aluno as a on t.matricula = a.matricula where $tipo like ?");

            $stat->bindValue(1,$busca.'%');

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'TCC');
            
            $array = $this->buscarAlunoPorTCC($array);

            return $array;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
            return false;
        }

    }

    // Buscar TCC por filtros
    function buscarTCCs($valorCurso = null, $valorCampus = null, $valorTitulo = null, $valorCategoria = null, $valorNomeAluno = null, $valorNomeProfessor = null, $paginaAtual = 1) {
        
        try {
            // Construção da consulta SQL
            $sql = "SELECT t.idTCC, t.titulo, t.descricao, t.localPDF, t.idCurso, t.idCampus, t.matricula
                    FROM tcc AS t
                    INNER JOIN categorias AS cs ON t.idTCC = cs.idTCC
                    INNER JOIN aluno AS a ON t.matricula = a.matricula
                    INNER JOIN orientador AS o ON t.idTCC = o.idTCC
                    INNER JOIN professor AS p ON o.matricula = p.matricula
                    WHERE 1 = 1";
        
            $params = array(); // Array para armazenar os parâmetros
        
            // Verificação e adição das cláusulas WHERE condicionalmente
            if (!empty($valorCurso)) {
                $sql .= " AND t.idCurso = :valorCurso";
                $params[':valorCurso'] = $valorCurso;
            }
            if (!empty($valorCampus)) {
                $sql .= " AND t.idCampus = :valorCampus";
                $params[':valorCampus'] = $valorCampus;
            }
            if (!empty($valorTitulo)) {
                $sql .= " AND t.titulo LIKE :valorTitulo";
                $params[':valorTitulo'] = "%" . $valorTitulo."%";
            }
            if (!empty($valorNomeAluno)) {
                $sql .= " AND a.nome LIKE :valorNomeAluno";
                $params[':valorNomeAluno'] = "%".$valorNomeAluno."%";
            }
            if (!empty($valorNomeProfessor)) {
                $sql .= " AND p.nome LIKE :valorNomeProfessor";
                $params[':valorNomeProfessor'] = "%".$valorNomeProfessor."%";
            }
            if (!empty($valorCategoria) && is_array($valorCategoria) && count($valorCategoria) > 0) {
                $sql .= " AND cs.idCategoria IN (";
                $placeholders = array();
                foreach ($valorCategoria as $categoria) {
                    $paramName = ':valorCategoria' . count($params);
                    $placeholders[] = $paramName;
                    $params[$paramName] = $categoria;
                }
                $sql .= implode(', ', $placeholders);
                $sql .= ") GROUP BY t.idTCC HAVING COUNT(DISTINCT cs.idCategoria) = " . count($valorCategoria);
            } else {
                $sql .= " GROUP BY t.idTCC";
            }

            $stat = $this->conexao->prepare($sql);
            $stat->execute($params);

            $totalRegistros = $stat->rowCount();
            //$stat->debugDumpParams();
            $registrosPorPagina = 2;

            $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

            $_SESSION['totalPaginas'] = $totalPaginas;

            if($paginaAtual > $totalPaginas){
                $paginaAtual = $totalPaginas;
            }

            $offset = ($paginaAtual - 1) * $registrosPorPagina;

            $sql .= " LIMIT $offset,$registrosPorPagina";
            
            
            // Execução da consulta com os parâmetros
            $stat = $this->conexao->prepare($sql);
            $stat->execute($params);
        


            // Obtenção dos resultados
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'TCC');

            $array = $this->listarOrientador($array);

            $array = $this->listarCategorias($array);

            $array = $this->buscarAlunoPorTCC($array);

            

            for($i = 0; $i < count($array); $i++){
                
                $array[$i]->campus = $this->buscarCampusPorTCC($array[$i]->idCampus)[0];
                $array[$i]->curso = $this->buscarCursoPorTCC($array[$i]->idCurso)[0];
            }


            
            return $array; 

        } catch (PDOException $e) {
            echo $stat->debugDumpParams();
            echo $e->getMessage();
        }
    }//fecha Buscar TCC por filtros

    // Buscar Campus de um TCC
    public function buscarCampusPorTCC($id){
        try{
            $sql = "SELECT * FROM campus WHERE idCampus = ?";

            $stat = $this->conexao->prepare($sql);

            $stat->bindValue(1,$id);

            $stat->execute(array($id));

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Campus');

            return $array;
        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
        }
    }

    // Buscar Curso de um TCC
    public function buscarCursoPorTCC($id){
        try{
            $sql = "SELECT * FROM curso WHERE idCurso = ?";

            $stat = $this->conexao->prepare($sql);

            $stat->bindValue(1,$id);

            $stat->execute(array($id));

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');

            return $array;

        }catch(PDOException $ex){
            $this->gerenciarErros($ex->getMessage());
        }

    }

}
