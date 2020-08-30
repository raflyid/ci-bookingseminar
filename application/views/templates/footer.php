     <!-- Footer -->
     <footer class="sticky-footer">
         <div class="container my-auto">
             <div class="copyright text-center my-auto">
                 <span>Copyright &copy; Booking Seminar 2020</span>
             </div>
         </div>
     </footer>
     <!-- End of Footer -->

     </div>
     <!-- End of Content Wrapper -->

     </div>
     <!-- End of Page Wrapper -->

     <!-- Scroll to Top Button-->
     <a class="scroll-to-top rounded" href="#page-top">
         <i class="fas fa-angle-up"></i>
     </a>

     <!-- Logout Modal-->
     <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                     <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">×</span>
                     </button>
                 </div>
                 <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                 <div class="modal-footer">
                     <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                     <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
                 </div>
             </div>
         </div>
     </div>

     <!-- Bootstrap core JavaScript-->
     <script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
     <script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

     <!-- Core plugin JavaScript-->
     <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

     <!-- Custom scripts for all pages-->
     <script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

     <!-- Bootstrap Date-Picker Plugin -->
    <script src="<?php echo base_url('assets/'); ?>datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
    $(function(){
      $('#tanggal_seminar').datepicker({
        format: 'dd-mm-yyyy',
        startDate: "today",
        todayBtn: "linked",
        autoclose: true,
        toggleHighlight: true,
        toggleActive: true
      });
    });
    </script>

     <script>
$('.form-check-input').on('click', function() {
    const menuId = $(this).data('menu');
    const roleId = $(this).data('role');

    $.ajax({
        url: "<?= base_url('admin/changeaccess'); ?>",
        type: 'post',
        data: {
            menuId: menuId,
            roleId: roleId
        },
        success: function() {
            document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
        }
    });

});
     </script>


     <script src="<?php echo base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
     <script src="<?php echo base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>



     <script>
$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});
     </script>

     <script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/qrcodelib.js"></script>
     <script type="text/javascript" src="<?php echo base_url(); ?>./assets/js/webcodecamjquery.js"></script>
     <script type="text/javascript">
var arg = {
    resultFunction: function(result) {
        //$('.hasilscan').append($('<input name="noijazah" value=' + result.code + ' readonly><input type="submit" value="Cek"/>'));
        // $.post("../cek.php", { noijazah: result.code} );
        var redirect = '<?php echo base_url('coordinator/resultscan'); ?>';
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


     </body>

     </html>