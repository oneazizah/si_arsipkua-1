<?php require 'header.php'; ?>

<head>
    <link rel="stylesheet" href="assets/css/report.css">
</head>

<h3 class="mb-4">📊 Laporan Arsip Buku Nikah</h3>

<div class="report-card">

    <div class="section-title mb-3">🔎 Filter Laporan</div>

    <form class="row g-3 mb-3" method="get" action="index.php">
        <input type="hidden" name="page" value="report">

        <div class="col-md-3">
            <label class="input-label">Dari Tanggal</label>
            <input type="date" name="from" class="form-control" required 
                   value="<?= $_GET['from'] ?? '' ?>">
        </div>

        <div class="col-md-3">
            <label class="input-label">Sampai Tanggal</label>
            <input type="date" name="to" class="form-control" required
                   value="<?= $_GET['to'] ?? '' ?>">
        </div>

        <div class="col-md-2 d-grid align-items-end">
            <button class="btn btn-primary mt-auto">Tampilkan</button>
        </div>
    </form>

    <?php if (isset($_GET['from'], $_GET['to'])): ?>

        <div class="result-card">

            <div class="mb-3">
                <span class="period-badge">
                    Periode: <?= $_GET['from'] ?> s.d <?= $_GET['to'] ?>
                </span>
            </div>

            <div class="mb-3">
                <a href="report_pdf.php?from=<?= $_GET['from'] ?>&to=<?= $_GET['to'] ?>" 
                   class="btn btn-danger me-2">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>

                <a href="export_excel.php?from=<?= $_GET['from'] ?>&to=<?= $_GET['to'] ?>" 
                   class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>

            <?php
            // Ambil data laporan
            $from = $_GET['from'];
            $to = $_GET['to'];

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
            ?>

            <?php if (empty($rows)): ?>
                <div class="alert alert-warning">Tidak ada data pada periode tersebut.</div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover report-table">
                        <thead>
                            <tr class="text-center">
                                <th width="50">No</th>
                                <th width="150">Nomor Akta</th>
                                <th width="120">Tanggal Akad</th>
                                <th>Nama Pria</th>
                                <th>Nama Wanita</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($rows as $r): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($r['nomor_akta']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($r['tanggal_akad']) ?></td>
                                <td><?= htmlspecialchars($r['nama_pria']) ?></td>
                                <td><?= htmlspecialchars($r['nama_wanita']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

<?php require 'footer.php'; ?>
