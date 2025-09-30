<?php

    include_once(__DIR__ . '/../../config/db.php');

    // Inicia sessões
    session_start();

    // Verifica se existe os dados da sessão de login
    if(!isset($_SESSION["nome_cadastro"]))
    {
    // Usuário não logado! Redireciona para a página de login
    echo
    "<script>
        window.location.href = '/src/pages/login.php';
    </script>";
    }

    try{

        // Verifica se o aluno ainda tem direito ao acesso
        // $aluno = $_SESSION['nome_cadastro'];

        // $sqlAluno = "SELECT status FROM AcessoAluno WHERE NomeAluno = :NomeAluno";
        
        // $stmt = $pdo->prepare($sqlAluno);
        // $stmt->bindParam(':NomeAluno', $aluno, PDO::PARAM_STR);
        // $stmt->execute();

        // $results = $stmt->fetchAll();
        // $status = $results['status'];

        // if($status == 'revogado'){
        //     echo
        //         "<script>
        //             alert('Seu acesso foi revogado.');
        //             window.location.href = '/src/login.php';
        //         </script>";
        // }

        $usuario = $_SESSION['nome_cadastro'];

        $query = "SELECT * FROM SolicitacaoCadastro WHERE Usuario = :Usuario";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['Email'] = $resultado['Email'];

        // Verifica o cargo do usuário
        // Define uma lista de cargos permitidos para acessar cada página
        $cargo_usuario = $_SESSION['cargo'];
        $pagina_atual = basename($_SERVER['PHP_SELF']);

        // Define as permissões de acesso por página
        $permissoes = [
            "index.php" => ["adm", "aluno", "funcionário", "professor", "dentista"],
            "index_professor.php" => ["professor"],
            "dados_paciente.php" => ["adm", "dentista"],
            "dados_lesao.php" => ["adm", "dentista"],
            "macro.php" => ["adm", "funcionário", "aluno", "professor"],
            "micro.php" => ["adm", "funcionário", "aluno", "professor"],
            "aprovar.php" => ["adm", "funcionário"],
            "solicitar_exame.php" => ["adm", "dentista"],
            "editar_macro.php" => ["adm", "professor", "funcionário"],
            "editar_micro.php" => ["adm", "professor", "funcionário"],
            "/src/pages/user/usuario_dentista.php" => ["adm", "dentista"],
            // Adicione outras páginas e os cargos que podem acessá-las
        ];

        // Verifica se o cargo do usuário tem permissão para acessar a página atual
        if(isset($permissoes[$pagina_atual]) && !in_array($cargo_usuario, $permissoes[$pagina_atual])){
            echo
                "<script>
                    alert('Você não tem permissão para acessar essa página!');
                    window.location.href = /src/index.php;
                </script>";
        }

    } catch (Exception $e){
        echo
        "<script>
            alert('" . $e->getMessage() . "');
            window.location.href = '../login.php';
        </script>";
    }

?>