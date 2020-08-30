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
                    <!-- <a style="float:right" href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newaddRekening">Add New Rekening</a> -->
                </div>

                <div class="card-body">

                    <table class="table table-hover" id="table1" style="border: 1px solid rgb(200, 200, 200);">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Penyelenggara</th>
                                <th scope="col">Tema Seminar</th>
                                <th scope="col">Nama Bank</th>
                                <th scope="col">No. Rek</th>
                                <th scope="col">Atas Nama</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($rekening as $r) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $r['fullname'] ?></td>
                                <td><?= $r['title'] ?></td>
                                <td><?= $r['nama_bank'] ?></td>
                                <td><?= $r['no_rek'] ?></td>
                                <td><?= $r['atas_nama'] ?></td>
                                <td>
                                    <a href="" class="badge badge-success" data-toggle="modal"
                                        data-target="#neweditRekening<?= $r['id_rekening'] ?>">edit</a>
                                </td>
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

<!-- Modal Edit -->
<?php foreach ($rekening as $r) :
  $id_rekening=$r['id_rekening'];
  ?>

<div class="modal fade" id="neweditRekening<?= $id_rekening; ?>" tabindex="-1" role="dialog"
    aria-labelledby="neweditRekening" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="neweditRekening">Edit Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?= base_url('coordinator/editrekening'); ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" id="id_rekening" name="id_rekening" value="<?= $id_rekening; ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" id="id_user" name="id_user"
                            value="<?= $r['fullname']; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="id_seminar" name="id_seminar"
                            value="<?= $r['title']; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <select name="nama_bank" id="nama_bank" class="form-control" required>
                            <option value="" disabled="" selected=""><?= $r['nama_bank']; ?></option>
                            <option value="Bank Central Asia">Bank Central Asia (BCA)</option>
                            <option value="Bank Negara Indonesia">Bank Negara Indonesia (BNI)</option>
                            <option value="Bank BNI Syariah">Bank BNI Syariah</option>
                            <option value="Bank Mega">Bank Mega</option>
                            <option value="Bank Rakyat Indonesia">Bank Rakyat Indonesia (BRI)</option>
                            <option value="CIMB Niaga">CIMB Niaga</option>
                            <option value="Permata Bank">Permata Bank</option>
                            <option value="Bank Mandiri">Bank Mandiri</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="number" class="form-control" id="no_rek" name="no_rek" value="<?= $r['no_rek']; ?>"
                            placeholder="Pembicara">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="atas_nama" name="atas_nama"
                            value="<?= $r['atas_nama']; ?>" placeholder="Tempat">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit Data</button>
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