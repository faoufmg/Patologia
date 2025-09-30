<?php
include_once("../../partials/header.php");
include_once('../../../config/db.php');
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

            <figure class="text-center">
                <h1>Banco de Dados Histopatologia e Citopatologia</h1>
            </figure>

            <div class="row justify-content-center">
                <div class="col">
                    <div class="row gy-2">

                        <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                            <a class="redirecionamento" href="../aluno/visualizar_cito.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/criar.svg" width="65px" height="65px" alt="Solicitar Exame">
                                    <p class="acoes text-center fw-bold">Visualizar Exame - Citologia</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                            <a class="redirecionamento" href="../aluno/visualizar_pato.php">
                                <div class="box d-flex flex-column align-items-center">
                                    <img src="../../../public/image/icons/listar.svg" width="65px" height="65px" alt="Visualizar Exame">
                                    <p class="acoes text-center fw-bold">Visualizar Exame - Patologia</p>
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
include_once("../../partials/footer.php");
?>