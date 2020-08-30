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
                    <a style="float:right" href="" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#newaddSeminar">Add New Seminar</a>
                </div>

                <div class="card-body">

                    <table class="table table-hover" id="table1" style="border: 1px solid rgb(200, 200, 200);">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Pembuat Seminar</th>
                                <th scope="col">Description</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Pembicara</th>
                                <th scope="col">Tempat</th>
                                <th scope="col">Tanggal Seminar</th>
                                <th scope="col">Image</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($seminar as $s) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $s['title'] ?></td>
                                <td><?= $s['fullname'] ?></td>
                                <td><?= $s['short_desc'] ?></td>
                                <td><?= $s['harga'] ?></td>
                                <td><?= $s['slot'] ?></td>
                                <td><?= $s['pembicara'] ?></td>
                                <td><?= $s['tempat'] ?></td>
                                <td><?= $s['tanggal_seminar'] ?></td>
                                <td class="text-center"><img src="<?= $s['image']; ?>" width="66" height="64"></td>
                                <td>
                                    <a href="" class="badge badge-success" data-toggle="modal"
                                        data-target="#neweditSeminar<?= $s['id_seminar'] ?>">edit</a>
                                    <a href="<?= base_url('admin/deleteseminar/'.$s['id_seminar']); ?>"
                                        class="badge badge-danger">delete</a>
                                    <a href="<?= base_url('admin/dataabsensi/'.$s['id_seminar']); ?>"
                                        class="badge badge-warning">data absensi</a>
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

<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="newaddSeminar" tabindex="-1" role="dialog" aria-labelledby="newaddSeminar"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newaddSeminar">New Seminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open_multipart('admin/seminar'); ?>
            <div class="modal-body">
                <input type="hidden" id="id_user" name="id_user" value="<?= $user['id_user']; ?>">
                <div class="form-group">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Seminar Title">
                </div>

                <div class="form-group">
                    <textarea class="form-control" class="form-control" id="short_desc" rows="5" name="short_desc"
                        placeholder="Your description goes here.."></textarea>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="harga">Harga</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Rp.</div>
                        </div>
                        <input type="number" class="form-control" id="harga" name="harga" placeholder="">

                    </div>
                    <small>&nbsp; Example: 50.000</small>
                </div>

                <div class="form-group">
                    <input type="number" class="form-control" id="slot" name="slot" placeholder="Slot">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="pembicara" name="pembicara" placeholder="Pembicara">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Tempat">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="tanggal_seminar" name="tanggal_seminar"
                        placeholder="Pilih tanggal seminar...">
                </div>

                <div class=" custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image" placeholder="Image">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>

                <div class="form-group">
                    <small style="color: red">&nbsp;Input data rekening yang akan digunakan untuk transfer pembayaran
                        seminar</small>
                </div>

                <div class="form-group">
                    <select name="nama_bank" id="nama_bank" class="form-control" required>
                        <option value="" disabled="" selected="">Pilih Bank</option>
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
                    <input type="number" class="form-control" id="no_rek" name="no_rek" placeholder="No. Rekening">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="atas_nama" name="atas_nama" placeholder="Atas Nama">
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Seminar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<?php foreach ($seminar as $s) :
  $id_seminar=$s['id_seminar'];
  ?>

<div class="modal fade" id="neweditSeminar<?= $id_seminar; ?>" tabindex="-1" role="dialog"
    aria-labelledby="neweditSeminar" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="neweditSeminar">Edit Seminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?= base_url('admin/editseminar'); ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" id="id_seminar" name="id_seminar" value="<?= $id_seminar; ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" id="title" name="title" value="<?= $s['title']; ?>">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="id_user" name="id_user"
                            value="<?= $s['fullname']; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control" class="form-control" id="short_desc" rows="5" name="short_desc"
                            placeholder="Description"><?= $s['short_desc']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="sr-only" for="harga">Harga</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <input type="number" class="form-control" id="harga" name="harga"
                                value="<?= $s['harga']; ?>" placeholder="">

                        </div>
                        <small>&nbsp; Example: 50.000</small>
                    </div>

                    <div class="form-group">
                        <input type="number" class="form-control" id="slot" name="slot" value="<?= $s['slot']; ?>"
                            placeholder="Slot">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="pembicara" name="pembicara"
                            value="<?= $s['pembicara']; ?>" placeholder="Pembicara">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="tempat" name="tempat" value="<?= $s['tempat']; ?>"
                            placeholder="Tempat">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="tanggal_seminar" name="tanggal_seminar"
                            value="<?= $s['tanggal_seminar']; ?>" placeholder="Tanggal Seminar" readonly>
                    </div>

                    <!-- <div class=" custom-file">
              <input type="file" class="custom-file-input" id="image" name="image" placeholder="Image">
              <label class="custom-file-label" for="customFile">Choose file</label>
          </div> -->

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