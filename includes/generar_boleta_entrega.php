<?php
require_once __DIR__.'/fpdf/fpdf.php';
require_once __DIR__.'/config.php';

// Función alternativa segura
function toISO($text) {
    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
}

// Validación de entrada
$entrega_id = isset($_GET['entrega_id']) ? intval($_GET['entrega_id']) : 0;
if (!$entrega_id) {
    die('ID de entrega inválido');
}

// Extensión de FPDF
class PDF extends FPDF {
    function Header() {
        $this->SetFillColor(44,10,77);
        $this->SetTextColor(255,255,255);
        $this->SetFont('Arial','B',14);
        $this->Cell(0,8, toISO('REPARACIONES TECNOLOGÍA S.A.'), 0,1,'C',true);
        $this->SetFont('Arial','',10);
        $this->Cell(0,5, toISO('Av. Siempre Viva 123 – Buenos Aires'), 0,1,'C',true);
        $this->Cell(0,5, 'contacto@reparaciones.com | (011) 5555-5555', 0,1,'C',true);
        $this->Ln(4);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(120,120,120);
        $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Consulta de datos
$sql = "SELECT e.*,
       o.descripcion AS rep_descrip, o.fecha_ingreso, o.fecha_finalizacion,
       c.nombre AS cli_nombre, c.telefono, c.email AS cli_email, c.direccion AS cli_dir, c.dni AS cli_dni,
       t.nombre AS tec_nombre,
       f.id AS fac_id, f.total AS fac_total, f.metodo_pago, f.monto_pagado, f.estado_pago AS fac_estado,
       eq.tipo AS eq_tipo, eq.marca AS eq_marca, eq.modelo AS eq_modelo, eq.numero_serie
FROM entregas e
JOIN ordenes_reparacion o ON o.id = e.orden_reparacion_id
JOIN clientes c ON c.id = o.cliente_id
JOIN tecnicos t ON t.id = o.tecnico_id
LEFT JOIN facturas f ON f.orden_reparacion_id = o.id
JOIN equipos eq ON eq.id = o.equipo_id
WHERE e.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $entrega_id);
$stmt->execute();
$ent = $stmt->get_result()->fetch_assoc();
if (!$ent) die('Entrega no encontrada');

$servicios = $conn->query("SELECT servicio_nombre, precio FROM ordenes_servicios WHERE orden_reparacion_id = {$ent['orden_reparacion_id']}");
$repuestos = $conn->query("SELECT repuesto_nombre, cantidad, costo FROM ordenes_repuestos WHERE orden_reparacion_id = {$ent['orden_reparacion_id']}");

// PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10,10,10);

// Cliente
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(56,19,98);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(0,8, toISO('Datos del Cliente'), 0,1,'L',true);
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(40,40,40);
$pdf->Cell(0,6, toISO('Nombre: '.$ent['cli_nombre']),0,1);
$pdf->Cell(0,6, toISO('Teléfono: '.$ent['telefono']),0,1);
$pdf->Cell(0,6, toISO('Email: '.$ent['cli_email']),0,1);
$pdf->Cell(0,6, toISO('Dirección: '.$ent['cli_dir']),0,1);
$pdf->Cell(0,6, toISO('DNI: '.$ent['cli_dni']),0,1);
$pdf->Ln(4);

// Equipo
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(69,27,120);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(0,8, toISO('Datos del Equipo'), 0,1,'L',true);
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(40,40,40);
$pdf->Cell(0,6, toISO('Tipo: '.ucfirst($ent['eq_tipo'])),0,1);
$pdf->Cell(0,6, toISO('Marca: '.$ent['eq_marca']),0,1);
$pdf->Cell(0,6, toISO('Modelo: '.$ent['eq_modelo']),0,1);
$pdf->Cell(0,6, toISO('N° Serie: '.$ent['numero_serie']),0,1);
$pdf->Ln(4);

// Reparación
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(224,220,230);
$pdf->SetTextColor(40,40,40);
$pdf->Cell(0,8, toISO('Detalles de la Reparación'),0,1,'L',true);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6, toISO('Descripción: '.$ent['rep_descrip']),0,1);
$pdf->Cell(0,6, 'Ingreso: '.$ent['fecha_ingreso'],0,1);
if ($ent['fecha_finalizacion']) {
    $pdf->Cell(0,6, 'Finalización: '.$ent['fecha_finalizacion'],0,1);
}
$pdf->Ln(4);

