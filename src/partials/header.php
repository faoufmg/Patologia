<?php

$nome = getenv("Shib-Person-CommonName");
include_once(dirname(__DIR__) . "/models/verifica.php");

$usuario = $_SESSION['nome_cadastro'];

try {

	$query =
		"SELECT
				*
		FROM
			SolicitacaoCadastro
		WHERE
			Usuario = :Usuario";
	$stmt = $pdo->prepare($query);
	$stmt->bindParam(':Usuario', $usuario, PDO::PARAM_STR);
	$stmt->execute();
	
	$resultado = $stmt->fetch();
	$cargo = $resultado['Cargo'];
} catch (Exception $e) {
	echo
	"<script>
			alert('Erro ao acessar o banco de dados.');
		</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<title>BDHC - FAO UFMG</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="../../../public/image/favicon.png" type="image/x-icon" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />

	<!-- DataTables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">

	<!-- Estilos customizados -->
	<link rel="stylesheet" href="../../../public/css/css.css" />
	<link rel="stylesheet" href="../../../public/css/footer.css" />
</head>

<body>

	<header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg">
			<?php if ($cargo === 'dentista'): ?>
				<a class="logo-laranjal-fao" href="/src/pages/index/index_dentista.php">
					<img class="img-logo-header" style="width: 200px; height: 50px;" src="/public/image/logo-bdhc.png" alt="Logo BDHC">
				</a>
			<?php endif; ?>
			<?php if ($cargo === 'funcionário' || $cargo === 'funcionário_dev'): ?>
				<a class="logo-laranjal-fao" href="/src/pages/index/index_funcionario.php">
					<img class="img-logo-header" style="width: 200px; height: 50px;" src="/public/image/logo-bdhc.png" alt="Logo BDHC">
				</a>
			<?php endif; ?>
			<?php if ($cargo === 'professor' || $cargo === 'professor_dev'): ?>
				<a class="logo-laranjal-fao" href="/src/pages/index/index_professor.php">
					<img class="img-logo-header" style="width: 200px; height: 50px;" src="/public/image/logo-bdhc.png" alt="Logo BDHC">
				</a>
			<?php endif; ?>
			<?php if ($cargo === 'alunopos' || $cargo === 'alunopos_dev'): ?>
				<a class="logo-laranjal-fao" href="/src/pages/index/index_alunopos.php">
					<img class="img-logo-header" style="width: 200px; height: 50px;" src="/public/image/logo-bdhc.png" alt="Logo BDHC">
				</a>
			<?php endif; ?>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault"
				aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<?php if ($cargo === 'dentista'): ?>
					<ul class="navbar-nav me-auto">
						<li class="nav-item active"><a class="nav-link" href="/src/pages/dentista/solicitar_exame.php">Solicitar Exame</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/dentista/visualizar_exames.php">Visualizar</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/user/usuario_dentista.php">Área do Usuário</a></li>
					</ul>
				<?php endif; ?>

				<?php if ($cargo === 'funcionário' || $cargo === 'funcionário_dev'): ?>
					<ul class="navbar-nav me-auto">
						<li class="nav-item active"><a class="nav-link" href="/src/pages/funcionario/cadastro_exame.php">Novo Exame</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/funcionario/dados_lesao.php">Dados da Lesão</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/exames/macro.php">Macroscopia</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/exames/micro.php">Microscopia</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/funcionario/visualizar_exames.php">Visualizar</a></li>
						<!-- <li class="nav-item active"><a class="nav-link" href="/src/pages/pesquisa.php">Pesquisa</a></li> -->
						<!-- <li class="nav-item active"><a class="nav-link" href="/src/pages/funcionario/aprovar.php">Aprovar</a></li> -->
						<!-- <li class="nav-item active"><a class="nav-link" href="/src/pages/funcionario/aprovar_aluno.php">Alunos</a></li> -->
						<!-- <li class="nav-item active"><a class="nav-link" href="/src/pages/user/usuario_funcionario.php">Área do Usuário</a></li> -->
					</ul>
				<?php endif; ?>

				<?php if ($cargo === 'professor' || $cargo === 'professor_dev'): ?>
					<ul class="navbar-nav me-auto">
						<li class="nav-item active"><a class="nav-link" href="/src/pages/exames/macro.php">Macroscopia</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/exames/micro.php">Microscopia</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/professor/visualizar_exames.php">Visualizar</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/pesquisa.php">Pesquisa</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/user/usuario_professor.php">Área do Usuário</a></li>
					</ul>
				<?php endif; ?>

				<?php if ($cargo === 'alunopos' || $cargo === 'alunopos_dev'): ?>
					<ul class="navbar-nav me-auto">
						<li class="nav-item active"><a class="nav-link" href="/src/pages/funcionario/dados_lesao.php">Dados da Lesão</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/exames/macro.php">Macroscopia</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/exames/micro.php">Microscopia</a></li>
						<li class="nav-item active"><a class="nav-link" href="/src/pages/alunopos/visualizar_exames.php">Visualizar</a></li>
						<!-- <li class="nav-item active"><a class="nav-link" href="/src/pages/pesquisa.php">Pesquisa</a></li> -->
						<!-- <li class="nav-item active"><a class="nav-link" href="/src/pages/user/usuario_alunopos.php">Área do Usuário</a></li> -->
					</ul>
				<?php endif; ?>
			</div>
			<div>
				<ul class="nav justify-content-end">
					<li class="nav-item dropdown">
					<li class="nav-item"><strong>Usuário: </strong></li>
					<li class="nav-item"><strong><a
								class="redirecionamento"><?php echo ($_SESSION["nome_view"]); ?></a></strong></li>
					</li>
					<li><a href="/src/models/logout.php" title="Sair"><i class="fas fa-sign-out-alt"></i></a></li>
				</ul>
			</div>
		</nav>
	</header>

</body>

</html>