<?php
session_start();
include("../conn.php");
$con = new connec();

function filterData(&$str){
    if ($str === null) {
        $str = '';
    }
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

$office = $_SESSION["office_id"];

$fields = array('ID', 'TYPE', 'BRAND', 'MODEL', 'SERIAL', 'OFFICE', 'DATE ADDED', 'COST', 'STATUS');

require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont("Arial", "", 12);

// Add header row
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(30, 10, 'TYPE', 1, 0, 'C');
$pdf->Cell(30, 10, 'BRAND', 1, 0, 'C');
$pdf->Cell(40, 10, 'MODEL', 1, 0, 'C');
$pdf->Cell(30, 10, 'SERIAL', 1, 0, 'C');
$pdf->Cell(30, 10, 'OFFICE', 1, 0, 'C');
$pdf->Cell(30, 10, 'DATE ADDED', 1, 0, 'C');
$pdf->Cell(30, 10, 'COST', 1, 0, 'C');
$pdf->Cell(30, 10, 'STATUS', 1, 0, 'C');
$pdf->Ln();

$sql = "SELECT a.id, t.type, b.brand, a.model, a.serial, o.office, a.date_add, a.cost, a.status
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN brand b ON a.brand_id = b.id
    INNER JOIN office o ON a.office_id = o.id
    WHERE a.office_id = $office";

$result=$con->select_by_query($sql);

if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['type'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['brand'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['model'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['serial'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['office'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['date_add'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['cost'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['status'], 1, 0, 'C');
        $pdf->Ln();
    }
    $fileName = "assets-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".pdf";
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    $pdf->Output($fileName, 'D');
    exit();
} else {
    // Handle empty result set
    header("HTTP/1.1 404 Not Found");
    echo "No data found for export.";
    exit();
}
?>