// Servicios
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(44,10,77);
$pdf->Cell(120,7,toISO('Servicio'),1,0,'C',true);
$pdf->Cell(40,7,'Precio',1,1,'C',true);
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(40,40,40);
while ($s = $servicios->fetch_assoc()) {
    $pdf->Cell(120,7,toISO($s['servicio_nombre']),1);
    $pdf->Cell(40,7,'$'.number_format($s['precio'],2),1,1,'R');
}
$pdf->Ln(3);

// Repuestos
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(44,10,77);
$pdf->Cell(90,7,'Repuesto',1,0,'C',true);
$pdf->Cell(30,7,'Cant.',1,0,'C',true);
$pdf->Cell(40,7,'Subtotal',1,1,'C',true);
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(40,40,40);
while ($r = $repuestos->fetch_assoc()) {
    $sub = $r['cantidad'] * $r['costo'];
    $pdf->Cell(90,7,toISO($r['repuesto_nombre']),1);
    $pdf->Cell(30,7,$r['cantidad'],1,0,'C');
    $pdf->Cell(40,7,'$'.number_format($sub,2),1,1,'R');
}
$pdf->Ln(4);

// Factura
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(56,19,98);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(0,8, toISO('Resumen de Factura'),0,1,'L',true);
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(40,40,40);
if ($ent['fac_id']) {
    $pdf->Cell(0,6,'Numero de Factura: '.$ent['fac_id'],0,1);
    $pdf->Cell(0,6,'Metodo de Pago: '.toISO($ent['metodo_pago']),0,1);
    $pdf->Cell(0,6,'Pagado: $'.number_format($ent['monto_pagado'],2),0,1);
    $pdf->Cell(0,6,'Estado: '.toISO($ent['fac_estado']),0,1);
    $pdf->Cell(0,6,'Total: $'.number_format($ent['fac_total'],2),0,1);
} else {
    $pdf->Cell(0,6,'Factura no generada',0,1);
}
$pdf->Ln(4);

// Entrega
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(224,220,230);
$pdf->SetTextColor(40,40,40);
$pdf->Cell(0,8, toISO('Detalles de Entrega'),0,1,'L',true);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6,'Fecha Entrega: '.$ent['fecha_entrega'],0,1);
$pdf->Cell(0,6,'Metodo: '.toISO($ent['metodo_entrega']),0,1);
if ($ent['ubicacion_entrega']) {
    $pdf->Cell(0,6,'Ubicacion: '.toISO($ent['ubicacion_entrega']),0,1);
}
if ($ent['observaciones_generales']) {
    $pdf->Cell(0,6,'Observaciones: '.toISO($ent['observaciones_generales']),0,1);
}
if ($ent['comentarios_tecnicos']) {
    $pdf->Cell(0,6,'Comentarios Tecnicos: '.toISO($ent['comentarios_tecnicos']),0,1);
}
$pdf->Cell(0,6,'Estado Pago Entrega: '.toISO($ent['estado_pago_entrega']),0,1);
$pdf->Ln(10);

// Firmas
$pdf->SetFont('Arial','',11);
$pdf->Cell(90,6, toISO('Firma Técnico: __________________________'),0,0);
$pdf->Cell(0,6, toISO('Firma Cliente: __________________________'),0,1);

// Output
$pdf->Output('I', "Entrega_{$entrega_id}.pdf");
