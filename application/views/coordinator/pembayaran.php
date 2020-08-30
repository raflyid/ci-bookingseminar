
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->

  <div class="row">
    <div class="col-lg">
      <?php if(validation_errors()) : ?>
        <div class="alert alert-danger" role="alert"><?= validation_errors(); ?></div>
      <?php endif; ?>
        
      <?= $this->session->flashdata('message'); ?>

      <!-- <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newaddSeminar">Add New Seminar</a> -->

        <div class="card shadow mb-5">
        <div class="card-header">
         <strong class="card-title text-primary"><?= $title ?></strong>
        <strong class="card-title" style="color:white"></strong>
        </div>

        <div class="card-body">
        
      <table class="table table-hover" id="table1" style="border: 1px solid rgb(200, 200, 200);">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Nama Peserta</th>
            <th scope="col">Harga</th>
            <th scope="col">Tempat</th>
            <th scope="col">Tanggal Pemesanan</th>
            <th scope="col">Status</th>
            <th scope="col">Bukti Pembayaran</th>
            <th scope="col">QR</th>
            <th scope="col">Bank</th>
            <th scope="col">No. Rek</th>
            <th scope="col">Atas Nama</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
        <?php foreach ($pembayaran as $p) : ?>
          <tr>
            <th scope="row"><?= $i; ?></th>
            <td><?= $p['title'] ?></td>
            <td><?= $p['nama_pemesan'] ?></td>
            <td><?= $p['harga'] ?></td>
            <td><?= $p['tempat'] ?></td>
            <td><?= $p['tanggal_pemesanan'] ?></td>
            
            <?php if($p['status']=='PENDING'){?>
             <td><?= $p['status'] ?></td>
            <?php }else if($p['status']=='IN PROCESS'){?>
            <td><a href="" class="badge badge-success" data-toggle="modal" data-target="#neweditStatus<?= $p['id_pembayaran'] ?>" >Update Status</a><br><small><?= $p['status']; ?></small></td>
            <?php }else {?>
            <td><small><?= $p['status'] ?></small></td>
            <?php }?>
           
            <td class="text-center"><img src="<?= $p['url'] ; ?>" width="66" height="64"></td>
            <td class="text-center"><img src="<?= $p['qrcode']; ?>" width="66" height="64"></td>
            <td><?= $p['nama_bank'] ?></td>
            <td><?= $p['no_rek'] ?></td>
            <td><?= $p['atas_nama'] ?></td>
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


</div>
<!-- /.container-fluid -->

<!-- Modal Edit -->
<?php foreach ($pembayaran as $p) :
  $id_pembayaran=$p['id_pembayaran'];
  ?>

<div class="modal fade" id="neweditStatus<?= $id_pembayaran; ?>" tabindex="-1" role="dialog" aria-labelledby="neweditStatus" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="neweditStatus">Edit Status</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <form action="<?= base_url('coordinator/editstatus'); ?>" method="post">
    <div class="modal-body">
    <input type="hidden" id="id_pembayaran" name="id_pembayaran" value="<?= $id_pembayaran; ?>">
      <div class="form-group row">
      <label ondragstart="return false;" for="inputstatus" class="col-sm-4 col-form-label">Status</label>
        <div class="col-sm-6">
          <select name="status" id="status" class="form-control" required>
                              <option value="" disabled="" selected="">Pilih Status</option>
                              <option value="PAID">PAID</option>
          </select>
        </div>
	    </div>

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Update Status</button>
    </div>
    </form>
  </div>
</div>
</div>
<?php endforeach; ?>

<!-- End of Main Content -->

<script src="https://code.jquery.com/jquery-3.1.0.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    });
</script>