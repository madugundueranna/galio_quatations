<script type="text/javascript" src="files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="files/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="files/bower_components/bootstrap/js/bootstrap.min.js"></script>
<!-- waves js -->
<script src="files/assets/pages/waves/js/waves.min.js"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<!-- amchart js -->
<script src="files/assets/pages/widget/amchart/amcharts.js"></script>
<script src="files/assets/pages/widget/amchart/serial.js"></script>
<script src="files/assets/pages/widget/amchart/light.js"></script>
<!-- Chartlist charts -->
<script src="files/bower_components/chartist/js/chartist.js"></script>
<!-- Custom js -->
<script src="files/assets/js/pcoded.min.js"></script>
<script src="files/assets/js/vertical/vertical-layout.min.js"></script>
<script type="text/javascript" src="files/assets/pages/dashboard/custom-dashboard.min.js"></script>
<script type="text/javascript" src="files/assets/js/script.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>




<!-- data tables -->

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> 
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>




<script>
    <?php if (isset($_SESSION['message'])) { ?>
        alertify.set('notifier', 'position', 'top-right');
        alertify.success('<?= $_SESSION['message']; ?>');
    <?php
        unset($_SESSION['message']);
    } ?>
</script>
</body>


</html>