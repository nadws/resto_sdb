<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Harga;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $id_user = Auth::user()->id;
        $id_menus = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 11)->first();
        if (empty($id_menus)) {
            return back()->with('warning', 'Permission belum diatur');
        } else {
            if (Auth::user()->jenis == 'adm') {
                $id_lokasi = $request->id_lokasi;
                $data = [
                    'title' => 'Menu',
                    'logout' => $request->session()->get('logout'),
                    'menu1' => DB::raw("SELECT tb_menu.*, tb_kategori.* FROM tb_menu LEFT JOIN tb_kategori ON tb_menu.id_kategori = tb_kategori.kd_kategori WHERE tb_menu.lokasi = $id_lokasi "),
                    'menu' => DB::table('tb_menu')->select('tb_menu.*', 'tb_kategori.*')->join('tb_kategori', 'tb_menu.id_kategori', '=', 'tb_kategori.kd_kategori')->where('tb_menu.lokasi', $id_lokasi)->orderBy('tb_menu.id_menu', 'DESC')->get(),

                    'kategori' => DB::table('tb_kategori')->get(),
                    'distribusi' => Distribusi::all(),
                    'id_lokasi' => $id_lokasi,
                    'bahan' => DB::table('tb_list_bahan')->where([['id_lokasi', $id_lokasi], ['jenis', 1]])->get(),
                    'satuan' => DB::table('tb_satuan')->whereIn('id_satuan', [12, 18, 22, 24, 25, 26])->get()
                ];

                return view("menu.menu", $data);
            } else {
                return back();
            }
        }
    }

    public function importMenu(Request $request)
    { {
            // include APPPATH.'third_party/PHPExcel/PHPExcel.php';
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file);
            // $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
            // $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $lokasi = $request->id_lokasi;


            $data = array();
            $numrow = 1;
            // cek
            $cek = 0;
            foreach ($sheet as $row) {

                if ($row['A'] == "" && $row['B'] == "" && $row['C'] == "" && $row['D'] == "" && $row['E'] == "" && $row['F'] == "")
                    continue;
                $numrow++; // Tambah 1 setiap kali looping
            }
            // endcek



            $kmenu = Menu::orderBy('kd_menu', 'desc')->where('lokasi', $lokasi)->first();

            $kd_menu = $kmenu->kd_menu + 1;

            foreach ($sheet as $row) {
                if ($row['A'] == "" && $row['B'] == "" && $row['C'] == "" && $row['D'] == "" && $row['E'] == "" && $row['F'] == "")
                    continue;


                if ($numrow > 1) {

                    $data = [
                        'id_kategori' => $row['A'],
                        'kd_menu' => $kd_menu++,
                        'nm_menu' => $row['B'],
                        'tipe' => $row['C'],
                        'aktif' => 'on',
                        'lokasi' => $lokasi,
                    ];
                    $menu = Menu::create($data);

                    if ($row['D'] == '') {
                        # code...
                    } else {
                        $data2 = [
                            'id_menu' => $menu->id,
                            'id_distribusi' => '1',
                            'harga' => $row['D']
                        ];
                        Harga::create($data2);
                    }

                    if ($row['E'] == '') {
                        # code...
                    } else {
                        $data3 = [
                            'id_menu' => $menu->id,
                            'id_distribusi' => '3',
                            'harga' => $row['E']
                        ];
                        Harga::create($data3);
                    }

                    if ($row['F'] == '') {
                        # code...
                    } else {
                        $data4 = [
                            'id_menu' => $menu->id,
                            'id_distribusi' => '2',
                            'harga' => $row['F']
                        ];
                        Harga::create($data4);
                    }
                }
                $numrow++; // Tambah 1 setiap kali looping
            }

            return redirect()->route('menu', ['id_lokasi' => 1])->with('sukses', 'Data berhasil Diimport');
        }
    }

    public function exportMenu(Request $request)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:D4')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // lebar kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(13);
        // header text
        $sheet
            ->setCellValue('A1', 'KODE KATEGORI')
            ->setCellValue('B1', 'NAMA MENU')
            ->setCellValue('C1', 'TIPE(FOOD/DRINK)')
            ->setCellValue('D1', 'DINE IN')
            ->setCellValue('E1', 'DELIVERY')
            ->setCellValue('F1', 'GOJEK');

        $sheet
            ->setCellValue('A2', '3')
            ->setCellValue('B2', 'Sosis Ayam')
            ->setCellValue('C2', 'food')
            ->setCellValue('D2', '30000')
            ->setCellValue('E2', '30000')
            ->setCellValue('F2', '40000');

        $sheet
            ->setCellValue('I1', 'TAKEMORI')
            ->mergeCells('I1:J1')
            ->setCellValue('I2', 'KODE KATEGORI')
            ->setCellValue('J2', 'KATEGORI');

        $sheet
            ->setCellValue('M1', 'SOONDOBU')
            ->mergeCells('M1:N1')
            ->setCellValue('M2', 'KODE KATEGORI')
            ->setCellValue('N2', 'KATEGORI');

        $tkm = DB::table('tb_kategori')->where('lokasi', "TAKEMORI")->get();
        $sdb = DB::table('tb_kategori')->where('lokasi', "SOONDOBU")->get();
        $kolom = 3;
        foreach ($tkm as $k) {
            $sheet
                ->setCellValue('I' . $kolom, $k->kd_kategori)
                ->setCellValue('J' . $kolom, $k->kategori);
            $kolom++;
        }

        $kol = 3;
        foreach ($sdb as $k) {
            $sheet
                ->setCellValue('M' . $kol, $k->kd_kategori)
                ->setCellValue('N' . $kol, $k->kategori);
            $kol++;
        }

        $writer = new Xlsx($spreadsheet);
        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        // tambah style
        $sheet->getStyle('A1:F10')->applyFromArray($style);
        $batas = count($tkm) + 2;
        $sheet->getStyle('I1:J' . $batas)->applyFromArray($style);
        $batas = count($sdb) + 2;
        $sheet->getStyle('M1:N' . $batas)->applyFromArray($style);
        // center
        $sheet->getStyle('I1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('M1')->getAlignment()->setHorizontal('center');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Menu.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function addMenu(Request $request)
    {
        

        $menu = Menu::orderBy('kd_menu', 'desc')->where('lokasi', $request->id_lokasi)->first();

        $kd_menu = $menu->kd_menu + 1;

        if ($request->hasFile('image')) {
            $request->file('image')->move('assets/tb_menu/', $request->file('image')->getClientOriginalName());
            $foto = $request->file('image')->getClientOriginalName();
        } else {
            $foto = '';
        }

        $data1 = [

            'id_kategori' => $request->id_kategori,
            'kd_menu' => $kd_menu,
            'nm_menu' => $request->nm_menu,
            'id_kategori' => $request->id_kategori,
            'tipe' => $request->tipe,
            'image' => $foto,
            'lokasi' => $request->id_lokasi,
            'aktif' => 'on',

        ];


        $menu = Menu::create($data1);
        $id_menu = $menu->id;


        $id_distribusi = $request->id_distribusi;
        $harga = $request->harga;
        for ($i = 0; $i < count($request->id_distribusi); $i++) {
            $data2 = [
                'id_menu' => $id_menu,
                'id_distribusi' => $id_distribusi[$i],
                'harga' => $harga[$i],
            ];

            Harga::create($data2);
        }

        // resep
        $file = $request->file('file');
        if(!empty($file)) {
            $fileDiterima = ['xls', 'xlsx'];
            $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
            if ($cek) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
                $spreadsheet = $reader->load($file);
                $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $data = [];
    
                $numrow = 1;
    
                foreach ($sheet as $row) {
                    if ($row['A'] == '' && $row['B'] == '') {
                        continue;
                    }
                    if ($numrow > 1) {
                        $dihalusi = Str::lower($row['A']);
                        $bahan = DB::selectOne("SELECT a.id_list_bahan, a.nm_bahan, a.id_satuan FROM `tb_list_bahan` as a
                        WHERE a.nm_bahan = '$dihalusi'");
                        if(!empty($bahan)) {
                            $data = [
                                'id_menu' => $id_menu,
                                'id_list_bahan' => $bahan->id_list_bahan,
                                'id_satuan' => $bahan->id_satuan,
                                'qty' => $row['B'],
                                'admin' => Auth::user()->nama,
                                'tgl' => date('Y-m-d')
                            ];
                            DB::table('tb_resep')->insert($data);
                        } else {
                            return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('error', 'Berhasiil tambah Menu tapi RESEP TIDAK ADA');
                        }
                        
                    }
                    $numrow++;
                }
                return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('sukses', 'Berhasiil tambah Menu');
            } else {
                return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('error', 'File tidak didukung');
            }
        }
        return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('sukses', 'Berhasiil tambah Menu tanpa resep');

    }

    public function deleteMenu(Request $request)
    {
        Menu::where('id_menu', $request->id_menu)->delete();
        Harga::where('id_menu', $request->id_menu)->delete();
        return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('error', 'Berhasiil Hapus Menu');
    }

    public function updateMenu(Request $request)
    {

        $data1 = [
            'id_kategori' => $request->id_kategori,
            'kd_menu' => $request->kd_menu,
            'nm_menu' => $request->nm_menu,
            'id_kategori' => $request->id_kategori,
            'tipe' => $request->tipe,
            'lokasi' => $request->id_lokasi,
            'aktif' => 'on',

        ];
        $menu = Menu::where('id_menu', $request->id_menu)->update($data1);
        $id_menu = $request->id_menu;
        $id_distribusi = $request->id_distribusi;
        $harga = $request->harga;
        for ($i = 0; $i < count($id_distribusi); $i++) {
            $data2 = [
                'id_menu' => $id_menu,
                'id_distribusi' => $id_distribusi[$i],
                'harga' => $harga[$i],
            ];
            // dd($data2);
            Harga::where('id_harga', $request->id_harga[$i])->update($data2);
        }

        for ($i = 0; $i < count($request->nm_bahan); $i++) {
            $data = [
                'id_menu' => $id_menu,
                'id_list_bahan' => $request->nm_bahan[$i],
                'id_satuan' => $request->id_satuan[$i],
                'qty' => $request->grBahan[$i],
                'admin' => Auth::user()->nama,
                'qty' => $request->grBahan[$i],
                'tgl' => date('Y-m-d')
            ];
            DB::table('tb_resep')->where('id_resep', $request->id_resep[$i])->update($data);
        }
        return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('sukses', 'Berhasiil ubah Menu');
    }

    public function editMenuCheck(Request $request)
    {
        $id = $request->id_checkbox;
        $nilai1 = $request->nilai1;


        $data = [
            'aktif' => $nilai1
        ];
        Menu::where('id_menu', $id)->update($data);
    }

    public function plusDistribusi(Request $request)
    {
        $id_distribusi = $request->id_distribusi;
        $id_menu = $request->id_menu;
        $cek = Harga::where('id_menu', $id_menu)->where('id_distribusi', $id_distribusi)->first();
        if ($cek == '') {
            $data = [
                'id_distribusi' => $id_distribusi,
                'id_menu' => $id_menu,
                'harga' => $request->harga,
            ];

            Harga::create($data);
            return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('sukses', 'Berhasil Tambah Harga');
        } else {
            return back()->with('error', 'Distribusi sudah ada');
        }
    }
    public function tbhResep(Request $r)
    {
        $data = [
            'c' => $r->c,
            'bahan' => DB::table('tb_list_bahan')->where([['id_lokasi', $r->id_lokasi], ['jenis', 1]])->get(),
            'satuan' => DB::table('tb_satuan')->whereIn('id_satuan', [12, 18, 22, 24, 25, 26])->get()
        ];
        return view('menu.tbhBahan', $data);
    }

    public function editMenu(Request $r)
    {
        $data = [
            'id_menu' => $r->id_menu,
            'menu' => DB::table('tb_menu')->select('tb_menu.*', 'tb_kategori.*')->join('tb_kategori', 'tb_menu.id_kategori', '=', 'tb_kategori.kd_kategori')->where('tb_menu.id_menu', $r->id_menu)->orderBy('tb_menu.id_menu', 'DESC')->first(),
            'kategori' => DB::table('tb_kategori')->get(),
            'distribusi' => Distribusi::all(),
            'id_lokasi' => $r->id_lokasi,
            'bahan' => DB::table('tb_list_bahan')->where([['jenis', 1], ['id_lokasi', 1]])->get(),
            'satuan' => DB::table('tb_satuan')->whereIn('id_satuan', [12, 18, 22, 24, 25, 26])->get()
        ];
        return view('menu.editMenu', $data);
    }

    public function plusResep(Request $request)
    {
        // resep
        $file = $request->file('file');
        $fileDiterima = ['xls', 'xlsx'];
        $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
        if ($cek) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $data = [];

            $numrow = 1;

            foreach ($sheet as $row) {
                if ($row['A'] == '' && $row['B'] == '') {
                    continue;
                }
                if ($numrow > 1) {
                    $dihalusi = Str::lower($row['A']);
                    $bahan = DB::selectOne("SELECT a.id_list_bahan, a.nm_bahan, a.id_satuan FROM `tb_list_bahan` as a
                    WHERE a.nm_bahan = '$dihalusi'");
                    if(!empty($bahan)) {
                        $data = [
                            'id_menu' => $request->id_menu,
                            'id_list_bahan' => $bahan->id_list_bahan,
                            'id_satuan' => $bahan->id_satuan,
                            'qty' => $row['B'],
                            'admin' => Auth::user()->nama,
                            'tgl' => date('Y-m-d')
                        ];
                        DB::table('tb_resep')->insert($data);
                    } else {
                        return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('error', 'Berhasiil tambah Menu tapi RESEP TIDAK ADA');
                    }
                    
                }
                $numrow++;
            }
            return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('sukses', 'Berhasiil tambah Menu');
        } else {
            return redirect()->route('menu', ['id_lokasi' => $request->id_lokasi])->with('error', 'File tidak didukung');
        }
    }

    public function formatResep()
    {

        $spreadsheet = new Spreadsheet();
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getActiveSheet());
        $spreadsheet->removeSheetByIndex($sheetIndex);

        $sheet1 = $spreadsheet->createSheet();
        $sheet1->setTitle("Format Import Resep");

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle("List Bahan & Satuan");

        // field -------------------
        // format resep
        $sheet1->setCellValue('A1', 'Nama Bahan')
            ->setCellValue('B1', 'Qty');

        // tb list bahan & satuan
        $sheet2->setCellValue('A1', 'ID Bahan')
            ->setCellValue('B1', 'Nama Bahan')
            ->setCellValue('C1', 'Satuan')

            ->setCellValue('F1', 'TB Satuan')
            ->setCellValue('G1', 'ID Satuan')
            ->setCellValue('H1', 'Satuan');
        // end field -----------------------------

        // isi kolom -----------------------
        $bahan = DB::select("SELECT b.id_bahan, a.nm_bahan, c.nm_satuan, b.debit, b.kredit FROM `tb_list_bahan` as a
            LEFT JOIN stok_ts as b ON a.id_list_bahan = b.id_bahan
            LEFT JOIN tb_satuan as c ON a.id_satuan = c.id_satuan GROUP BY a.id_list_bahan");

        $satuan = DB::table('tb_satuan')->get();
        $kol = 2;
        foreach ($bahan as $b) {
            $sheet2->setCellValue("A$kol", $b->id_bahan)
                ->setCellValue("B$kol", $b->nm_bahan)
                ->setCellValue("C$kol", $b->nm_satuan);
            $kol++;
        }

        $kol2 = 2;
        foreach ($satuan as $d) {
            $sheet2->setCellValue("G$kol2", $d->id_satuan);
            $sheet2->setCellValue("H$kol2", $d->nm_satuan);
            $kol2++;
        }
        // end kolom ------------------------

        // style excel ---------------
        $sheet1->getColumnDimension('A')->setWidth(15);
        $sheet1->getColumnDimension('B')->setWidth(20);
        $sheet1->getColumnDimension('C')->setWidth(15);

        // font bold
        $sheet1->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet2->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet2->getStyle('F1:H1')->getFont()->setBold(true);

        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        // style bold baris
        $sheet1->getStyle('A1:C2')->applyFromArray($style);
        $batas1 = count($bahan) + 1;
        $sheet2->getStyle('A1:C' . $batas1)->applyFromArray($style);

        $batas2 = count($satuan) + 1;
        $sheet2->getStyle('G1:H' . $batas2)->applyFromArray($style);

        // center
        $sheet1->getStyle('A1:C2')->getAlignment()->setHorizontal('center');
        // end style ------------------

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Resep Menu.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
