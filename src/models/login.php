<?php

require_once("../../config/db.php");

session_start();

// 2. Coleta e Limpeza dos Dados de Entrada
$email = isset($_POST["email_cadastro"]) ? trim($_POST["email_cadastro"]) : null;
$senha = isset($_POST["senha_cadastro"]) ? trim($_POST["senha_cadastro"]) : null;

// Verifica se e-mail ou senha estão vazios.
if (!$email || !$senha) {
    echo 
        "<script>
            alert('Você deve digitar seu e-mail e senha!');
            window.location.href = '/src/pages/login.php';
        </script>";
    exit;
}

try {
    // 3. Consulta ao Banco de Dados
    $stmt = $pdo->prepare("SELECT * FROM SolicitacaoCadastro WHERE Email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user['status'] === 'revogado' || $user['status'] === 'rejeitada') {
        echo
            "<script>
                alert('Você não possui acesso ao sistema.');
                window.location.href = '/src/pages/login.php';
            </script>";
        exit;
    }

    // 4. Lógica de Autenticação 
    if ($user && ($user['senha_bcrypt'] !== NULL)) {
        
        if(password_verify($senha, $user['senha_bcrypt'])) {
            // 5. Regeneração do ID da Sessão (Segurança)
            session_regenerate_id(true);

            $nome = $user['Usuario'];
            $nomes_separados = explode(" ", $nome);
            $primeiro_nome = $nomes_separados[0];

            // Armazena informações do usuário na sessão.
            $_SESSION['nome_cadastro'] = $user['Usuario'];
            $_SESSION['nome_view'] = $primeiro_nome;
            $_SESSION['cargo'] = $user['Cargo'];

            // 6. Redirecionamento 
            $redirectMap = [
                'dentista'    => '/src/pages/index/index_dentista.php',
                'funcionário' => '/src/pages/index/index_funcionario.php',
                'funcionário_dev' => '/src/pages/index/index_funcionario.php',
                'professor'   => '/src/pages/index/index_professor.php',
                'professor_dev'   => '/src/pages/index/index_professor.php',
                'aluno'       => '/src/pages/index/index_aluno.php',
                'alunopos'    => '/src/pages/index/index_alunopos.php',
                'alunopos_dev'    => '/src/pages/index/index_alunopos.php',
                'adm'         => '/src/pages/index/index.php',
            ];

            // Obtém o caminho de redirecionamento do mapa.
            $redirectTo = $redirectMap[$user['Cargo']] ?? null;

            if ($redirectTo) {
                echo 
                    "<script>
                        window.location.href = '{$redirectTo}';
                    </script>";
                exit;
            } else {
                // Se o cargo não for reconhecido, nega o acesso.
                echo 
                    '<script>
                        alert("Acesso negado.");
                        window.location.href = "/src/pages/login.php";
                    </script>';
                exit;
            }
        } else {

            $redirectMap = [
                'dentista'    => '/src/pages/index/index_dentista.php',
                'funcionário' => '/src/pages/index/index_funcionario.php',
                'funcionário_dev' => '/src/pages/index/index_funcionario.php',
                'professor'   => '/src/pages/index/index_professor.php',
                'professor_dev'   => '/src/pages/index/index_professor.php',
                'aluno'       => '/src/pages/index/index_aluno.php',
                'alunopos'    => '/src/pages/index/index_alunopos.php',
                'alunopos_dev'    => '/src/pages/index/index_alunopos.php',
                'adm'         => '/src/pages/index/index.php',
            ];

            $redirectTo = $redirectMap[$user['Cargo']] ?? null;

            echo
                "<script>
                    alert('Usuário ou senha inválidos. Tente novamente.');
                    window.location.href = '{$redirectTo}';
                </script>";
        }

    } elseif($user && ($user['senha_bcrypt'] === NULL)) {

        if(md5($senha) === $user['senha']) {
            $options = [
                'cost' => 12
            ];
            $senha_criptografada = password_hash($senha, PASSWORD_BCRYPT, $options);

            $query = "UPDATE SolicitacaoCadastro SET senha_bcrypt = :senha_bcrypt WHERE SolicitacaoCadastro_id = :SolicitacaoCadastro_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':senha_bcrypt', $senha_criptografada, PDO::PARAM_STR);
            $stmt->bindParam(':SolicitacaoCadastro_id', $user['SolicitacaoCadastro_id'], PDO::PARAM_INT);
            $stmt->execute();

            session_regenerate_id(true);

            $nome = $user['Usuario'];
            $nomes_separados = explode(" ", $nome);
            $primeiro_nome = $nomes_separados[0];

            // Armazena informações do usuário na sessão.
            $_SESSION['nome_cadastro'] = $user['Usuario'];
            $_SESSION['nome_view'] = $primeiro_nome;
            $_SESSION['usuario'] = $user['Usuario'];
            $_SESSION['cargo'] = $user['Cargo'];

            $redirectMap = [
                'dentista'    => '/src/pages/index/index_dentista.php',
                'funcionário' => '/src/pages/index/index_funcionario.php',
                'funcionário_dev' => '/src/pages/index/index_funcionario.php',
                'professor'   => '/src/pages/index/index_professor.php',
                'professor_dev'   => '/src/pages/index/index_professor.php',
                'aluno'       => '/src/pages/index/index_aluno.php',
                'alunopos'    => '/src/pages/index/index_alunopos.php',
                'alunopos_dev'    => '/src/pages/index/index_alunopos.php',
                'adm'         => '/src/pages/index/index.php',
            ];

            $redirectTo = $redirectMap[$user['Cargo']] ?? null;

            if ($redirectTo) {
                echo 
                    '<script>
                        window.location.href = "' . $redirectTo . '";
                    </script>';
                exit;
            } else {
                echo 
                    '<script>
                        alert("Acesso negado.");
                        window.location.href = "/src/pages/login.php";
                    </script>';
                exit;
            }
        } else {
            echo 
                '<script>
                    alert("E-mail ou senha inválidos.");
                    window.location.href = "/src/pages/login.php";
                </script>';
            exit;
        }

    } else {
        // 7. Mensagem de Erro 
        echo 
            '<script>
                alert("E-mail ou senha inválidos.");
                window.location.href = "/src/pages/login.php";
            </script>';
        exit;
    }

} catch (PDOException $e) {
    // 8. Tratamento de Erros de Conexão
    error_log("Erro no banco de dados: " . $e->getMessage());
    
    echo 
        '<script>
            alert("Ocorreu um erro no servidor.");
            window.location.href = "/src/pages/login.php";
        </script>';
    exit;
}
?>