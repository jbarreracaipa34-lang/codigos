<?php
require "../../vendor/autoload.php";
require "../../modelo/productoModelo.php";

use Dompdf\Dompdf;

$descripcion = "";
$precio = "";
$stock = "";
$idProducto = "";
$barcodeOutput = "";

if (isset($_GET["idProducto"])) {
    $objProducto = ProductoModelo::mdlCargarProducto($_GET["idProducto"]);

    if (isset($objProducto["codigo"]) && $objProducto["codigo"] == "200" && !empty($objProducto["Producto"])) {
        $descripcion = $objProducto["Producto"]["descripcion"];
        $precio = $objProducto["Producto"]["precio"];
        $stock = $objProducto["Producto"]["stock"];
        $idProducto = $objProducto["Producto"]["idProducto"];

        $codigoTexto = $idProducto . "-" . $precio;

        if (extension_loaded('gd')) {
            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeData = $generator->getBarcode($codigoTexto, $generator::TYPE_CODE_128);
            $barcodeOutput = '<img src="data:image/png;base64,' . base64_encode($barcodeData) . '" alt="Código de Barras" style="width: 180px; height: 50px;">';
        } else {
            $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
            $svgData = $generator->getBarcode($codigoTexto, $generator::TYPE_CODE_128, 2, 50);
            $barcodeOutput = '<img src="data:image/svg+xml;base64,' . base64_encode($svgData) . '" alt="Código de Barras" style="width: 180px; height: 50px;">';
        }
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código de Barras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .panel {
            width: 220px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            margin: auto;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .mc-qr {
            margin-top: 5px;
        }
        .mc-qr img {
            width: 180px;
            height: 50px;
        }
        .mc-panel {
            margin-top: 8px;
            font-size: 12px;
        }
        .mc-panel p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="panel">
        <div class="mc-qr">
            <?php echo $barcodeOutput; ?>
        </div>
        <div class="mc-panel">
            <p><strong><?php echo htmlspecialchars($descripcion); ?></strong></p>
            <p>Precio: $<?php echo number_format((float)$precio, 2); ?> | Stock: <?php echo htmlspecialchars($stock); ?></p>
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
$dompdf->setPaper(array(0, 0, 260, 160), 'portrait');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('codigo_' . $idProducto . '.pdf', array("Attachment" => false));
exit();
?>