<?php
require __DIR__ . '/vendor/autoload.php'; // Cargar Composer
use setasign\Fpdi\Fpdi;

// Conectar a la base de datos
$config = include '/data/config.php';
$dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
$conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);

// Obtener datos de la persona y la venta
$persona = $conexion->query("SELECT * FROM persona LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$venta = [
    ["producto" => "New Balance 574", "precio" => 152000, "cantidad" => 1, "subtotal" => 152000],
    ["producto" => "Converse Chuck Taylor", "precio" => 130000, "cantidad" => 2, "subtotal" => 260000],
    ["producto" => "Reebok Classic Leather", "precio" => 140000, "cantidad" => 2, "subtotal" => 280000]
];

$total = array_sum(array_column($venta, "subtotal"));

// Cargar plantilla de factura
$pdf = new Fpdi();
$pdf->AddPage();
$pdf->setSourceFile("Factura_Blanco.pdf");
$template = $pdf->importPage(1);
$pdf->useTemplate($template);

// Agregar datos a la factura
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(50, 40);
$pdf->Cell(0, 10, "Factura #00001");
$pdf->SetXY(50, 50);
$pdf->Cell(0, 10, "Fecha: " . date("Y-m-d"));
$pdf->SetXY(50, 60);
$pdf->Cell(0, 10, "Cliente: " . $persona['Nombre'] . " " . $persona['Apellido']);
$pdf->SetXY(50, 70);
$pdf->Cell(0, 10, "Documento: " . $persona['Documento']);

$y = 90;
foreach ($venta as $item) {
    $pdf->SetXY(30, $y);
    $pdf->Cell(50, 10, $item['producto']);
    $pdf->SetXY(90, $y);
    $pdf->Cell(30, 10, "$" . number_format($item['precio'], 0, ',', '.'));
    $pdf->SetXY(130, $y);
    $pdf->Cell(30, 10, $item['cantidad']);
    $pdf->SetXY(160, $y);
    $pdf->Cell(30, 10, "$" . number_format($item['subtotal'], 0, ',', '.'));
    $y += 10;
}

$pdf->SetXY(160, $y + 10);
$pdf->Cell(30, 10, "Total: $" . number_format($total, 0, ',', '.'));

$pdf->Output("Factura.pdf", "I");
