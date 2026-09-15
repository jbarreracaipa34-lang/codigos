<?php
require "../../vendor/autoload.php";
require "../../modelo/UsuarioModelo.php";

use Dompdf\Dompdf;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

$nombre = "";
$documento = "";
$email = "";
$telefono = "";
$id = "";
$fotoBase64 = "";
$qrBase64 = "";

if (isset($_GET["user"])) {
    $objUsuario = UsuarioModelo::mdlCargarUsuario($_GET["user"]);
    if (isset($objUsuario["codigo"]) && $objUsuario["codigo"] == "200" && !empty($objUsuario["Usuario"])) {
        $nombre = $objUsuario["Usuario"]["nombre"];
        $documento = $objUsuario["Usuario"]["documento"];
        $urlFoto = $objUsuario["Usuario"]["url_foto"];
        $email = $objUsuario["Usuario"]["email"];
        $telefono = $objUsuario["Usuario"]["telefono"];
        $id = $objUsuario["Usuario"]["idusuario"];

        if (!empty($urlFoto)) {
            $rutaFotoFisica = realpath(__DIR__ . "/../../" . $urlFoto);
            if ($rutaFotoFisica && file_exists($rutaFotoFisica)) {
                $ext = strtolower(pathinfo($rutaFotoFisica, PATHINFO_EXTENSION));
                if ($ext === 'svg' || extension_loaded('gd')) {
                    $contenidoFoto = file_get_contents($rutaFotoFisica);
                    $fotoBase64 = 'data:image/' . ($ext === 'svg' ? 'svg+xml' : $ext) . ';base64,' . base64_encode($contenidoFoto);
                }
            }
        }

        $qrCode = new QrCode(
            data: $nombre . "," . $documento . "," . $email . "," . $telefono,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 150,
            margin: 5,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = extension_loaded('gd') ? new PngWriter() : new SvgWriter();
        $result = $writer->write($qrCode);
        $qrBase64 = $result->getDataUri();
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnet Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .carnet {
            width: 270px;
            height: 420px;
            border: 2px solid #0d6efd;
            padding: 15px;
            margin: auto;
            border-radius: 12px;
            box-sizing: border-box;
            text-align: center;
        }
        .header-carnet {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 8px 0;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        .header-carnet h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .foto-container {
            margin-bottom: 10px;
        }
        .foto {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #0d6efd;
        }
        .datos {
            margin-bottom: 10px;
            text-align: left;
            padding: 0 10px;
            font-size: 11px;
            line-height: 1.5;
        }
        .datos h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            text-align: center;
            color: #333333;
        }
        .datos p {
            margin: 2px 0;
            color: #444444;
        }
        .datos strong {
            color: #111111;
        }
        .qr-container {
            margin-top: 10px;
            text-align: center;
        }
        .qr-container img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="carnet">
        <div class="header-carnet">
            <h2>Carnet de Identificación</h2>
        </div>

        <div class="foto-container">
            <?php if (!empty($fotoBase64)): ?>
                <img class="foto" src="<?php echo $fotoBase64; ?>" alt="Foto">
            <?php endif; ?>
        </div>

        <div class="datos">
            <h3><?php echo htmlspecialchars($nombre); ?></h3>
            <hr style="border: 0; border-top: 1px solid #ddd; margin: 5px 0;">
            <p><strong>Documento:</strong> <?php echo htmlspecialchars($documento); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="qr-container">
            <?php if (!empty($qrBase64)): ?>
                <img src="<?php echo $qrBase64; ?>" alt="Código QR">
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);
$dompdf->setPaper(array(0, 0, 300, 480), 'portrait');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('carnet_' . $documento . '.pdf', array("Attachment" => false));
exit();
?>