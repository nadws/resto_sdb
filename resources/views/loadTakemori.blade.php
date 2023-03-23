@php
        $l = 1;
        $ttl_kom = 0;
        
        foreach ($server as $k) {
            $o = $l++;
            $ttl_kom += $k->komisi;
        }
        
        $bagi_kom = $service->total;
        
        $service_charge = $service->total * 0.07;
        $orang = $o ?? 0;
        
        $kom = ((($service_charge / 7) * $persen->jumlah_persen) / $jumlah_orang->jumlah) * $orang;
    @endphp
<div class="card">
    <div class="card-header">

        <h5 style="font-weight: bold" class="text-center">
            Takemori
        </h5>
        <h5>Org p/r : {{ number_format($jumlah_orang->jumlah, 0) }} /
            {{ number_format($orang, 0) }} </h5>

        <h5>Service charge p/r :
            {{ number_format(($service_charge / 7) * $persen->jumlah_persen, 0) }} /
            {{ number_format($kom, 0) }}</h5>

    </div>

    <div class="card-body">
        @php
            $ttl_komisi = 0;
            foreach ($server as $k) {
                $ttl_komisi += $k->komisi;
            }
        @endphp

        <table width="100%" class="table table-responsive" id="table"
            style="font-size: 11px">
            <thead style="white-space: nowrap; ">
                <tr>
                    <th>#</th>
                    <th style="font-size: 10px;text-align: center">Nama</th>
                    <th style="font-size: 10px;text-align: right">Total Penjualan <br>
                        ({{ number_format($ttl_komisi, 0) }}) </th>
                    <th style="font-size: 10px;text-align: right">Kom Penjualan</th>
                    <th style="font-size: 10px;text-align: right">Kom Stk</th>
                    <th style="font-size: 10px;text-align: right">Kom Majo</th>
                    <th style="font-size: 10px;text-align: right">Kom KPI</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($server as $no => $k)
                    <tr>
                        <td>
                            {{ $no + 1 }}
                        </td>
                        <td>
                            {{ $k->nama }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($k->komisi, 0) }}
                        </td>
                        @php
                            $kom1 = $ttl_kom == '' ? '0' : ($kom / $bagi_kom) * $k->komisi;
                        @endphp
                        <td style="text-align: right">
                            {{ number_format($kom1, 0) }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format(round($k->kom, 0), 0) }}
                        </td>
                        @php
                            $komisiG = Http::get("https://majoo-laravel.putrirembulan.com/api/komisiGaji/1/$k->karyawan_majo/$tgl1/$tgl2");
                            $komaj = empty($komisiG['komisi']) ? 0 : $komisiG['komisi'][0]['dt_komisi'];
                        @endphp 
                        <td style="text-align: right">
                            {{ number_format($komaj, 0) }}
                        </td>
                        <td style="text-align: right">
                            {{-- {{ number_format($kom1, 0) }} --}}
                            -
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>
    </div>

</div>