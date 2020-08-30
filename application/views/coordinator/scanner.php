<!-- Begin Page Content -->
<div class="container-fluid">

    <body>
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800" align="center"><?= $title; ?></h1>
        <p class="mb-4" align="center">Silahkan lakukan <i>Scanning</i> QR <i>Code</i> untuk melakukan verifikasi data.
        </p>
        <div align="center" class="alert">

            <?php
                foreach ($seminar as $s) :
                $id_seminar=$s['id_seminar']; ?>
            <input hidden type="text" id="id_seminar" name="id_seminar" value="<?= $s['id_seminar']; ?>">
            <h2 align="center">Seminar <?= $s['title']; ?></h2>
            <?php endforeach; ?>

            <br>
            <canvas></canvas>
            <br>
            <select></select>
            <hr>
        </div>
        <br>
    </body>
</div>

<script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>

</div>

<!-- /.container-fluid -->
<!-- End of Main Content -->