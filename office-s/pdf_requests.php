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

$fields = array('ORDER ID', 'REQUEST TYPE', 'USER', 'ASSET', 'SERIAL', 'TYPE', 'STATUS');

require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont("Arial", "", 12);

// Add header row
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(20, 10, 'ORDER ID', 1, 0, 'C');
$pdf->Cell(40, 10, 'REQUEST TYPE', 1, 0, 'C');
$pdf->Cell(40, 10, 'USER', 1, 0, 'C');
$pdf->Cell(40, 10, 'ASSET', 1, 0, 'C');
$pdf->Cell(30, 10, 'SERIAL', 1, 0, 'C');
$pdf->Cell(30, 10, 'TYPE', 1, 0, 'C');
$pdf->Cell(30, 10, 'STATUS', 1, 0, 'C');
$pdf->Ln();

$sql = "SELECT r.order_id, r.req_type, u.name, a.model, a.serial, t.type, r.req_status, o.office
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN office o ON u.office_id = o.id
    JOIN asset_type t ON r.asset_type_id = t.id
    LEFT JOIN assets a ON r.asset_id = a.id
    WHERE u.office_id = $office
    ORDER BY r.order_id ASC";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $pdf->Cell(20, 10, $row['order_id'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['req_type'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['name'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['model'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['serial'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['type'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['req_status'], 1, 0, 'C');
        $pdf->Ln();
    }
    $fileName = "request-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".pdf";

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