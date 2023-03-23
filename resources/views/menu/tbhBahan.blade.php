<div class="row tbhBahanEdit" id="row{{$c}}">
    <div class="col-lg-5">
        <div class="form-group">
            <label for="">Nama Bahan</label>
            <select name="nm_bahan[]" id="" class="select selectBahan">
                <option value="">- Pilih Bahan -</option>
                @foreach ($bahan as $b)
                    <option value="{{ $b->id_list_bahan }}">{{ ucwords($b->nm_bahan) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Satuan</label>
            <select name="id_satuan[]" id="" class="select selectBahan">
                <option value="">- Pilih Bahan -</option>
                @foreach ($satuan as $b)
                    <option value="{{ $b->id_satuan }}">{{ ucwords($b->nm_satuan) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Qty</label>
            <input type="number" name="grBahan[]" class="form-control grBahan">
        </div>
    </div>
    <div class="col-lg-2 mb-2">
        <label for="">Aksi</label><br>
        <button class="btn btn-sm btn-danger removeResep" data-row="{{$c}}" type="button"><i class="fas fa-minus"></i> Resep</button>
    </div>
</div>