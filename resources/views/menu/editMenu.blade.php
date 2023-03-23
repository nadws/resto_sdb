    <div class="row">
        <div class="col-sm-4 ol-md-6 col-xs-12 mb-2">
            <input type="hidden" name="id_menu" value="{{ $menu->id_menu }}">
            <input type="hidden" name="id_lokasi" value="{{ $id_lokasi }}">

            <label for="">Image</label>
            <br>
            <img width="270" src="https://upperclassindonesia.com/uploads/tb_menu/CHAWAN MUSHI 1.jpg" alt="">
            <br>
            <br>
            <input type="file" class="form-control" name="image">
            <input type="hidden" class="form-control" name="image2" value="CHAWAN MUSHI 1.jpg">
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6 mb-2">
                    <label for="">
                        <dt>Kategori</dt>
                    </label>
                    <select name="id_kategori" id="" class="form-control select">
                        @foreach ($kategori as $p)
                            <option value="{{ $p->kd_kategori }}"
                                {{ $p->kd_kategori == $menu->id_kategori ? 'selected' : '' }}>
                                {{ $p->kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-2">
                    <label for="">
                        <dt>Kode Menu</dt>
                    </label>
                    <input type="text" readonly name="kd_menu" class="form-control" placeholder="Kode Menu"
                        value="{{ $menu->kd_menu }}">
                </div>
                <div class="col-lg-6 mb-2">
                    <label for="">
                        <dt>Nama Menu</dt>
                    </label>
                    <input type="text" name="nm_menu" class="form-control" placeholder="Nama Menu"
                        value="{{ $menu->nm_menu }}">
                </div>
                <div class="col-lg-6 mb-2">
                    <label for="">
                        <dt>Tipe</dt>
                    </label>
                    <select class="form-control select" name="tipe">
                        <option value="food">food</option>
                        <option value="drink">drink</option>
                    </select>
                </div>
                @php
                    $harga = DB::table('tb_harga')
                        ->select('tb_harga.*', 'tb_distribusi.*')
                        ->join('tb_distribusi', 'tb_harga.id_distribusi', '=', 'tb_distribusi.id_distribusi')
                        ->where('id_menu', $menu->id_menu)
                        ->get();
                    $no = 1;
                    
                @endphp
                @foreach ($harga as $h)
                    <div class="col-lg-5 mb-2">
                        <label for="">
                            <input type="hidden" value="{{ $h->id_harga }}" name="id_harga[]">
                            <dt>Distribusi</dt>
                        </label>
                        <select name="id_distribusi[]" id="" class="form-control select">
                            @foreach ($distribusi as $d)
                                @if ($h->id_distribusi == $d->id_distribusi)
                                    <option selected value="{{ $h->id_distribusi }}">
                                        {{ $h->nm_distribusi }}
                                    </option>
                                @else
                                    <option value="{{ $d->id_distribusi }}">
                                        {{ $d->nm_distribusi }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-5 mb-2">
                        <label for="">
                            <dt>Harga</dt>
                        </label>
                        <input type="text" name="harga[]" class="form-control" placeholder="Harga"
                            value="{{ $h->harga }}">
                    </div>
                @endforeach
            </div>
            <hr>
            @php
                $resep = DB::table('tb_resep')->where('id_menu', $menu->id_menu)->get();
            @endphp
            
            @foreach ($resep as $r)
            <input type="hidden" name="id_resep[]" value="{{ $r->id_resep }}">
            <div class="row tbhBahanEdit">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="">Nama Bahan</label>
                        <select name="nm_bahan[]" id="" class="select selectBahan">
                            <option value="">- Pilih Bahan -</option>
                            @foreach ($bahan as $b)
                                <option {{$b->id_list_bahan == $r->id_list_bahan ? 'selected' : ''}} value="{{ $b->id_list_bahan }}">{{ ucwords($b->nm_bahan) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Satuan</label>
                        <select name="id_satuan[]" id="" class="select selectBahan">
                            <option value="">- Plih Bahan -</option>
                            @foreach ($satuan as $b)
                                <option {{$b->id_satuan == $r->id_satuan ? 'selected' : ''}} value="{{ $b->id_satuan }}">{{ ucwords($b->nm_satuan) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="">Qty</label>
                        <input type="number" name="grBahan[]" value="{{ $r->qty }}" class="form-control grBahan">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
