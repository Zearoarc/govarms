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

$fields = array('ID', 'NAME', 'EMAIL', 'OFFICE', 'CONTACT', 'ROLE', 'DATE ADDED', 'REQUESTS');

require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont("Arial", "", 12);

// Add header row
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(50, 10, 'NAME', 1, 0, 'C');
$pdf->Cell(50, 10, 'EMAIL', 1, 0, 'C');
$pdf->Cell(30, 10, 'OFFICE', 1, 0, 'C');
$pdf->Cell(30, 10, 'CONTACT', 1, 0, 'C');
$pdf->Cell(30, 10, 'ROLE', 1, 0, 'C');
$pdf->Cell(30, 10, 'DATE CREATED', 1, 0, 'C');
$pdf->Cell(30, 10, 'REQUESTS', 1, 0, 'C');
$pdf->Ln();

$sql = "SELECT u.id, u.name, u.email, u.contact, u.user_role, u.date_add, o.office, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
    FROM users u
    INNER JOIN office o ON u.office_id = o.id
    WHERE u.office_id = '$office'";

$result=$con->select_by_query($sql);

if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
        $pdf->Cell(50, 10, $row['name'], 1, 0, 'C');
        $pdf->Cell(50, 10, $row['email'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['office'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['contact'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['user_role'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['date_add'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['request_count'], 1, 0, 'C');
        $pdf->Ln();
    }
    $fileName = "users-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".pdf";
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