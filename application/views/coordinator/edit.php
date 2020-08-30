        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


            <div class="row">
                <div class="col-lg-8">
                    <?= form_open_multipart('coordinator/edit'); ?>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email"
                                value="<?= $user['email']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Fullname</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                value="<?= $user['fullname']; ?>">
                            <?= form_error('fullname', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2">Picture</div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="<?= base_url('assets/img/default.png'); ?>" class="img-thumbnail">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </div>

                    </form>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->