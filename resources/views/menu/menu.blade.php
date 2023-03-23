@extends('template.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2 justify-content-center">
                    <div class="col-sm-12">

                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">



                                <h5>Data Menu {{ $id_lokasi == 1 ? 'Takemori' : 'Soondobu' }}</h5>
                                <a href="" data-toggle="modal" data-target="#tambah"
                                    class="btn btn-info float-right"><i class="fas fa-plus"></i> Tambah Menu</a>
                                <a href="" data-toggle="modal" data-target="#import"
                                    class="mr-2 btn btn-info float-right"><i class="fas fa-file-import"></i> Import</a>
                                <a href="{{ route('exportMenu', ['lokasi' => $id_lokasi]) }}"
                                    class="mr-2 btn btn-info float-right"><i class="fas fa-file-excel"></i> Export</a>

                            </div>
                            @include('flash.flash')
                            <div class="card-body">
                                <table class="table  " id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kategori</th>
                                            <th>Kode Menu</th>
                                            <th>Nama Menu</th>
                                            <th>Tipe</th>
                                            <th>Distribusi</th>
                                            <th></th>
                                            <th>On/Off</th>
                                            <!--<th>Image</th>-->
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($menu as $m)
                                            @php
                                                $harga = DB::table('tb_harga')
                                                    ->select('tb_harga.*', 'tb_distribusi.*')
                                                    ->join('tb_distribusi', 'tb_harga.id_distribusi', '=', 'tb_distribusi.id_distribusi')
                                                    ->where('id_menu', $m->id_menu)
                                                    ->get();
                                            @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $m->kategori }}</td>
                                                <td>{{ $m->kd_menu }}</td>
                                                <td>{{ ucwords(Str::lower($m->nm_menu)) }}</td>
                                                <td>{{ $m->tipe }}</td>
                                                <td style="white-space: nowrap;">
                                                    @foreach ($harga as $h)
                                                        {{ $h->nm_distribusi }} <br>
                                                    @endforeach
                                                </td>

                                                <td>
                                                    @foreach ($harga as $h)
                                                        :{{ number_format($h->harga, 0) }} <br>
                                                    @endforeach
                                                    <a href="" data-placement="top" title="Tambah Distribusi"
                                                        data-toggle="tooltip" class="btn btn-new btnPlusDistribusi"
                                                        id_menu="{{ $m->id_menu }}" style="background-color: #F7F7F7;"><i
                                                            style="color: #B0BEC5;"><i class="fas fa-plus"></i></a>

                                                    <a href="#" data-placement="top" title="Tambah Resep"
                                                        data-toggle="tooltip" class="btn btn-new btnPlusResep"
                                                        id_menu="{{ $m->id_menu }}" style="background-color: #F7F7F7;"><i
                                                            style="color: #B0BEC5;"><i class="fas fa-plus"></i></a>
                                                </td>
                                                <td>
                                                    <label class="switch float-left">
                                                        <?php if ($m->aktif == 'on') : ?>
                                                        <input type="checkbox" class="form-checkbox1" id="form-checkbox"
                                                            id_checkbox="<?= $m->id_menu ?>" checked>
                                                        <span class="slider round"></span>
                                                        <?php else : ?>
                                                        <input type="checkbox" class="form-checkbox1"
                                                            id_checkbox="<?= $m->id_menu ?>" id="form-checkbox">
                                                        <span class="slider round"></span>
                                                        <?php endif ?>

                                                    </label>
                                                    <?php if ($m->aktif == 'on') : ?>
                                                    <input name="monitor"
                                                        class="swalDefaultSuccess form-password nilai<?= $m->id_menu ?>  form-control"
                                                        value="on" hidden>
                                                    <?php else : ?>
                                                    <input name="monitor"
                                                        class="swalDefaultSuccess form-password nilai<?= $m->id_menu ?>  form-control"
                                                        hidden>
                                                    <?php endif ?>
                                                </td>
                                                <td style="white-space: nowrap;">
                                                    <a href="" data-toggle="modal"
                                                        data-target="#edit_data{{ $m->id_menu }}"
                                                        id_menu="{{ $m->id_menu }}" id_lokasi="{{ $m->lokasi }}"
                                                        class="btn edit_menu btn-new editMenu"
                                                        style="background-color: #F7F7F7;"><i style="color: #B0BEC5;"><i
                                                                class="fas fa-edit"></i></a>
                                                    <a onclick="return confirm('Apakah ingin hapus ?')"
                                                        href="{{ route('deleteMenu', ['id_menu' => $m->id_menu, 'id_lokasi' => $id_lokasi]) }}"
                                                        class="btn  btn-new" style="background-color: #F7F7F7;"><i
                                                            style="color: #B0BEC5;"><i class="fas fa-trash-alt"></i></a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>







                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <style>
        .modal-lg-max {
            max-width: 900px;
        }
    </style>
    <form action="{{ route('updateMenu') }}" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        @csrf
        <div class="modal fade" id="edit_data" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg-max" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Edit Menu</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="menu"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info">Edit/Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="{{ route('importMenu') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="modal fade" id="import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg-max" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah menu</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="lokasi" value="{{ $id_lokasi }}">
                        <div class="row">
                            <input type="hidden" name="id_lokasi" value="{{ $id_lokasi }}">
                            <div class="col-sm-4 ol-md-6 col-xs-12 mb-2">
                                <label for="">Masukkan Gambar</label>
                                <input type="file" data-height="150" name="file">
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-costume" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-costume">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- tambah distribusi --}}
    <form action="{{ route('plusDistribusi') }}" method="post">
        @csrf
        <div class="modal fade" id="distribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Distribusi</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <input type="hidden" name="id_lokasi" value="{{ $id_lokasi }}">
                                <input type="hidden" name="id_menu" value="" id="distribusiIdMenu">
                                <label for="">
                                    <dt>Distribusi</dt>
                                </label>
                                <select name="id_distribusi" id="" class="form-control select">
                                    <option value="">-Pilih distribusi-</option>
                                    <?php foreach ($distribusi as $d) : ?>
                                    <option value="<?= $d->id_distribusi ?>"><?= $d->nm_distribusi ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label for="">
                                    <dt>Harga</dt>
                                </label>
                                <input type="text" name="harga" class="form-control" placeholder="Harga">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info">Edit/Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- ------------------- --}}

    {{-- tambah resep --}}
    <form action="{{ route('plusResep') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="modal fade" id="resep" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Resep</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_menu" value="" id="resepIdMenu">
                        <input type="hidden" name="id_lokasi" value="{{ Request::get('id_lokasi') }}">
                        <label for="">Import File Resep <a href="{{ route('formatResep') }}"
                            class="btn btn-primary btn-xs">Download Format</a></label>
                    <input type="file" name="file" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info">Edit/Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- ----------------------- --}}

    {{-- tambah menu --}}
    <form action="{{ route('addMenu') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="modal fade" id="tambah" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg-max" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah menu</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_lokasi" value="{{ $id_lokasi }}">
                            <div class="col-sm-4 ol-md-6 col-xs-12 mb-2">
                                <label for="">Masukkan Gambar</label>
                                <input type="file" class="dropify" data-height="150" name="image"
                                    placeholder="Image">
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 mb-2">
                                        <label for="">
                                            <dt>Kategori</dt>
                                        </label>
                                        <select name="id_kategori" id="" class="form-control select">
                                            <option value="">-Pilih Kategori-</option>
                                            @foreach ($kategori as $m)
                                                <option value="{{ $m->kd_kategori }}">{{ $m->kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label for="">
                                            <dt>Kode Menu</dt>
                                        </label>
                                        @php
                                            $menu = DB::table('tb_menu')
                                                ->orderBy('kd_menu', 'desc')
                                                ->where('lokasi', $id_lokasi)
                                                ->first();
                                        @endphp
                                        <input readonly type="text" name="kd_menu" class="form-control"
                                            placeholder="Kode Menu" value="{{ $menu->kd_menu + 1 }}">
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label for="">
                                            <dt>Nama Menu</dt>
                                        </label>
                                        <input type="text" name="nm_menu" class="form-control"
                                            placeholder="Nama Menu">
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label for="">
                                            <dt>Tipe</dt>
                                        </label>
                                        <Select class="form-control select" name="tipe">
                                            <option value="">-Pilih tipe-</option>
                                            <option value="food">Food</option>
                                            <option value="drink">Drink</option>
                                        </Select>
                                    </div>

                                    <div class="col-lg-5 mb-2">
                                        <label for="">
                                            <dt>Distribusi</dt>
                                        </label>
                                        <select name="id_distribusi[]" id="" class="form-control select">
                                            <option value="">-Pilih distribusi-</option>
                                            <option value="1">DINE-IN / TAKEWAY</option>
                                            <option value="2">GOJEK</option>
                                            <option value="3">DELIVERY</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-5 mb-2">
                                        <label for="">
                                            <dt>Harga</dt>
                                        </label>
                                        <input type="text" name="harga[]" class="form-control" placeholder="Harga">
                                    </div>

                                    <div class="col-lg-2 mb-2">
                                        <label for="">
                                            <dt>Aksi</dt>
                                        </label> <br>
                                        <button href="" id="tambah_distribusi" type="button"
                                            class="btn btn-sm btn-info "><i class="fas fa-plus"></i></button>
                                    </div>

                                </div>
                                <div id="p_pakan">

                                </div>


                                <div class="row mt-2" x-data="{
                                    open: false
                                }">
                                    <div class="col-lg-4">
                                        <label for="checkResep" class="mt-2 ml-1">Tambah Resep</label>
                                        <label class="switch float-left">
                                            <input type="checkbox" class="checkResep" id="checkResep"
                                                x-on:click="open = !open" x-bind:open="checked">
                                            <span class="slider round"></span>
                                            <input name="monitor" class="swalDefaultSuccess form-password form-control"
                                                value="off" hidden>
                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <span x-show="open">
                                            <label for="">Import File Resep <a href="{{ route('formatResep') }}"
                                                    class="btn btn-primary btn-xs">Download Format</a></label>
                                            <input type="file" name="file" class="form-control">
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-costume" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-costume">Save</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- akhir tambah menu --}}
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {

                var count_monitoring = 1;
                $('#tambah_distribusi').click(function() {
                    count_monitoring = count_monitoring + 1;
                    // var no_nota_atk = $("#no_nota_atk").val();
                    var html_code = "<div class='row' id='row_stk" + count_monitoring + "'>";


                    html_code +=
                        '<div class="col-md-5 col-3"><div class="form-group"><select name="id_distribusi[]" id="" class="form-control select"><option value="">-Pilih distribusi-</option><option value="1">DINE-IN / TAKEWAY</option><option value="2">GOJEK</option><option value="3">DELIVERY</option></select></div></div>';

                    html_code +=
                        '<div class="col-md-5 col-3"><div class="form-group"><input type="text" name="harga[]" class="form-control hasil" id="hasil' +
                        count_monitoring + '" placeholder="Harga"></div></div>';

                    html_code +=
                        ' <div class="col-md-2 col-1"><button type="button" name="remove" data-row="row_stk' +
                        count_monitoring +
                        '" class="btn btn-danger btn-sm remove_stk"><i class="fas fa-minus"></i></button></div>';


                    html_code += "</div>";

                    $('#p_pakan').append(html_code);
                    $('.select').select2()
                });



                $(document).on('click', '.remove_stk', function() {
                    var delete_row = $(this).data("row");
                    $('#' + delete_row).remove();
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip()
                })
                $('.form-checkbox1').click(function() {

                    var id_checkbox = $(this).attr("id_checkbox");
                    // alert(id_checkbox)
                    if ($(this).is(':checked')) {
                        var nilai1 = 'on';
                        $('.nilai' + id_checkbox).val(nilai1);
                    } else {
                        var nilai1 = 'off';
                        $('.nilai' + id_checkbox).val(nilai1);
                    }
                    $.ajax({
                        method: "POST",
                        url: "{{ route('editMenuCheck') }}",
                        data: {
                            id_checkbox: id_checkbox,
                            nilai1: nilai1,
                            '_token': "{{ csrf_token() }}"
                        },

                        success: function(hasil) {
                            if (nilai1 == 'on') {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    icon: 'success',
                                    title: 'Data menu telah di on kan'
                                });
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    icon: 'error',
                                    title: 'Data menu telah di off kan'
                                });
                            }
                        }
                    });
                });

                // $(document).on('click', '.edit_menu', function() {
                //     var id_menu = $(this).attr("id_menu");
                //     $.ajax({
                //         url: "",
                //         method: "POST",
                //         data: {
                //             id_menu: id_menu,
                //         },
                //         success: function(data) {
                //             $('#menu').html(data);
                //         }
                //     });

                // });

                $(document).on('click', '.editMenu', function() {
                    var id_menu = $(this).attr("id_menu");
                    var id_lokasi = "{{ Request::get('id_lokasi') }}";
                    $('#edit_data').modal('show')

                    $.ajax({
                        url: "{{ route('editMenu') }}",
                        method: "GET",
                        data: {
                            id_menu: id_menu,
                            id_lokasi: id_lokasi,
                        },
                        success: function(data) {
                            $('#menu').html(data);
                            $('.select').select2()
                        }
                    });
                })


                $(document).on('click', '.btnPlusResep', function(e) {
                    e.preventDefault()
                    var id_menu = $(this).attr('id_menu')
                    $('#resepIdMenu').val(id_menu)
                    $("#resep").modal('show')
                })

                $(document).on('click', '.btnPlusDistribusi', function(e) {
                    e.preventDefault()
                    var id_menu = $(this).attr('id_menu')
                    $('#distribusiIdMenu').val(id_menu)
                    $("#distribusi").modal('show')
                })

            });
        </script>
    @endsection
