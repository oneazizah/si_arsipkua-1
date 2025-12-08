<?php
require_once "config.php";

// Ambil parameter tanggal
$from = $_GET['from'] ?? null;
$to   = $_GET['to'] ?? null;

if (!$from || !$to) {
    die("Parameter tanggal tidak valid.");
}

// Ambil data laporan
$stmt = $pdo->prepare("
    SELECT * FROM akta_nikah 
    WHERE tanggal_akad BETWEEN :from AND :to
    ORDER BY tanggal_akad ASC
");
$stmt->execute([
    ':from' => $from,
    ':to'   => $to
]);
$rows = $stmt->fetchAll();

// Load FPDF
require_once __DIR__ . "/libs/fpdf/fpdf.php";

$pdf = new FPDF("P", "mm", "A4");
$pdf->AddPage();

// ================= HEADER =================
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"LAPORAN ARSIP BUKU NIKAH",0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Periode: $from s.d $to",0,1,'C');

$pdf->Ln(5);

// ================= TABLE HEADER =================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,8,"No",1,0,'C');
$pdf->Cell(35,8,"Nomor Akta",1,0,'C');
$pdf->Cell(30,8,"Tgl Akad",1,0,'C');
$pdf->Cell(55,8,"Nama Pria",1,0,'C');
$pdf->Cell(55,8,"Nama Wanita",1,1,'C');

// ================= TABLE BODY =================
$pdf->SetFont('Arial','',9);
$no = 1;
foreach ($rows as $r) {
    $pdf->Cell(10,8, $no++, 1);
    $pdf->Cell(35,8, $r['nomor_akta'], 1);
    $pdf->Cell(30,8, $r['tanggal_akad'], 1);
    $pdf->Cell(55,8, $r['nama_pria'], 1);
    $pdf->Cell(55,8, $r['nama_wanita'], 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output("I", "laporan_arsip.pdf");
exit;
