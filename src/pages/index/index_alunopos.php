<?php
include_once('../../partials/header.php');
?>

<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>BDHC</title>
    <meta name="viewport" content="widht=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../../../image/favicon.png">
</head>

<body>

    <section class="principal-index bg-color-cinza">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col">
                    <div class="row gy-2">

                        <div class="col-md-6 col-lg-6 d-flex justify-content-center">
                            <a class="redirecionamento" href="../funcionario/dados_lesao.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/criar.svg" width="65px" height="65px" alt="Solicitar Exame">
                                    <p class="acoes text-center fw-bold">Dados da Lesão e Macroscopia</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-6 d-flex justify-content-center">
                            <a class="redirecionamento" href="../exames/micro.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/criar.svg" width="65px" height="65px" alt="Solicitar Exame">
                                    <p class="acoes text-center fw-bold">Microscopia</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-6 d-flex justify-content-center">
                            <a class="redirecionamento" href="../alunopos/visualizar_exames.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/listar.svg" width="65px" height="65px" alt="Visualizar Exame">
                                    <p class="acoes text-center fw-bold">Visualizar Exames</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-6 d-flex justify-content-center">
                            <a class="redirecionamento" href="../pesquisa.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/listar.svg" width="65px" height="65px" alt="Visualizar Exame">
                                    <p class="acoes text-center fw-bold">Pesquisa</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                            <a class="redirecionamento" href="../user/usuario_alunopos.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/editar.svg" width="65px" height="65px" alt="Área do Usuário">
                                    <p class="acoes text-center fw-bold">Área do Usuário</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

<?php
include_once('../../partials/footer.php');
?>