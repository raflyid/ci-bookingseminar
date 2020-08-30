<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>

    <div class="card mb-3" style="max-width: 540px;">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="<?= base_url('assets/img/default.png') ?>" class="card-img">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?= $user['fullname'] ?></h5>
                    <p class="card-text"><?= $user['email'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="card" style="width: 55rem;">
        <div class="card-header">
            Info
        </div>
        <div class="card-body">
            <h5 class="card-title">Hi, <?= $user['fullname'] ?>!</h5>
            <p class="card-text">Selamat datang kembali <?= $user['fullname'] ?>. Mohon gunakan website ini dengan bijak
                dan penuh tanggungjawab.</p>
            <p class="card-text" style="color: red;">Silahkan gunakan link yang ada pada sidebar untuk
                melakukan manajemen
                data.</p>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->