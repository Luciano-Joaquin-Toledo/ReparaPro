<?php
require('./fpdf/fpdf.php');
include 'config.php';

$id_factura = intval($_GET['id']);

// Cargo datos de factura, cliente, servicios y repuestos
$query = "
  SELECT f.*, c.nombre AS cliente_nombre, c.direccion, c.email
  FROM facturas f
  JOIN ordenes_reparacion o ON f.orden_reparacion_id = o.id
  JOIN clientes c ON o.cliente_id = c.id
  WHERE f.id = $id_factura";
$factura = $conn->query($query)->fetch_assoc();

$servicios = $conn->query("
  SELECT servicio_nombre, precio 
  FROM ordenes_servicios 
  WHERE orden_reparacion_id = {$factura['orden_reparacion_id']}");
$repuestos = $conn->query("
  SELECT repuesto_nombre, cantidad, costo 
  FROM ordenes_repuestos 
  WHERE orden_reparacion_id = {$factura['orden_reparacion_id']}");

// Colores del dashboard en RGB
$violetPrimary = [44,10,77];     // #2c0a4d
$violetHover   = [56,19,98];     // #381362
$linkColor     = [224,220,230];  // #e0dce6
$grayLight     = [245,245,245];  // fondo tablas

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(15,15,15);

// **BANNER SUPERIOR**
$pdf->SetFillColor(...$violetPrimary);
$pdf->Rect(0, 0, 210, 30, 'F');
$pdf->SetXY(15, 6);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0, 10, utf8_decode("REPARACIONES TECNOLOGÍA S.A."), 0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0, 5, utf8_decode("Av. Siempre Viva 123 - Buenos Aires"), 0,1,'L');
$pdf->Cell(0, 5, utf8_decode("Email: contacto@reparaciones.com | Tel: (011) 5555-5555"), 0,1,'L');
$pdf->Ln(8);

// **TÍTULO & NÚMERO DE FACTURA**
$pdf->SetTextColor(...$violetPrimary);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0, 10, utf8_decode("FACTURA N° {$factura['id']}"), 0,1,'C');
$pdf->Ln(4);

// **DATOS CLIENTE Y FECHA**
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0);
$pdf->Cell(100, 6, utf8_decode('Datos del Cliente:'), 0,0,'L');
$pdf->Cell(0, 6, 'Fecha: ' . date('d/m/Y', strtotime($factura['fecha'])), 0,1,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(100, 6, utf8_decode("Nombre: ") . utf8_decode($factura['cliente_nombre']), 0,1,'L');
$pdf->Cell(100, 6, "Email: " . $factura['email'], 0,1,'L');
$pdf->Cell(100, 6, utf8_decode("Dirección: ") . utf8_decode($factura['direccion']), 0,1,'L');
$pdf->Ln(8);

// **SERVICIOS**
$pdf->SetFillColor(...$violetHover);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130, 8, utf8_decode('SERVICIOS REALIZADOS'), 1,0,'L', true);
$pdf->Cell(50, 8, 'PRECIO', 1,1,'C', true);

$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(0);
$fill = false;
while ($s = $servicios->fetch_assoc()) {
    $pdf->SetFillColor(...$grayLight);
    $pdf->Cell(130, 7, utf8_decode($s['servicio_nombre']), 1,0,'L', $fill);
    $pdf->Cell(50, 7, '$'.number_format($s['precio'],2), 1,1,'R', $fill);
    $fill = !$fill;
}
$pdf->Ln(6);

// **REPUESTOS**
$pdf->SetFillColor(...$violetHover);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(70, 8, utf8_decode('REPUESTOS USADOS'), 1,0,'L', true);
$pdf->Cell(30, 8, 'CANT.', 1,0,'C', true);
$pdf->Cell(40, 8, 'COSTO U.', 1,0,'C', true);
$pdf->Cell(40, 8, 'SUBTOTAL', 1,1,'C', true);

$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(0);
$fill = false;
while ($r = $repuestos->fetch_assoc()) {
    $subtotal = $r['cantidad'] * $r['costo'];
    $pdf->SetFillColor(...$grayLight);
    $pdf->Cell(70, 7, utf8_decode($r['repuesto_nombre']), 1,0,'L', $fill);
    $pdf->Cell(30, 7, $r['cantidad'], 1,0,'C', $fill);
    $pdf->Cell(40, 7, '$'.number_format($r['costo'],2), 1,0,'R', $fill);
    $pdf->Cell(40, 7, '$'.number_format($subtotal,2), 1,1,'R', $fill);
    $fill = !$fill;
}
$pdf->Ln(8);

// **TOTALES** — cada etiqueta y valor en su línea, con ancho de 140/40 mm
$pdf->SetFont('Arial','B',12);
$pdf->Cell(140, 8, 'TOTAL:', 0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(40, 8, '$'.number_format($factura['total'],2), 0,1,'R');
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(140, 8, 'PAGADO:', 0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(40, 8, '$'.number_format($factura['monto_pagado'],2), 0,1,'R');
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(140, 8, utf8_decode('MÉTODO:'), 0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(40, 8, utf8_decode(ucfirst($factura['metodo_pago'])), 0,1,'R');
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(140, 8, 'ESTADO:', 0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(40, 8, utf8_decode(ucfirst($factura['estado_pago'])), 0,1,'R');

$pdf->Ln(12);

// **FOOTER**
$pdf->SetFont('Arial','I',9);
$pdf->SetTextColor(...$linkColor);
$pdf->Cell(0, 6, utf8_decode("Gracias por confiar en nosotros. Esta factura es válida como comprobante."), 0,1,'C');

// Salida del PDF
$pdf->Output("I", "Factura_{$factura['id']}.pdf");
