<!-- CSS only -->
<div class="card">
    <div class="card-body">
        <a href="<?= route('export_itemMajo') ?>?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>"
            class="btn btn-sm btn-info float-left">Export</a>
        <table width="100%" class="table" id="tb-item">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Menu</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($kategori as $k) : ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $k->nm_produk ?></td>
                    <td><?= $k->qty ?></td>
                    <td><?= number_format($k->qty * $k->harga, 0) ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

</div>
<script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script>
    $("#tb-item").DataTable({
        "lengthChange": false,
        "autoWidth": false,
        "paging": true
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
</script>
