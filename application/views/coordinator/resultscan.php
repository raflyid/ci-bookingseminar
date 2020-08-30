<div class="row">
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <body>
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800" align="center"><?= $title; ?></h1>
            <p class="mb-4" align="center">Silahkan lakukan <i>Scanning</i> QR <i>Code</i> untuk melakukan verifikasi
                data.</p>

            <div align="center" class="alert">
                <canvas></canvas>
                <form method="post" action="<?= base_url('coordinator/resultscan'); ?>">
                    <?php $id_pembayaran = $_POST['id_pembayaran']; ?>
                    <input type="hidden" id="id_pembayaran" name="id_pembayaran" value="<?= $id_pembayaran; ?>">
                </form>
                <br>
                <select></select>

            </div>
            <br>
        </body>
    </div>
</div>
<!-- /.container-fluid -->
<!-- End of Main Content -->

<script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/qrcodelib.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/webcodecamjquery.js"></script>
<script type="text/javascript">
var arg = {
    resultFunction: function(result) {
        var redirect = '<?php echo base_url('
        coordinator / resultscan '); ?>';
        $.redirectPost(redirect, {
            id_pembayaran: result.code
        });
        //id_seminar: document.getElementById('id_seminar').value
    }
};

var decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
decoder.buildSelectMenu("select");
decoder.play();
/*  Without visible select menu
    decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
*/
$('select').on('change', function() {
    decoder.stop().play();
});

// jquery extend function
$.extend({
    redirectPost: function(location, args) {
        var form = '';
        $.each(args, function(key, value) {
            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
        });
        $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo('body').submit();
    }
});
</script>

<div class="container-fluid">
    <div class="panel panel-success">
        <div class="panel-body">

            <?php if(validation_errors()) : ?>
            <div class="alert alert-success" id="tutup" role="alert"><button type="button" class="close"
                    data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button><?= validation_errors(); ?></div>
            <?php endif; ?>


            <?php $absensi = $this->db->query("SELECT * from absensi WHERE id_absensi='$_POST[id_pembayaran]' ")->row();?>

            <?php $datahasil = $this->db->query("SELECT pembayaran.*, nama_pemesan, users.email, seminar.title, seminar.harga, seminar.pembicara, status FROM pembayaran LEFT JOIN seminar ON seminar.id_seminar=pembayaran.id_seminar LEFT JOIN users ON users.id_user=pembayaran.id_user WHERE pembayaran.id_pembayaran='$_POST[id_pembayaran]' "); ?>
            <?php if ($datahasil->num_rows() < 1) { ?>
            <div class="alert alert-danger">
                <center>
                    <strong>Mohon maaf, Peserta tidak terdaftar!</strong><br>
                </center>
            </div>
            <?php } else { ?>
            <table class="table table-responsive-data2">
                <thead>
                    <tr style="color:blue;" align="center">
                        <?php
                        foreach ($datahasil->result() as $data) { ?>
                        <th class="h4" colspan="12">Data <?php echo $data->title; ?></th>
                        <?php } ?>
                    </tr>

                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>Email Peserta</th>
                        <th>Nama Seminar</th>
                        <th>Harga</th>
                        <th>Pembicara</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Status</th>
                    </tr>

                </thead>
                <tbody>
                    <?php $no = 1;
                        foreach ($datahasil->result() as $data) { ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data->nama_pemesan; ?></td>
                        <td><?php echo $data->email; ?></td>
                        <td><?php echo $data->title; ?></td>
                        <td><?php echo $data->harga; ?></td>
                        <td><?php echo $data->pembicara; ?></td>
                        <td><?php echo $data->tanggal_pemesanan; ?></td>
                        <td><?php echo $data->status; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br>
        <div class="panel-footer">
            <center><a class="btn btn-danger" href="<?php echo base_url('coordinator/scanner') ?>">Kembali</a></center>
        </div>
    </div>
</div>
</div>

<script>
window.setTimeout(function() {
    $("#tutup").fadeTo(2000, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 3000);
</script>