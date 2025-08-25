<?php
session_start();


include './Configuracion.php'; // Ajusta la ruta según tu estructura
require '../../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Pedidos');

// Establecer encabezados con estilos
$encabezados = ['ID Detalle', 'ID Recibo', 'Cliente', 'Producto', 'Cantidad'];
$letras = ['A', 'B', 'C', 'D', 'E'];

foreach ($encabezados as $index => $encabezado) {
    $columna = $letras[$index] . '1';
    $sheet->setCellValue($columna, $encabezado);
    $sheet->getStyle($columna)->getFont()->setBold(true);
    $sheet->getStyle($columna)->getAlignment()->setHorizontal('center');
}

// Consultar datos de la base de datos
$sql = "SELECT d.IdDetalleRecibo, d.IdRecibo, p.Nombre, c.Descripcion, d.Cantidad 
        FROM detalleRecibo d
        JOIN recibo r ON d.IdRecibo = r.IdRecibo
        JOIN persona p ON r.IdPersona = p.IdPersona
        JOIN calzado c ON d.IdCalzado = c.IdCalzado";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Insertar datos en las filas
$fila = 2;
foreach ($pedidos as $pedido) {
    $sheet->setCellValue("A$fila", $pedido['IdDetalleRecibo']);
    $sheet->setCellValue("B$fila", $pedido['IdRecibo']);
    $sheet->setCellValue("C$fila", $pedido['Nombre']);
    $sheet->setCellValue("D$fila", $pedido['Descripcion']);
    $sheet->setCellValue("E$fila", $pedido['Cantidad']);
    $fila++;
}

// Ajustar ancho de columnas automáticamente
foreach ($letras as $columna) {
    $sheet->getColumnDimension($columna)->setAutoSize(true);
}

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$filename = "Pedidos.xlsx";

// Cabeceras para forzar la descarga
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Cache-Control: max-age=0");

$writer->save("php://output");
exit;
?>
