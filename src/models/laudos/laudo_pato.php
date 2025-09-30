<?php
include_once('../../../config/db.php');
session_start();

use Dompdf\Dompdf;
use Dompdf\Options;
require '../../../config/vendor/autoload.php';

if (!isset($_POST['Paciente_id'])) {
    echo "<script>
            alert('Erro ao acessar o banco de dados');
            window.location.href = '../../pages/professor/visualizar_exames.php';
        </script>";
    exit();
}

$paciente_id = $_POST['Paciente_id'];
$usuario = $_SESSION['nome_cadastro'];

try {
    $query = "
        SELECT a.Assinaturas_id, a.Assinatura, a.CRO
        FROM Assinaturas AS a
        JOIN SolicitacaoCadastro AS s ON a.SolicitacaoCadastro_id = s.SolicitacaoCadastro_id
        WHERE s.Usuario = :Usuario";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $professor_data = $stmt->fetch(PDO::FETCH_ASSOC);

    $assinatura_base64 = $professor_data && $professor_data['Assinatura']
        ? base64_encode($professor_data['Assinatura'])
        : '';
    $cro = $professor_data['CRO'] ?? '';
    $assinaturas_id = $professor_data['Assinaturas_id'];

    $query =
            "SELECT P.*, DL.*, Ma.*, Mi.*, L.*, S.*
            FROM Paciente AS P
            LEFT JOIN DadosLesao AS DL ON P.Paciente_id = DL.Paciente_id
            LEFT JOIN Macroscopia AS Ma ON P.Paciente_id = Ma.Paciente_id
            LEFT JOIN Microscopia AS Mi ON P.Paciente_id = Mi.Paciente_id
            LEFT JOIN Laboratorio AS L ON P.Paciente_id = L.Paciente_id
            LEFT JOIN Sjogren AS S ON S.Microscopia_id = Mi.Microscopia_id
            WHERE P.Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $codigo_solicitacao = $resultado['CodigoSolicitacao'];
    $pdo->prepare("UPDATE SolicitacaoExame SET StatusSolicitacao = 'Liberado' WHERE CodigoSolicitacao = ?")->execute([$codigo_solicitacao]);
    $pdo->prepare("UPDATE Laboratorio SET Status = 'Liberado' WHERE Paciente_id = ?")->execute([$paciente_id]);

    $logo_ufmg_path = '../../../public/image/brasao-ufmg.png';
    if (file_exists($logo_ufmg_path)) {
        $image_data = base64_encode(file_get_contents($logo_ufmg_path));
        $image_tipo = mime_content_type($logo_ufmg_path);
        $image_base64 = 'data:' . $image_tipo . ';base64,' . $image_data;
    } else {
        throw new Exception('Erro ao carregar logo UFMG');
    }

    $fragmentos_complemento = '';
    if($resultado['Fragmentos'] === '1' || $resultado['Fragmentos'] === '01') {
        $fragmentos_complemento = htmlspecialchars($resultado['Fragmentos']) . ' fragmento';
    } else {
        $fragmentos_complemento = htmlspecialchars(strtolower($resultado['Fragmentos'])) . ' fragmentos';
    }

    $macroscopia_complemento = '';
    if (!empty($resultado['FragInclusao']) && ($resultado['FragInclusao'] === '1' || $resultado['FragInclusao'] === '01')) {
        $macroscopia_complemento = 'Foi enviado ' . htmlspecialchars($resultado['FragInclusao']) . ' fragmento para inclusão.';
    }
    elseif(!empty($resultado['FragInclusao']) && $resultado['FragInclusao'] !== '1' && $resultado['FragInclusao'] !== '01') {
        $macroscopia_complemento = 'Foram enviados ' . htmlspecialchars(strtolower($resultado['FragInclusao'])) . ' fragmentos para inclusão.';
    }
    elseif (!empty($resultado['FragDescalcificacao']) && ($resultado['FragDescalcificacao'] === '1' || $resultado['FragDescalcificacao'] === '01')) {
        $macroscopia_complemento = 'Foi enviado ' . htmlspecialchars($resultado['FragDescalcificacao']) . ' fragmento para descalcificação.';
    }
    elseif(!empty($resultado['FragDescalcificacao']) && $resultado['FragDescalcificacao'] !== '1' && $resultado['FragDescalcificacao'] !== '01') {
        $macroscopia_complemento = 'Foram enviados ' . htmlspecialchars(strtolower($resultado['FragDescalcificacao'])) . ' fragmentos para descalcificação.';
    }

    // Configurações DOMPFD
    $options = new Options();
    $options->set('defaultFont', 'times-roman');
    $dompdf = new Dompdf($options);

    // Sanitização das variáveis para usar no HTML
    $exameNum = htmlspecialchars($resultado['ExameNum']);
    $nomePaciente = htmlspecialchars($resultado['NomePaciente']);
    $dataNascimento = date('d/m/Y', strtotime($resultado['DataNascimento']));
    $solicitantePaciente = htmlspecialchars($resultado['SolicitantePaciente']);
    $procedenciaExame = htmlspecialchars($resultado['ProcedenciaExame']);
    $especificacaoProcedencia = htmlspecialchars($resultado['EspecificacaoExame']);
    $modoColeta = htmlspecialchars($resultado['ModoColeta']);
    $envolvimentoOsseo = htmlspecialchars($resultado['EnvolvimentoOsseo']);
    $localizacao = htmlspecialchars($resultado['Localizacao']);
    $diagnosticoClinico = htmlspecialchars($resultado['DiagnosticoClinico']);

    $fragmentos = strtolower(htmlspecialchars($resultado['Fragmentos']));
    $tipoFragmento = strtolower(htmlspecialchars($resultado['TipoFragmento']));
    $formato = strtolower(htmlspecialchars($resultado['Formato']));
    $superficie = strtolower(htmlspecialchars($resultado['Superficie']));
    $coloracao = strtolower(htmlspecialchars($resultado['ColoracaoMacro']));
    $consistencia = strtolower(htmlspecialchars($resultado['Consistencia']));
    $tamanhoMacro = strtolower(htmlspecialchars($resultado['TamanhoMacro']));
    $observacao = strtolower(htmlspecialchars($resultado['Observacao']));

    $microscopia = htmlspecialchars($resultado['Microscopia']);
    $nota = htmlspecialchars($resultado['Nota']);
    $diagnostico = htmlspecialchars($resultado['Diagnostico']);

    $procedencia_html = '';
    if($procedenciaExame === 'FAO-UFMG') {
        $procedencia_html = '<p><strong>Procedência: </strong>' . $procedenciaExame . ' - ' . $especificacaoProcedencia . '</p>';
    } else {
        $procedencia_html = '<p><strong>Procedência: </strong>' . $procedenciaExame . '</p>';
    }

    $macro_html = '';
    if($observacao === "revisão de lâmina") {
        $macro_html =
                '<div class="cytology">
                    <p>Revisão de Lâmina</p>
                </div>';
    } elseif($observacao != NULL && $observacao != 'revisão de lâmina' && $observacao != '') {
        $macro_html =
                '<div class="cytology">
                    <p>
                        O material recebido para exame consta de ' . $fragmentos_complemento . ' de ' . $tipoFragmento . ',
                        formato ' . $formato . ', superfície ' . $superficie . ', coloração ' . $coloracao . ', 
                        consistência ' . $consistencia . ', medindo ' . $tamanhoMacro . '. ' . $macroscopia_complemento . ' 
                        Observação: ' . $observacao .'.
                    </p>
                </div>';
    } else {
        $macro_html =
                '<div class="cytology">
                    <p>
                        O material recebido para exame consta de ' . $fragmentos_complemento . ' de ' . $tipoFragmento . ',
                        formato ' . $formato . ', superfície ' . $superficie . ', coloração ' . $coloracao . ', 
                        consistência ' . $consistencia . ', medindo ' . $tamanhoMacro . '. ' . $macroscopia_complemento . '
                    </p>
                </div>';
    }

    $sjogren = [
        'Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)',
        'Sialodenite crônica discreta e inespecífica'
    ];

    $micro_html = '';
    if(!in_array($resultado['Diagnostico'], $sjogren)) {
        $micro_html = 
                '<div class="cytology">
                    <p>' . $microscopia . '</p>
                </div>';
    } else {
        $micro_html = 
                '<div class="report-info">
                    <p>Os cortes histológicos mostram glândulas salivares menores com as seguintes características:</p>
                    <table>
                        <tr>
                            <td>
                                <p>1) Área total da amostra glandular: ' . $resultado['AreaAmostra'] . '</p>
                                <p>3) Focus score: ' . $resultado['FocusScore'] . '</p>
                            </td>
                            <td>
                                <p>2) Nº de focos observados: ' . $resultado['Focos'] . '</p>
                                <p>4) Grau de inflamação: ' . $resultado['GrauInflamacao'] . '</p>
                            </td>
                        </tr>
                    </table>
                    <p>5) Outros achados histopatológicos:</p>
                    <table>
                        <tr>
                            <td>
                                <p>- Centros germinativos: ' . $resultado['CentrosGerminativos'] . '</p>
                                <p>- Atrofia acinar: ' . $resultado['AtrofiaAcinar'] . '</p>
                                <p>- Dilatação acinar: ' . $resultado['DilatacaoAcinar'] . '</p>
                            </td>
                            <td>
                                <p>- Dilatação ductal: ' . $resultado['DilatacaoDuctal'] . '</p>
                                <p>- Fibrose: ' . $resultado['Fibrose'] . '</p>
                                <p>- Infiltração adiposa: ' . $resultado['InfiltracaoAdiposa'] . '</p>
                            </td>
                        </tr>
                    </table>
                </div>';
    }

    $diagnostico_html = '';
    if(!empty($nota)) {
        $diagnostico_html = '
            <h3>DIAGNÓSTICO:</h3>
            <div>
                <p style="font-size: 14px;">' . $diagnostico . ' </p>
                <p style="font-size: 14px;"><strong>Nota: </strong>' . $nota . '</p>
            </div>';
    } else {
        $diagnostico_html = '
            <h3>DIAGNÓSTICO:</h3>
            <div>
                <p>' . $diagnostico . '</p>
            </div>';
    }

    $query = 
            "SELECT
                L.DataLiberacao, A.Assinatura, A.CRO
            FROM 
                Laudos AS L
            JOIN
                Assinaturas AS A
            ON
                A.Assinaturas_id = L.Assinaturas_id
            WHERE
                L.Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);

    $nomeProfessor = htmlspecialchars($_SESSION['nome_cadastro']);
    $dataHora = ((new DateTime('now', new DateTimeZone('America/Sao_Paulo'))))->format('Y/m/d H:i:s');
    $dataHoraLaudo = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y H:i:s');
    $assinaturaImg = $assinatura_base64 ? '<img src="data:image/png;base64,' . $assinatura_base64 . '" alt="Assinatura" width="150px" />' : '';

    $html = <<<HTML
    <html>
        <head>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; line-height: 1.6; background-color: #ffffff; padding: 20px; }
                .container { width: 80%; margin: 0 auto; background-color: #fff; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); page-break-after: always; }
                header { display: flex; align-items: center; justify-content: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; }
                .logo { margin-right: 20px; }
                .logo img { width: 100px; height: auto; }
                .info-header { text-align: center; align-items: center; margin-top: -100px; margin-bottom: -30px; }
                .info-header h1 { font-size: 18px; font-weight: bold; }
                .info-header p { font-size: 12px; line-height: 1.4; }
                h2, h3 { margin-bottom: 10px; }
                .report-title { text-align: center; font-size: 15px; }
                .report-info, .cytology, .diagnosis { margin-bottom: 20px; }
                .report-info p, .cytology p, .diagnosis p { font-size: 14px; }
                .report-info table { width: 100%; border-collapse: collapse; }
                .report-info td { width: 50%; vertical-align: top; padding-right: 10px; }
                footer { text-align: right; font-size: 16px; font-style: italic; margin-top: 40px; }
                .signatures { display: flex; justify-content: space-between; margin-top: 40px; page-break-inside: avoid; }
                .signature-column { display: flex; flex-direction: column; justify-content: space-around; width: 30%; page-break-inside: avoid; }
                .signature-block { text-align: left; margin-bottom: 20px; page-break-inside: avoid; vertical-align: bottom; }
                .signature-block p { font-size: 12px; margin-bottom: 5px; margin-top: -10px; }
                .signature-block img { margin-bottom: -20px; }
            </style>
        </head>
        <body>
            <header>
                <div class="logo">
                    <img src="{$image_base64}" alt="Logo UFMG" width="100px" height="auto" />
                </div>
                <div class="info-header">
                    <h1>UNIVERSIDADE FEDERAL DE MINAS GERAIS</h1>
                    <p>LABORATÓRIO PATOLOGIA BUCOMAXILOFACIAL</p>
                    <p>FACULDADE DE ODONTOLOGIA</p>
                    <p>Av. Antônio Carlos, 6627 Sala 3202 Pampulha</p>
                    <p>Belo Horizonte-MG 31270-901 Fone: (31) 3409-2479</p>
                    <p>E-mail: patobucal@odonto.ufmg.br</p>
                </div>
                <br />
            </header>

            <h2 class="report-title">RELATÓRIO ANATOMOPATOLÓGICO</h2>

            <div class="report-info">
                <table>
                    <tr>
                        <td>
                            <p><strong>CÓDIGO:</strong> {$exameNum}</p>
                            <p><strong>Paciente:</strong> {$nomePaciente}</p>
                            <p><strong>Data de Nascimento:</strong> {$dataNascimento}</p>
                            <p><strong>Solicitante:</strong> {$solicitantePaciente}</p>
                            {$procedencia_html}
                        </td>
                        <td>
                            <p><strong>Modo de Coleta:</strong> {$modoColeta}</p>
                            <p><strong>Tipo de Lesão:</strong> {$envolvimentoOsseo}</p>
                            <p><strong>Localização:</strong> {$localizacao}</p>
                            <p><strong>Diagnóstico Clínico:</strong> {$diagnosticoClinico}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <h3>Macroscopia:</h3>
            {$macro_html}

            <h3>Microscopia:</h3>
            {$micro_html}

            {$diagnostico_html}

            <footer>
                <div class="signatures">
                    <div class="signature-column">
                        <div class="signature-block">
                            {$assinaturaImg}
                            <p>____________________</p>
                            <p><strong>Prof. Dr. {$nomeProfessor}</strong></p>
                            <p><strong>CRO: {$cro}</strong></p>
                            <p><em>Patologista</em></p>
                        </div>
                    </div>
                </div>
                <p>Laudo liberado em {$dataHoraLaudo} por {$nomeProfessor}</p>
            </footer>
        </body>
    </html>
HTML;


    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfOutput = $dompdf->output();

    $laudo_nome = 'Laudo ' . $resultado['NomePaciente'] . '.pdf';
    $laudo_tipo = 'application/pdf';
    $laudo_tamanho = strlen($pdfOutput);

    $query =
            "SELECT COUNT(*) AS Total FROM Laudos WHERE Paciente_id = :Paciente_id AND CodigoSolicitacao = :CodigoSolicitacao";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->bindParam(':CodigoSolicitacao', $codigo_solicitacao, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch();
    $total = $resultado['Total'];

    if($total == 1) {
        $query =
            "UPDATE
                Laudos
            SET
                Laudo = :Laudo,
                LaudoTamanho = :LaudoTamanho,
                DataLiberacao = :DataLiberacao
            WHERE
                Paciente_id = :Paciente_id AND CodigoSolicitacao = :CodigoSolicitacao";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Laudo', $pdfOutput, PDO::PARAM_LOB);
        $stmt->bindParam(':LaudoTamanho', $laudo_tamanho, PDO::PARAM_STR);
        $stmt->bindParam(':DataLiberacao', $dataHora, PDO::PARAM_STR);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        $stmt->bindParam(':CodigoSolicitacao', $codigo_solicitacao, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $query =
            "INSERT INTO Laudos(Laudo, LaudoNome, LaudoTipo, LaudoTamanho, CodigoSolicitacao, Paciente_id, DataLiberacao, Assinaturas_id)
            VALUES (:Laudo, :LaudoNome, :LaudoTipo, :LaudoTamanho, :CodigoSolicitacao, :Paciente_id, :DataLiberacao, :Assinaturas_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Laudo', $pdfOutput, PDO::PARAM_LOB);
        $stmt->bindParam(':LaudoNome', $laudo_nome, PDO::PARAM_STR);
        $stmt->bindParam(':LaudoTipo', $laudo_tipo, PDO::PARAM_STR);
        $stmt->bindParam(':LaudoTamanho', $laudo_tamanho, PDO::PARAM_STR);
        $stmt->bindParam(':CodigoSolicitacao', $codigo_solicitacao, PDO::PARAM_STR);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        $stmt->bindParam(':DataLiberacao', $dataHora, PDO::PARAM_STR);
        $stmt->bindParam(':Assinaturas_id', $assinaturas_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    echo "<script>
            alert('Laudo armazenado com sucesso.');
            window.location.href = '../../pages/professor/visualizar_exames.php';
        </script>";
    exit();

} catch(Exception $e) {
    echo "<script>
            alert('Erro ao processar o laudo: " . addslashes($e->getMessage()) . "');
            window.location.href = '../../pages/professor/visualizar_exames.php';
        </script>";
    exit();
}