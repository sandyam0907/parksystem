</div> <!-- flex-fill -->
</div> <!-- page-wrapper -->

<footer class="footer text-white text-center py-2" style="background-color:#06606aff;">
    &copy; <?= date('Y') ?> My Company
</footer>


<script src="<?= base_url('assets\js\jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!-- <script src="<?= base_url('assets/js/buttons.html5.min.js') ?>"></script> -->

<script>
    $(function () {
        $('#myTable').DataTable({
            pageLength: 10
        });
    });
</script>

</body>

</html>