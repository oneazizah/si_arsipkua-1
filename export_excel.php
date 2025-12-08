<?php
require_once "config.php";

$from = $_GET['from'] ?? null;
$to   = $_GET['to'] ?? null;

if(!$from || !$to){
    die("Parameter tanggal tidak valid.");
}

$stmt = $pdo->prepare("
    SELECT * FROM akta_nikah
    WHERE tanggal_akad BETWEEN :from AND :to
    ORDER BY tanggal_akad ASC
");
$stmt->execute([':from' => $from, ':to' => $to]);
$rows = $stmt->fetchAll();

// ====== Header Excel ======
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_arsip_$from-$to.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>
<tr>
    <th>No</th>
    <th>Nomor Akta</th>
    <th>Tanggal Akad</th>
    <th>Nama Pria</th>
    <th>Nama Wanita</th>
</tr>";

$no = 1;
foreach($rows as $r){
    echo "<tr>
            <td>{$no}</td>
            <td>{$r['nomor_akta']}</td>
            <td>{$r['tanggal_akad']}</td>
            <td>{$r['nama_pria']}</td>
            <td>{$r['nama_wanita']}</td>
        </tr>";
    $no++;
}
echo "</table>";
exit;
?>
