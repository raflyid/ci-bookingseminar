<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1> -->

    <div class="row">
        <div class="col-lg">
            <?php if(validation_errors()) : ?>
            <div class="alert alert-danger" role="alert"><?= validation_errors(); ?></div>
            <?php endif; ?>

            <?= $this->session->flashdata('message'); ?>

            <div class="card shadow mb-5">
                <div class="card-header">
                    <strong class="card-title text-primary"><?= $title ?></strong>
                    <strong class="card-title" style="color:white"></strong>
                    <a style="float:right" href="<?= base_url('coordinator/seminar'); ?>"
                        class="btn btn-danger btn-sm">Kembali</a>
                </div>

                <div class="card-body">

                    <table class="table table-hover" id="table1" style="border: 1px solid rgb(200, 200, 200);">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Peserta</th>
                                <th scope="col">Email Peserta</th>
                                <th scope="col">Nama Seminar</th>
                                <th scope="col">Pembicara</th>
                                <th scope="col">Tanggal Pemesanan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($dataabsensi as $da) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $da['nama_pemesan'] ?></td>
                                <td><?= $da['email'] ?></td>
                                <td><?= $da['title'] ?></td>
                                <td><?= $da['pembicara'] ?></td>
                                <td><?= $da['tanggal_pemesanan'] ?></td>
                                <td><?= $da['status'] ?></td>

                                <?php if($da['sertifikat'] == null){ ?>
                                <td>
                                    <a href="" class="badge badge-warning" data-toggle="modal"
                                        data-target="#neweditCert<?= $da['id_absensi'] ?>">push certificate</a>
                                </td>
                                <?php } else { ?>
                                <td class="text-center"><img
                                        src="<?= base_url('../adminbookingseminar/assets/img/sertifikat/' .$da['sertifikat']); ?>"
                                        width="66" height="64"></td>
                                <?php } ?>
                            </tr>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>


        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal -->

<!-- Modal -->
<?php foreach ($dataabsensi as $da) :
  $id_absensi=$da['id_absensi'];
  $id_seminar=$da['id_seminar'];
  ?>

<div class="modal fade" id="neweditCert<?= $id_absensi; ?>" tabindex="-1" role="dialog" aria-labelledby="neweditCert"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="neweditCert">Upload Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open_multipart('coordinator/editdataabsensi'); ?>
            <div class="modal-body">
                <input type="hidden" id="id_absensi" name="id_absensi" value="<?= $id_absensi; ?>">
                <input type="hidden" id="id_seminar" name="id_seminar" value="<?= $id_seminar; ?>">

                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="sertifikat" name="sertifikat"
                        placeholder="sertifikat">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.1.0.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#table1').DataTable();
});
</script>