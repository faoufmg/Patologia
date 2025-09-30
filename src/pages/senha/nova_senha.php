<?php
$email = $_GET['email'];
?>

<!DOCTYPE html>
<html class="bd-login" lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Nova Senha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="#" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../public/css/login.css" /> <!-- Arquivo CSS personalizado -->
</head>

<body>
    <section class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5 login-container">
                <h2 class="text-center mb-4"><strong>Nova Senha</strong></h2>
                <form action="../../models/nova_senha.php" method="POST" onsubmit="return validarSenhas()">
                    <div class="form-group mb-3">
                        <label for="Email" class="form-label"><strong>E-mail</strong></label>
                        <input type="text" class="form-control" id="Email" value="<?php echo htmlspecialchars($email); ?>" name="Email" readonly required />
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <label for="NovaSenha" class="form-label"><strong>Nova Senha</strong></label>
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Digite a nova senha" id="NovaSenha" name="NovaSenha" required />
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha('NovaSenha')">👁</button>
                        </div>
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <label for="NovaSenhaRep" class="form-label"><strong>Repetir Senha</strong></label>
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Repita a senha" id="NovaSenhaRep" name="NovaSenhaRep" required />
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha('NovaSenhaRep')">👁</button>
                        </div>
                    </div>
                    <div id="mensagemErro" class="text-danger text-center mb-3" style="display: none;">As senhas não correspondem!</div>
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <p class="copy"><?php echo date('Y'); ?> © Copyright -
            <a href="https://www.odonto.ufmg.br" target="_blank">Faculdade de Odontologia da UFMG</a>
            <img src="/public/image/sti (3).png" style="height: 50px;" alt="Logo UFMG" class="img-footer">
        </p>    
    </footer>

    <script>
        function toggleSenha(id) {
            let input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }

        function validarSenhas() {
            let senha = document.getElementById("NovaSenha").value;
            let senhaRep = document.getElementById("NovaSenhaRep").value;
            let mensagemErro = document.getElementById("mensagemErro");
            
            if (senha !== senhaRep) {
                mensagemErro.style.display = "block";
                return false;
            }
            mensagemErro.style.display = "none";
            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>