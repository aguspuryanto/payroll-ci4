<?php

namespace App\Controllers\payroll;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusHistoriModel;
use App\Models\BonStatusModel;
use App\Models\KasModel;
use App\Models\PayrollModel;
use App\Models\PayrollDetilModel;
use App\Models\PTModel;
use App\Models\PinjamanTagihanModel;
use App\Models\PTKaryawanModel;

use CodeIgniter\Files\File;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Daftar extends BaseController {

  protected $data;
  private $bonModel;
  private $bonStatusModel;
  private $bonStatusHistoriModel;
  private $kasModel;
  private $PTModel;
  private $payrollModel;
  private $payrollDetilModel;
  private $pinjamanTagihanModel;
  private $ptKaryawanModel;
  private $spreadsheet;

  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusModel = new BonStatusModel();
    $this->bonStatusHistoriModel = new BonStatusHistoriModel();
    $this->kasModel = new KasModel();
    $this->PTModel = new PTModel();
    $this->payrollModel = new PayrollModel();
    $this->payrollDetilModel = new PayrollDetilModel();
    $this->pinjamanTagihanModel = new PinjamanTagihanModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false }); 
        $('.daterange').daterangepicker({ 
          'autoApply': true, 
          'drops': 'auto' 
        });  
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus payroll \'<i>'+kode+'</i>\' ?');
        });

        $('#datatable').parent().addClass('table-responsive');
        $('#datatable').parent().parent().addClass('pe-2');
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      $where = array('kode_payroll'=>$kode, 'status'=>"Tagihan");
      $payrolls = $this->payrollModel->where($where)->find();
      if(!empty($payrolls)) {
        $this->payrollModel->delete($kode);
        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Payroll telah dihapus"));
      } else {
        $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll gagal dihapus"));
      }
      return redirect()->to(base_url('payroll/daftar'));
    }

    $where = "";

    $this->data['status'] = $this->request->getGet('status');
    $periode = $this->request->getGet('periode');
    $this->data['abaikan'] = $this->request->getGet('abaikan');
    if(!empty($periode)) {
      $this->data['tglawal'] = substr($periode, 0, 10);
      $this->data['tglakhir'] = substr($periode, 13, 10);
    } else {
      $this->data['tglawal'] = date("m/01/Y");
      $this->data['tglakhir'] = date("m/t/Y");
    }
    if($this->data['status']!="" ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "payroll.status = '".$this->data['status']."'";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(payroll.tanggal_pengajuan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "payroll.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }
    $this->data['payrolls']=$this->payrollModel->getPayroll($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('payroll/v_daftar', $this->data);    
    echo view('payroll/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function ajaxPayroll() {
    if($this->request->isAjax()) {
      $hasil = "<tr><td colspan='22' class='fst-italic'>Belum ada data</td></tr>"; 
      $footer="<tr>
                <td class='text-end' colspan='4'>TOTAL</td>
                <td class='text-end footer-total' tag='gaji_pokok'>0</td>
                <td class='text-end footer-total' tag='gaji_jabatan_tetap'>0</td>
                <td class='text-end footer-total' tag='tunjangan'>0</td>
                <td class='text-end footer-total' tag='lembur'>0</td>
                <td class='text-end footer-total' tag='lainlain'>0</td>
                <td class='text-end footer-total' tag='thr'>0</td>
                <td class='text-end footer-total' tag='gratifikasi'>0</td>
                <td class='text-end footer-total' tag='rapel'>0</td>
                <td class='text-end footer-total' tag='total'>0</td>
                <td class='text-end footer-total' tag='bpjs_kes'>0</td>
                <td class='text-end footer-total' tag='bpjs_tk'>0</td>
                <td class='text-end footer-total' tag='pinjaman'>0</td>
                <td class='text-end footer-total' tag='nett'>0</td>
                <td class='text-end footer-total' tag='bpjs_kes_pt'>0</td>
                <td class='text-end footer-total' tag='bpjs_jkm_pt'>0</td>
                <td class='text-end footer-total' tag='bpjs_jkk_pt'>0</td>
                <td class='text-end footer-total' tag='bpjs_jp_pt'>0</td>
                <td class='text-end footer-total' tag='bpjs_jht_pt'>0</td>
              </tr>";

      $arr_total = array(
              "gaji_pokok"=>0, "gaji_jabatan_tetap"=>0, "tunjangan"=>0, "lembur"=>0, "lainlain"=>0,
              "thr"=>0, "gratifikasi"=>0, "rapel"=>0, "total"=>0, "bpjs_kes"=>0, "bpjs_tk"=>0,
              "pinjaman"=>0, "gaji_nett"=>0, "bpjs_kes_pt"=>0, "bpjs_jkm_pt"=>0, "bpjs_jkk_pt"=>0, "bpjs_jp_pt"=>0, "bpjs_jht_pt"=>0
            );

      $result = 1; $jumlah=0;
      $kode_pt = $this->request->getPost('kode_pt');
      $where = "payroll.kode_pt = '$kode_pt' AND month(payroll.tanggal_pengajuan)=".date("m");
      $payroll = $this->payrollModel->getPayroll($where)->first();

      if(!empty($payroll)) {
        $result = 0;
      } else {
        $where = array("pt_karyawan.isaktif"=>1,'pt.kode_pt'=>$kode_pt);
        $karyawans = $this->ptKaryawanModel->getPTKaryawan($where, 'pt_karyawan.no_urut, pt_karyawan.nama_lengkap')->find();
        if(!empty($karyawans)) {
          $hasil = ""; $jumlah = count($karyawans);
          foreach($karyawans as $karyawan) {
            $total = $karyawan->gaji_pokok + $karyawan->gaji_jabatan_tetap;
            
            $where = array('bon.kode_bon_status >'=>0, 'bon.kode_bon_status <'=>5, 'bon.kode_pt_karyawan'=>$karyawan->kode_pt_karyawan, 'pinjaman_tagihan.status'=>'Tagihan');
            //$pinjaman = $this->pinjamanTagihanModel->getPinjamanTagihan($where)->first();
            $pinjaman = array();
            $nilai_pinjaman = empty($pinjaman) ? 0 : $pinjaman->nominal;
            $kode_pinjaman_tagihan = empty($pinjaman) ? "" : $pinjaman->kode_pinjaman_tagihan;

            $nett = $total - $nilai_pinjaman;

            $hasil .= "<tr>";
              $hasil .= "<td>$karyawan->no_urut</td>";
              $hasil .= "<td>$karyawan->nik<input type='hidden' class='nik' nik='$karyawan->nik' value='$karyawan->kode_pt_karyawan'></td>";
              $hasil .= "<td>$karyawan->nama_lengkap</td>";
              $hasil .= "<td>$karyawan->jabatan</td>";
              $hasil .= "<td class='text-end'><span class='gaji_pokok' kode='$karyawan->kode_pt_karyawan'>".number_format($karyawan->gaji_pokok, 0, ".", ",")."</span><input type='hidden' name='gaji_pokok[$karyawan->kode_pt_karyawan]' value='$karyawan->gaji_pokok'></td>";
                        $arr_total["gaji_pokok"]+=$karyawan->gaji_pokok;
              $hasil .= "<td class='text-end'><span class='gaji_jabatan_tetap' kode='$karyawan->kode_pt_karyawan'>".number_format($karyawan->gaji_jabatan_tetap, 0, ".", ",")."</span><input type='hidden' name='gaji_jabatan_tetap[$karyawan->kode_pt_karyawan]' value='$karyawan->gaji_jabatan_tetap'></td>";
                        $arr_total["gaji_jabatan_tetap"]+=$karyawan->gaji_jabatan_tetap;
              $hasil .= "<td><input type='text' tag='tunjangan' class='form-control number tunjangan text-end payroll' name='tunjangan[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='lembur' class='form-control number lembur text-end payroll' name='lembur[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='lainlain' class='form-control number lainlain text-end payroll' name='lainlain[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='thr' class='form-control number thr text-end payroll' name='thr[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='gratifikasi' class='form-control number gratifikasi text-end payroll' name='gratifikasi[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='rapel' class='form-control number rapel text-end payroll' name='rapel[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td class='text-end'><span class='total' kode='$karyawan->kode_pt_karyawan'>".number_format($total, 0, ".", ",")."</span></td>";
                        $arr_total["total"]+=$total;
              $hasil .= "<td><input type='text' tag='bpjs_kes' class='form-control number bpjs_kes text-end bpjs' name='bpjs_kes[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='bpjs_tk' class='form-control number bpjs_tk text-end bpjs' name='bpjs_tk[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";

              $hasil .= "<td class='text-end'><span class='pinjaman' kode='$karyawan->kode_pt_karyawan'>".number_format($nilai_pinjaman, 0, ".", ",")."</span><input type='hidden' name='pinjaman[$karyawan->kode_pt_karyawan]' value='$nilai_pinjaman'><input type='hidden' value='$kode_pinjaman_tagihan' name='kode_pinjaman_tagihan[$karyawan->kode_pt_karyawan]'></td>";
                        $arr_total["pinjaman"]+=$nilai_pinjaman;
              $hasil .= "<td class='text-end'><span class='nett' kode='$karyawan->kode_pt_karyawan'>".number_format($nett, 0, ".", ",")."</span></td>";
                        $arr_total["gaji_nett"]+=$nett;
              $hasil .= "<td><input type='text' tag='bpjs_kes_pt' class='form-control number bpjs_kes_pt text-end bpjs_pt' name='bpjs_kes_pt[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='bpjs_jkm_pt' class='form-control number bpjs_jkm_pt text-end bpjs_pt' name='bpjs_jkm_pt[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='bpjs_jkk_pt' class='form-control number bpjs_jkk_pt text-end bpjs_pt' name='bpjs_jkk_pt[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='bpjs_jp_pt' class='form-control number bpjs_jp_pt text-end bpjs_pt' name='bpjs_jp_pt[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
              $hasil .= "<td><input type='text' tag='bpjs_jht_pt' class='form-control number bpjs_jht_pt text-end bpjs_pt' name='bpjs_jht_pt[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";
            $hasil .= "</tr>";
          }

          $footer="<tr>
                  <td class='text-end' colspan='4'>TOTAL</td>
                  <td class='text-end footer-total' tag='gaji_pokok'>".number_format($arr_total["gaji_pokok"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='gaji_jabatan_tetap'>".number_format($arr_total["gaji_jabatan_tetap"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='tunjangan'>".number_format($arr_total["tunjangan"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='lembur'>".number_format($arr_total["lembur"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='lainlain'>".number_format($arr_total["lainlain"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='thr'>".number_format($arr_total["thr"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='gratifikasi'>".number_format($arr_total["gratifikasi"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='rapel'>".number_format($arr_total["rapel"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='total'>".number_format($arr_total["total"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_kes'>".number_format($arr_total["bpjs_kes"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_tk'>".number_format($arr_total["bpjs_tk"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='pinjaman'>".number_format($arr_total["pinjaman"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='nett'>".number_format($arr_total["gaji_nett"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_kes_pt'>".number_format($arr_total["bpjs_kes_pt"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_jkm_pt'>".number_format($arr_total["bpjs_jkm_pt"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_jkk_pt'>".number_format($arr_total["bpjs_jkk_pt"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_jp_pt'>".number_format($arr_total["bpjs_jp_pt"],0,".",",")."</td>
                  <td class='text-end footer-total' tag='bpjs_jht_pt'>".number_format($arr_total["bpjs_jht_pt"],0,".",",")."</td>
                </tr>";
        }
      }

      $output = array ("body"=>$hasil, "footer"=>$footer, "hasil"=>$result, "jumlah"=>$jumlah);

      echo json_encode($output);
    }
  }

  private function jquery() {
    $jquery = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});

      function updateFooterTotal() {
        var total=0;
        $('.total').each(function(){
          total += $(this).html().replace(/,(?=\d{3})/g, '')*1;
          //console.log($(this).html());
        });
        $('.footer-total[tag=total]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
      }
      function updateFooterGajiNett() {
        var total=0;
        $('.nett').each(function(){
          total += $(this).html().replace(/,(?=\d{3})/g, '')*1;
          //console.log($(this).html());
        });
        $('.footer-total[tag=nett]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
      }
      function updateFooterBPJS() {
        var total=0;
        $('.bpjs[tag=bpjs_kes]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_kes]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        var total=0;
        $('.bpjs[tag=bpjs_tk]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_tk]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
      }
      function updateFooterBPJSPT() {
        var total=0;
        $('.bpjs_pt[tag=bpjs_kes_pt]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_kes_pt]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        var total=0;
        $('.bpjs_pt[tag=bpjs_jkm_pt]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_jkm_pt]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        var total=0;
        $('.bpjs_pt[tag=bpjs_jkm_pt]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_jkm_pt]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        var total=0;
        $('.bpjs_pt[tag=bpjs_jkk_pt]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_jkk_pt]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        var total=0;
        $('.bpjs_pt[tag=bpjs_jp_pt]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_jp_pt]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        var total=0;
        $('.bpjs_pt[tag=bpjs_jht_pt]').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag=bpjs_jht_pt]').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
      }

      function updateFooterPayroll(tag) {
        var total=0;
        $('.payroll[tag='+tag+']').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag='+tag+']').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
        updateFooterTotal();
        updateFooterGajiNett()
      }
      
      $('body').on('keyup', 'input.number', function(){
        var kode_karyawan = $(this).attr('kode');

        var total = $('.gaji_pokok[kode='+kode_karyawan+']').html().replace(/,(?=\d{3})/g, '')*1;
        total += $('.gaji_jabatan_tetap[kode='+kode_karyawan+']').html().replace(/,(?=\d{3})/g, '')*1;
        $('.payroll[kode='+kode_karyawan+']').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.total[kode='+kode_karyawan+']').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        total -= $('.bpjs_kes[kode='+kode_karyawan+']').val().replace(/,(?=\d{3})/g, '')*1; 
        total -= $('.bpjs_tk[kode='+kode_karyawan+']').val().replace(/,(?=\d{3})/g, '')*1;
        total -= $('.pinjaman[kode='+kode_karyawan+']').html().replace(/,(?=\d{3})/g, '')*1;
        $('.nett[kode='+kode_karyawan+']').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        updateFooterPayroll($(this).attr('tag'));
        updateFooterBPJS();
        updateFooterBPJSPT();
      });
    ";

    return $jquery;
  }

  private function mulaiUpload() {
    $file_excel = $this->request->getFile('file');
    $render = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    $spreadsheet = $render->load($file_excel);
    $data = $spreadsheet->getActiveSheet()->toArray();
    /*foreach($data as $idx => $rows) {
      if ($idx == 0) continue;
      
    }*/
    return $data;
  }

  public function ajaxUploadCSV() {
    if($this->request->isAjax()) {
      $hasil = array("result"=>0, "data"=>"Format file tidak sesuai");
      $kode_pt = $this->request->getPost('kode');
      if (in_array($_FILES['file']['type'], array('text/csv', 'application/vnd.ms-excel'))) {
        $hasil["result"]=1;
        $hasil["data"] = $this->mulaiUpload();
      }
      //echo $kode_pt;
      echo json_encode($hasil);
    }
  }

  public function tambah() {
    if(!in_array("ATBHPRL", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('payroll/daftar'));

    $this->data['jquery'] = $this->jquery()."
      $('body').on('click', '#btnSimpanUpload', function(){
        var formData = new FormData($('#frmUpload')[0]);
        $.ajax({
          url: '".site_url('payroll/ajaxUploadCSV')."',
          type: 'POST',
          data: formData,
          async: false,
          cache: false,
          contentType: false,
          enctype: 'multipart/form-data',
          processData: false,
          success: function (response) {
            var hasil = JSON.parse(response);
            if(hasil.result=='1') {
              var payrolls = hasil.data;
              console.log(payrolls, 'payrolls');
              $.each(payrolls, function(i, item){
                if(i==0) return true;
                var nik=item[1];
                console.log(nik, 'nik');
                //var gaji_pokok = item[2];
                //var gaji_jabatan_tetap = item[3];
                var tunjangan = item[4];
                var lembur = item[5];
                var lainlain = item[6];
                var thr = item[7];
                var gratifikasi = item[8];
                var rapel = item[9];
                var bpjs_kes = item[10];
                var bpjs_tk = item[11];
                var bpjs_kes_pt = item[12];
                var bpjs_tk_jkm = item[13];
                var bpjs_tk_jkk = item[14];
                var bpjs_tk_jp = item[15];
                var bpjs_tk_jht = item[16];

                var kode_kary_pt = $('.nik[nik='+nik+']').val();

                $('.tunjangan[kode='+kode_kary_pt+']').val(tunjangan);
                $('.lembur[kode='+kode_kary_pt+']').val(lembur);
                $('.lainlain[kode='+kode_kary_pt+']').val(lainlain);
                $('.thr[kode='+kode_kary_pt+']').val(thr);
                $('.gratifikasi[kode='+kode_kary_pt+']').val(gratifikasi);
                $('.rapel[kode='+kode_kary_pt+']').val(rapel);
                $('.bpjs_kes[kode='+kode_kary_pt+']').val(bpjs_kes);
                $('.bpjs_tk[kode='+kode_kary_pt+']').val(bpjs_tk);
                $('.bpjs_kes_pt[kode='+kode_kary_pt+']').val(bpjs_kes_pt);
                // $('.bpjs_tk_pt[kode='+kode_kary_pt+']').val(bpjs_tk);

                $('.bpjs_jkm_pt[kode='+kode_kary_pt+']').val(bpjs_tk_jkm);
                $('.bpjs_jkk_pt[kode='+kode_kary_pt+']').val(bpjs_tk_jkk);
                $('.bpjs_jp_pt[kode='+kode_kary_pt+']').val(bpjs_tk_jp);
                $('.bpjs_jht_pt[kode='+kode_kary_pt+']').val(bpjs_tk_jht);
              });
              $('input.number').keyup();
            }
          }
        });
      });   

      $('body').on('change', '#pt', function(){
        $('.upload').addClass('disabled');
        var kode_pt = $(this).val();
        if(kode_pt.length>0) $('.upload').removeClass('disabled');

        $('#hid-upload-kode').val(kode_pt);
        var nama_pt = $('#pt option:selected').text();

        $('.error').html('');
        $('#simpan').addClass('disabled');

        $('#label-upload').html('Upload file CSV untuk '+nama_pt);
        $.post('".site_url("payroll/ajaxPayroll")."', {kode_pt: kode_pt})
        .done(function(data){
          var output = JSON.parse(data);
          $('.body-payroll').html(output.body);
          $('.footer-payroll').html(output.footer);

          if(output.hasil=='1') {            
            $('input.number').priceFormat({centsLimit: 0, prefix: ''});
            if(output.jumlah>0) $('#simpan').removeClass('disabled');
          } else {
            $('.error').html('Payroll sudah ada di bulan ini');
          }
        });
      });
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'pt'  => [
            'label' => 'PT',
            'rules' => 'required',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $pt = $this->request->getPost('pt');
        $tanggal_pengajuan = $this->request->getPost('tanggal_pengajuan');
        $tanggal_pengajuan = date("Y-m-d", strtotime($tanggal_pengajuan));
        
        $arr_data = array('kode_pt'=>$pt
            , 'tanggal_pengajuan' => $tanggal_pengajuan
          ); 

        $this->payrollModel->insert($arr_data);
        $kode_payroll = $this->payrollModel->getInsertID();

        if($kode_payroll) {
          $data = $this->request->getPost();
          // echo json_encode($data); die;
          $arr["gaji_pokok"] = $this->request->getPost('gaji_pokok');
          $arr["gaji_jabatan_tetap"] = $this->request->getPost('gaji_jabatan_tetap');
          $arr["tunjangan"] = $this->request->getPost('tunjangan');
          $arr["lembur"] = $this->request->getPost('lembur');
          $arr["lainlain"] = $this->request->getPost('lainlain');
          $arr["thr"] = $this->request->getPost('thr');
          $arr["gratifikasi"] = $this->request->getPost('gratifikasi');
          $arr["rapel"] = $this->request->getPost('rapel');
          $arr["bpjs_kes"] = $this->request->getPost('bpjs_kes');
          $arr["bpjs_tk"] = $this->request->getPost('bpjs_tk');
          $arr["pinjaman"] = $this->request->getPost('pinjaman');
          $arr["kode_pinjaman_tagihan"] = $this->request->getPost('kode_pinjaman_tagihan');
          $arr["bpjs_kes_pt"] = $this->request->getPost('bpjs_kes_pt');
          // $arr["bpjs_tk_pt"] = $this->request->getPost('bpjs_tk_pt');
          $arr["bpjs_jkm_pt"] = $this->request->getPost('bpjs_jkm_pt');
          $arr["bpjs_jkk_pt"] = $this->request->getPost('bpjs_jkk_pt');
          $arr["bpjs_jp_pt"] = $this->request->getPost('bpjs_jp_pt');
          $arr["bpjs_jht_pt"] = $this->request->getPost('bpjs_jht_pt');
          // echo json_encode($arr); die;

          $arr_total = array(
              "gaji_pokok"=>0, "gaji_jabatan_tetap"=>0, "tunjangan"=>0, "lembur"=>0, "lainlain"=>0,
              "thr"=>0, "gratifikasi"=>0, "rapel"=>0, "bpjs_kes"=>0, "bpjs_tk"=>0,
              "pinjaman"=>0, "bpjs_kes_pt"=>0, "bpjs_jkm_pt"=>0, "bpjs_jkk_pt"=>0, "bpjs_jp_pt"=>0, "bpjs_jht_pt"=>0
            );

          $arr_grand = array("total"=>0, "nett"=>0, "bpjs_kes_pt"=>0, "bpjs_jkm_pt"=>0, "bpjs_jkk_pt"=>0, "bpjs_jp_pt"=>0, "bpjs_jht_pt"=>0);

          foreach($arr["gaji_pokok"] as $kode_pt_karyawan => $gaji_pokok) {
            
            foreach($arr_total as $field=>$value) {
              // echo $arr[$field][$kode_pt_karyawan] . " - " . $field . "<br>";
              $arr_total[$field] += str_replace(",", "", $arr[$field][$kode_pt_karyawan]);
            }

            $total = $arr["gaji_pokok"][$kode_pt_karyawan]
                      +$arr["gaji_jabatan_tetap"][$kode_pt_karyawan]
                      + str_replace(",", "", $arr["tunjangan"][$kode_pt_karyawan])
                      + str_replace(",", "", $arr["lembur"][$kode_pt_karyawan])
                      + str_replace(",", "", $arr["lainlain"][$kode_pt_karyawan])
                      + str_replace(",", "", $arr["thr"][$kode_pt_karyawan])
                      + str_replace(",", "", $arr["gratifikasi"][$kode_pt_karyawan])
                      + str_replace(",", "", $arr["rapel"][$kode_pt_karyawan]);
            $arr_grand["total"] += $total;

            $nett = $total - str_replace(",", "", $arr["bpjs_kes"][$kode_pt_karyawan])
                    - str_replace(",", "", $arr["bpjs_tk"][$kode_pt_karyawan])
                    - str_replace(",", "", $arr["pinjaman"][$kode_pt_karyawan]);
            $arr_grand["nett"] += $nett;
           
            $arr_grand["bpjs_kes_pt"] += str_replace(",", "", $arr["bpjs_kes_pt"][$kode_pt_karyawan]);
            // $arr_grand["bpjs_tk_pt"] += str_replace(",", "", $arr["bpjs_tk_pt"][$kode_pt_karyawan]);
            $arr_grand["bpjs_jkm_pt"] += str_replace(",", "", $arr["bpjs_jkm_pt"][$kode_pt_karyawan]);
            $arr_grand["bpjs_jkk_pt"] += str_replace(",", "", $arr["bpjs_jkk_pt"][$kode_pt_karyawan]);
            $arr_grand["bpjs_jp_pt"] += str_replace(",", "", $arr["bpjs_jp_pt"][$kode_pt_karyawan]);
            $arr_grand["bpjs_jht_pt"] += str_replace(",", "", $arr["bpjs_jht_pt"][$kode_pt_karyawan]);

            $arr_data = array("kode_payroll"=>$kode_payroll
                    , "kode_pt_karyawan"  =>$kode_pt_karyawan
                    , "gaji_pokok"        =>$arr["gaji_pokok"][$kode_pt_karyawan]
                    , "gaji_jabatan_tetap"=>$arr["gaji_jabatan_tetap"][$kode_pt_karyawan]
                    , "tunjangan"         =>str_replace(",", "", $arr["tunjangan"][$kode_pt_karyawan])
                    , "lembur"            =>str_replace(",", "", $arr["lembur"][$kode_pt_karyawan])
                    , "lain_lain"          =>str_replace(",", "", $arr["lainlain"][$kode_pt_karyawan])
                    , "thr"               =>str_replace(",", "", $arr["thr"][$kode_pt_karyawan])
                    , "gratifikasi"       =>str_replace(",", "", $arr["gratifikasi"][$kode_pt_karyawan])
                    , "rapel"             =>str_replace(",", "", $arr["rapel"][$kode_pt_karyawan])
                    , "bpjs_kes"          =>str_replace(",", "", $arr["bpjs_kes"][$kode_pt_karyawan])
                    , "bpjs_tk"           =>str_replace(",", "", $arr["bpjs_tk"][$kode_pt_karyawan])
                    , "pinjaman"          =>str_replace(",", "", $arr["pinjaman"][$kode_pt_karyawan])
                    , "gaji_nett"         =>$nett
                    , "bpjs_kes_pt"       =>str_replace(",", "", $arr["bpjs_kes_pt"][$kode_pt_karyawan])
                    // , "bpjs_tk_pt"        =>str_replace(",", "", $arr["bpjs_tk_pt"][$kode_pt_karyawan])
                    , "bpjs_tk_jkm_pt"       =>str_replace(",", "", $arr["bpjs_jkm_pt"][$kode_pt_karyawan])
                    , "bpjs_tk_jkk_pt"       =>str_replace(",", "", $arr["bpjs_jkk_pt"][$kode_pt_karyawan])
                    , "bpjs_tk_jp_pt"        =>str_replace(",", "", $arr["bpjs_jp_pt"][$kode_pt_karyawan])
                    , "bpjs_tk_jht_pt"       =>str_replace(",", "", $arr["bpjs_jht_pt"][$kode_pt_karyawan])
                  );

            if(!empty($arr["kode_pinjaman_tagihan"][$kode_pt_karyawan])) {
              $arr_data['kode_pinjaman_tagihan'] = $arr["kode_pinjaman_tagihan"][$kode_pt_karyawan];
            }
            // echo json_encode($arr_total); die;
            // echo json_encode($arr_data); die;
            $db = \Config\Database::connect();
            $query = $db->table('payroll_detil')->insert($arr_data);
            // echo $db->getLastQuery(); // Menampilkan query terakhir

            // $this->payrollDetilModel->insert($arr_data);
            // echo $this->payrollDetilModel->getLastQuery()->getQuery(); // Menampilkan query terakhir
          }

          // update kembali payroll nya
          $arr_data = array( "total_gaji_pokok" => $arr_total["gaji_pokok"]
              , "total_gaji_jabatan" => $arr_total["gaji_jabatan_tetap"]
              , "total_tunjangan" => $arr_total["tunjangan"]
              , "total_lembur" => $arr_total["lembur"]
              , "total_lain_lain" => $arr_total["lainlain"]
              , "total_thr" => $arr_total["thr"]
              , "total_gratifikasi" => $arr_total["gratifikasi"]
              , "total_rapel" => $arr_total["rapel"]
              , "total_bpjs_kes" => $arr_total["bpjs_kes"]
              , "total_bpjs_tk" => $arr_total["bpjs_tk"]
              , "total_pinjaman" => $arr_total["pinjaman"]
              , "total_gaji_net" => $arr_grand["nett"]
              , "total_bpjs_kes_pt" => $arr_total["bpjs_kes_pt"]
              // , "total_bpjs_tk_pt" => $arr_total["bpjs_tk_pt"]
              , "total_bpjs_tk_jkm_pt" => $arr_total["bpjs_jkm_pt"]
              , "total_bpjs_tk_jkk_pt" => $arr_total["bpjs_jkk_pt"]
              , "total_bpjs_tk_jp_pt" => $arr_total["bpjs_jp_pt"]
              , "total_bpjs_tk_jht_pt" => $arr_total["bpjs_jht_pt"]
            );
          // echo json_encode($arr_data);
          $where = array("kode_payroll"=>$kode_payroll);

          $db = \Config\Database::connect();
          $query = $db->table('payroll')->update($arr_data, $where);
          // echo $db->getLastQuery(); // Menampilkan query terakhir
          
          // $this->payrollModel->update($where, $arr_data);
          // echo $this->payrollModel->getLastQuery()->getQuery(); // Menampilkan query terakhir

          $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Payroll telah ditambahkan"));
        } else {
          $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll gagal ditambahkan"));
        }
       
        return redirect()->to(base_url('payroll/tambah'));
      }
    }    

    $this->data['karyawans'] = $this->ptKaryawanModel->getPTKaryawan(array("pt_karyawan.isaktif"=>1))->find();
    
    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts'] = $this->PTModel->getPT($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('payroll/v_tambah', $this->data);  
    echo view('payroll/v_fragment_upload', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  private function getBungaPinjaman ($kode_pt_karyawan) {
    $kary = $this->ptKaryawanModel->getPTKaryawan(array('kode_pt_karyawan'=>$kode_pt_karyawan))->first();
    $bunga = 0;
    if(!empty($kary)) {
      $pt = $this->PTModel->find($kary->kode_pt);
      if(!empty($pt)) $bunga = $pt->bunga_pinjaman;
    }
    return $bunga;
  }

  public function lihat() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('payroll/daftar'));

    $where = array('payroll.kode_payroll'=>$kode);
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['payroll.kode_pt'] = $this->data['session']->get('kode_pt');
    }

    $this->data['payroll'] = $this->payrollModel->getPayroll($where)->first();
    if(empty($this->data['payroll'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll tidak ditemukan"));

      return redirect()->to(base_url('payroll/daftar'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      
    ";

    $this->data['detils'] = $this->payrollDetilModel->getPayrollDetil(array('payroll_detil.kode_payroll'=>$kode))->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('payroll/v_lihat', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  public function download() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('payroll/daftar'));

    $where = array('payroll.kode_payroll'=>$kode, 'payroll.status'=>'Lunas');
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['payroll.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $payroll = $this->payrollModel->getPayroll($where)->first();
    if(empty($payroll)) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll tidak ditemukan"));

      return redirect()->to(base_url('payroll/daftar'));
    }

    $detils = $this->payrollDetilModel->getPayrollDetil(array('payroll_detil.kode_payroll'=>$kode))->find();

    $this->generateExcel($payroll, $detils);
  }

  private function generateExcel($payroll, $detils) {
    $this->spreadsheet = new Spreadsheet();

    //$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadsheet, 'Payroll');
    //$this->spreadsheet->addSheet($myWorkSheet, 0);

    $sheet = $this->spreadsheet->getSheetByName('Worksheet');
    $sheet->setTitle("Payroll");

    $file_name = "Payroll ".$payroll->nama_pt." ".date("Y-m-d", strtotime($payroll->tanggal_pengajuan)).".xlsx";
    $writer = new Xlsx($this->spreadsheet);

    $styleArray = array(
      'borders' => array(
        'allBorders' => array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => '00000000'),
        ),
      ),
    );

    //header
    $sheet->getColumnDimension('A')->setWidth(120, 'pt');
    $sheet->getColumnDimension('B')->setWidth(60, 'pt');
    $sheet->getStyle('B1:B6')->getAlignment()->setHorizontal('left');

    $sheet->setCellValue('A1', 'Kode Payroll'); $sheet->setCellValue('B1', $payroll->kode_payroll);
    $sheet->setCellValue('A2', 'PT'); $sheet->setCellValue('B2', $payroll->nama_pt);
    $tanggal_pengajuan = empty($payroll->tanggal_pengajuan) ? "" : date("Y-m-d", strtotime($payroll->tanggal_pengajuan));
    $sheet->setCellValue('A3', 'Tanggal Pengajuan'); $sheet->setCellValue('B3', $tanggal_pengajuan);
    $tanggal_pengajuan_bon = empty($payroll->tanggal_pengajuan_bon) ? "" : date("Y-m-d", strtotime($payroll->tanggal_pengajuan_bon));
    $sheet->setCellValue('A4', 'Tanggal Pengajuan Bon'); $sheet->setCellValue('B4', $tanggal_pengajuan_bon);
    $tanggal_lunas = empty($payroll->tanggal_lunas) ? "" : date("Y-m-d", strtotime($payroll->tanggal_lunas));
    $sheet->setCellValue('A5', 'Tanggal Pelunasan'); $sheet->setCellValue('B5', $tanggal_lunas);
    $sheet->setCellValue('A6', 'Kode Bon'); $sheet->setCellValue('B6', $payroll->kode_bon);
    
    // body
    $sheet->setCellValue('B8', 'No.'); 
    $sheet->setCellValue('C8', 'NIK'); $sheet->getColumnDimension('C')->setWidth(80, 'pt');
    $sheet->setCellValue('D8', 'Nama'); $sheet->getColumnDimension('D')->setWidth(130, 'pt');
    $sheet->setCellValue('E8', 'Jabatan'); $sheet->getColumnDimension('E')->setWidth(90, 'pt');
    $sheet->setCellValue('F8', 'Gaji Pokok'); $sheet->getColumnDimension('F')->setWidth(100, 'pt');
    $sheet->setCellValue('G8', 'Gaji Jabatan Tetap'); $sheet->getColumnDimension('G')->setWidth(100, 'pt');
    $sheet->setCellValue('H8', 'Tunjangan'); $sheet->getColumnDimension('H')->setWidth(100, 'pt');
    $sheet->setCellValue('I8', 'Lembur'); $sheet->getColumnDimension('I')->setWidth(100, 'pt');
    $sheet->setCellValue('J8', 'Lain-lain'); $sheet->getColumnDimension('J')->setWidth(100, 'pt');
    $sheet->setCellValue('K8', 'THR'); $sheet->getColumnDimension('K')->setWidth(100, 'pt');
    $sheet->setCellValue('L8', 'Gratifikasi'); $sheet->getColumnDimension('L')->setWidth(100, 'pt');
    $sheet->setCellValue('M8', 'Rapel'); $sheet->getColumnDimension('M')->setWidth(100, 'pt');
    $sheet->setCellValue('N8', 'Total'); $sheet->getColumnDimension('N')->setWidth(100, 'pt');
    $sheet->setCellValue('O8', 'BPJS Kes'); $sheet->getColumnDimension('O')->setWidth(100, 'pt');
    $sheet->setCellValue('P8', 'BPJS TK'); $sheet->getColumnDimension('P')->setWidth(100, 'pt');
    $sheet->setCellValue('Q8', 'Pinjaman Pegawai'); $sheet->getColumnDimension('Q')->setWidth(100, 'pt');
    $sheet->setCellValue('R8', 'Gaji Nett'); $sheet->getColumnDimension('R')->setWidth(100, 'pt');
    $sheet->setCellValue('S8', 'BPJS Kes PT'); $sheet->getColumnDimension('S')->setWidth(100, 'pt');
    $sheet->setCellValue('T8', 'BPJS TK JKM PT'); $sheet->getColumnDimension('T')->setWidth(100, 'pt');
    $sheet->setCellValue('U8', 'BPJS TK JKK PT'); $sheet->getColumnDimension('T')->setWidth(100, 'pt');
    $sheet->setCellValue('V8', 'BPJS TK JP PT'); $sheet->getColumnDimension('T')->setWidth(100, 'pt');
    $sheet->setCellValue('W8', 'BPJS TK JHT PT'); $sheet->getColumnDimension('T')->setWidth(100, 'pt');
    $sheet->setCellValue('X8', 'PPh 21'); $sheet->getColumnDimension('U')->setWidth(100, 'pt');
    $sheet->getStyle("B8:X8")->getFont()->setBold( true );

    $row = 9;
    if(!empty($detils)) {
      foreach($detils as $detil) {
        $sheet->setCellValue('B'.$row, $detil->no_urut_pt_karyawan);
        //$sheet->setCellValue('C'.$row, $detil->nik_pt_karyawan);
        $sheet->getCell('C'.$row)
            ->setValueExplicit(
                $detil->nik_pt_karyawan,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
            );
        $sheet->setCellValue('D'.$row, $detil->nama_lengkap_pt_karyawan);
        $sheet->setCellValue('D'.$row, $detil->jabatan_pt_karyawan);

        $sheet->setCellValue('F'.$row, $detil->gaji_pokok);
        $sheet->setCellValue('G'.$row, $detil->gaji_jabatan_tetap);
        $sheet->setCellValue('H'.$row, $detil->tunjangan);
        $sheet->setCellValue('I'.$row, $detil->lembur);
        $sheet->setCellValue('J'.$row, $detil->lain_lain);
        $sheet->setCellValue('K'.$row, $detil->thr);
        $sheet->setCellValue('L'.$row, $detil->gratifikasi);
        $sheet->setCellValue('M'.$row, $detil->rapel);
        $total = $detil->gaji_pokok + $detil->gaji_jabatan_tetap + $detil->tunjangan + $detil->lembur 
              + $detil->lain_lain + $detil->thr + $detil->gratifikasi + $detil->rapel;
        $sheet->setCellValue('N'.$row, $total);
        $sheet->setCellValue('O'.$row, $detil->bpjs_kes);
        $sheet->setCellValue('P'.$row, $detil->bpjs_tk);
        $sheet->setCellValue('Q'.$row, $detil->pinjaman);
        $sheet->setCellValue('R'.$row, $detil->gaji_nett);
        $sheet->setCellValue('S'.$row, $detil->bpjs_kes_pt);
        // $sheet->setCellValue('T'.$row, $detil->bpjs_tk_pt);
        $sheet->setCellValue('T'.$row, $detil->bpjs_tk_jkm_pt);
        $sheet->setCellValue('U'.$row, $detil->bpjs_tk_jkk_pt);
        $sheet->setCellValue('V'.$row, $detil->bpjs_tk_jp_pt);
        $sheet->setCellValue('W'.$row, $detil->bpjs_tk_jht_pt);
        $sheet->setCellValue('X'.$row, $detil->pph21);

        $row++;
      }
    } else {
      $sheet->setCellValue('B9', 'Tidak ada data');
      $sheet->mergeCells('B9:T9');
      $row++;
    }

    //footer
    $sheet->setCellValue('B'.$row, 'TOTAL');
    $sheet->mergeCells("B$row:E$row");
    $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal('right');
    $sheet->setCellValue('F'.$row, $payroll->total_gaji_pokok);
    $sheet->setCellValue('G'.$row, $payroll->total_gaji_jabatan);
    $sheet->setCellValue('H'.$row, $payroll->total_tunjangan);
    $sheet->setCellValue('I'.$row, $payroll->total_lembur);
    $sheet->setCellValue('J'.$row, $payroll->total_lain_lain);
    $sheet->setCellValue('K'.$row, $payroll->total_thr);
    $sheet->setCellValue('L'.$row, $payroll->total_gratifikasi);
    $sheet->setCellValue('M'.$row, $payroll->total_rapel);
    $total = $payroll->total_gaji_pokok + $payroll->total_gaji_jabatan + $payroll->total_tunjangan + $payroll->total_lembur 
              + $payroll->total_lain_lain + $payroll->total_thr + $payroll->total_gratifikasi + $payroll->total_rapel;
    
    $sheet->setCellValue('N'.$row, $total);
    $sheet->setCellValue('O'.$row, $payroll->total_bpjs_kes);
    $sheet->setCellValue('P'.$row, $payroll->total_bpjs_tk);
    $sheet->setCellValue('Q'.$row, $payroll->total_pinjaman);
    $sheet->setCellValue('R'.$row, $payroll->total_gaji_net);
    $sheet->setCellValue('S'.$row, $payroll->total_bpjs_kes_pt);
    // $sheet->setCellValue('T'.$row, $payroll->total_bpjs_tk_pt);
    $sheet->setCellValue('T'.$row, $payroll->total_bpjs_tk_jkm_pt);
    $sheet->setCellValue('U'.$row, $payroll->total_bpjs_tk_jkk_pt);
    $sheet->setCellValue('V'.$row, $payroll->total_bpjs_tk_jp_pt);
    $sheet->setCellValue('W'.$row, $payroll->total_bpjs_tk_jht_pt);
    $sheet->setCellValue('X'.$row, $payroll->total_pph21);


    $sheet->getStyle("B8:X$row")->applyFromArray($styleArray);

    //header("Content-Type: application/vnd.ms-excel");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    //header('Content-Length:' . filesize($file_name));

    $writer->save($file_name);

    flush();

    readfile($file_name);

    exit;
  }

  public function pengajuan() {
    if(!in_array("AAJUBON", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('payroll/daftar'));

    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('payroll/daftar'));

    $where = array('payroll.kode_payroll'=>$kode, 'payroll.status'=>'Tagihan');
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['payroll.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['payroll'] = $this->payrollModel->getPayroll($where)->first();
    if(empty($this->data['payroll'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll tidak ditemukan"));

      return redirect()->to(base_url('payroll/daftar'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });      
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'karyawan'  => [
            'label' => 'Karyawan',
            'rules' => 'required',
          ],
      ];
      if (! $this->validate($validationRule)) {
        $this->data['errors'] = $this->validator->getErrors();  
      } else {
        // update dulu pinjamannya
        $arr_kode_pinjaman = $this->request->getPost('kode_pinjaman');
        $arr_nilai_pinjaman = $this->request->getPost('nilai_pinjaman');
        $arr_gaji_nett = $this->request->getPost('gaji_nett');
        $total_nilai_pinjaman = $this->request->getPost('total_nilai_pinjaman');
        $total_gaji_nett = $this->request->getPost('total_gaji_nett');

        foreach($arr_kode_pinjaman as $kode_payroll_detil=>$kode_pinjaman) {
          if(empty($kode_pinjaman)) continue;
          $arr_data = array('pinjaman'              => $arr_nilai_pinjaman[$kode_payroll_detil]
                          , 'kode_pinjaman_tagihan' => $kode_pinjaman
                          , 'gaji_nett'             => $arr_gaji_nett[$kode_payroll_detil]
                        );
         //print_r($arr_data); echo $kode_payroll_detil; die();
          $this->payrollDetilModel->update($kode_payroll_detil, $arr_data);
        }
//die();

        $arr_data = array('jenis'=>"Payroll"
            , 'kode_bon_status' => 0
            , 'tanggal_pengajuan'=>date("Y-m-d", strtotime($this->request->getPost("tanggal_pengajuan_bon")))
            , 'kode_pt_karyawan'=>$this->request->getPost('karyawan')
            , 'nominal'=>$total_gaji_nett //$this->data['payroll']->total_gaji_net
            , 'kode_kas'=>$this->request->getPost('kas')
            , 'keterangan'=>"Kode Payroll: ".$this->data['payroll']->kode_payroll
            , 'ispersetujuan'=>1
          );
        //print_r($arr_data); die();
        $this->bonModel->insert($arr_data);
        $kode_bon = $this->bonModel->getInsertID();

        $arr_data = array('kode_bon'=>$kode_bon, 'kode_bon_status'=>0);
        $this->bonStatusHistoriModel->insert($arr_data);

        //$this->isiPinjamanKaryawan($kode_bon, $this->data['payroll']->kode_payroll);

        $arr_data = array('status'=>'Pengajuan Bon', 'kode_bon'=>$kode_bon, "total_pinjaman" => $total_nilai_pinjaman, 'total_gaji_net'=>$total_gaji_nett);
        $this->payrollModel->update($this->data['payroll']->kode_payroll, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah diajukan"));
      }
     
      return redirect()->to(base_url('payroll/daftar'));
    }

    $this->data['detils'] = $this->payrollDetilModel->getPayrollDetil(array('payroll_detil.kode_payroll'=>$kode))->find();
    
    $where = array("pt_karyawan.isaktif"=>1, 'departmen.kode_pt'=>$this->data['payroll']->kode_pt);
    $this->data['karyawans'] = $this->ptKaryawanModel->getPTKaryawan($where)->find();
    
    $where = array('departmen.kode_pt'=>$this->data['payroll']->kode_pt);
    $this->data['kass'] = $this->kasModel->getKas($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('payroll/v_pengajuan', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  private function isiPinjamanKaryawan($kode_bon, $kode_payroll) {
    $payroll_detils = $this->payrollDetilModel->getPayrollDetil(array('payroll_detil.kode_payroll'=>$kode_payroll))->find();
    if(!empty($payroll_detils)) {
      $total_nilai_pinjaman=0;
      foreach($payroll_detils as $detil) {
        $where = array('bon.kode_bon_status >'=>0, 'bon.kode_bon_status <'=>5, 'bon.kode_pt_karyawan'=>$detil->kode_pt_karyawan, 'pinjaman_tagihan.status'=>'Tagihan');
        $pinjaman = $this->pinjamanTagihanModel->getPinjamanTagihan($where)->first();
        

        if(!empty($pinjaman)) {
          $nilai_pinjaman = $pinjaman->nominal;
          $kode_pinjaman_tagihan = $pinjaman->kode_pinjaman_tagihan;
          $total_nilai_pinjaman+=$nilai_pinjaman;

          $arr_data = array('pinjaman'              => $nilai_pinjaman
                          , 'kode_pinjaman_tagihan' => $kode_pinjaman_tagihan
                          , 'gaji_nett'             => $detil->gaji_nett-$nilai_pinjaman
                        );
          $this->payrollDetilModel->update($detil->kode_payroll_detil, $arr_data);
        }
      }

      $payroll=$this->payrollModel->find($kode_payroll);
      if(!empty($payroll)) {
        $total_gaji_net=$payroll->total_gaji_net - $total_nilai_pinjaman;
        $arr_data = array('total_pinjaman'=>$total_nilai_pinjaman, 'total_gaji_net'=>$total_gaji_net);
        $this->payrollModel->update($this->data['payroll']->kode_payroll, $arr_data);
        
        $arr_data = array('nominal'=>$total_gaji_net);
        $this->bonModel->update($kode_bon, $arr_data);
      }
      
    }
  }

  public function ubah() {
    if(!in_array("ATBHPRL", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('payroll/daftar'));
    
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('payroll/daftar'));

    $where = array('payroll.kode_payroll'=>$kode, 'payroll.status'=>'Tagihan');
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['payroll.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['payroll'] = $this->payrollModel->getPayroll($where)->first();
    if(empty($this->data['payroll'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll tidak ditemukan"));

      return redirect()->to(base_url('payroll/daftar'));
    }

    $this->data['jquery'] = $this->jquery();

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'kode'  => [
            'label' => 'Kode',
            'rules' => 'required|max_length[40]',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $kode_payroll = $this->request->getPost('kode');

        if($kode_payroll) {
          $arr["gaji_pokok"] = $this->request->getPost('gaji_pokok');
          $arr["gaji_jabatan_tetap"] = $this->request->getPost('gaji_jabatan_tetap');
          $arr["tunjangan"] = $this->request->getPost('tunjangan');
          $arr["lembur"] = $this->request->getPost('lembur');
          $arr["lainlain"] = $this->request->getPost('lainlain');
          $arr["thr"] = $this->request->getPost('thr');
          $arr["gratifikasi"] = $this->request->getPost('gratifikasi');
          $arr["rapel"] = $this->request->getPost('rapel');
          $arr["bpjs_kes"] = $this->request->getPost('bpjs_kes');
          $arr["bpjs_tk"] = $this->request->getPost('bpjs_tk');
          $arr["pinjaman"] = $this->request->getPost('pinjaman');
          $arr["kode_pinjaman_tagihan"] = $this->request->getPost('kode_pinjaman_tagihan');

          $arr_total = array(
              "gaji_pokok"=>0, "gaji_jabatan_tetap"=>0, "tunjangan"=>0, "lembur"=>0, "lainlain"=>0,
              "thr"=>0, "gratifikasi"=>0, "rapel"=>0, "bpjs_kes"=>0, "bpjs_tk"=>0,
              "pinjaman"=>0, "bpjs_kes_pt"=>0, "bpjs_tk_pt"=>0
            );

          $arr_grand = array("total"=>0, "nett"=>0);

          foreach($arr["gaji_pokok"] as $kode_payroll_detil => $gaji_pokok) {
            
            foreach($arr_total as $field=>$value) {
              $arr_total[$field] += str_replace(",", "", $arr[$field][$kode_payroll_detil]);
            }

            $total = $arr["gaji_pokok"][$kode_payroll_detil]
                      +$arr["gaji_jabatan_tetap"][$kode_payroll_detil]
                      + str_replace(",", "", $arr["tunjangan"][$kode_payroll_detil])
                      + str_replace(",", "", $arr["lembur"][$kode_payroll_detil])
                      + str_replace(",", "", $arr["lainlain"][$kode_payroll_detil])
                      + str_replace(",", "", $arr["thr"][$kode_payroll_detil])
                      + str_replace(",", "", $arr["gratifikasi"][$kode_payroll_detil])
                      + str_replace(",", "", $arr["rapel"][$kode_payroll_detil]);
            $arr_grand["total"] += $total;

            $nett = $total - $arr["bpjs_kes"][$kode_payroll_detil]
                    - $arr["bpjs_tk"][$kode_payroll_detil]
                    - $arr["pinjaman"][$kode_payroll_detil];
            $arr_grand["nett"] += $nett;

            $arr_grand["bpjs_kes_pt"] += $arr["bpjs_kes_pt"][$kode_payroll_detil];
            $arr_grand["bpjs_tk_pt"] += $arr["bpjs_tk_pt"][$kode_payroll_detil];           

            $arr_data = array(
                      "gaji_pokok"        =>$arr["gaji_pokok"][$kode_payroll_detil]
                    , "gaji_jabatan_tetap"=>$arr["gaji_jabatan_tetap"][$kode_payroll_detil]
                    , "tunjangan"         =>str_replace(",", "", $arr["tunjangan"][$kode_payroll_detil])
                    , "lembur"            =>str_replace(",", "", $arr["lembur"][$kode_payroll_detil])
                    , "lainlain"          =>str_replace(",", "", $arr["lainlain"][$kode_payroll_detil])
                    , "thr"               =>str_replace(",", "", $arr["thr"][$kode_payroll_detil])
                    , "gratifikasi"       =>str_replace(",", "", $arr["gratifikasi"][$kode_payroll_detil])
                    , "rapel"             =>str_replace(",", "", $arr["rapel"][$kode_payroll_detil])
                    , "bpjs_kes"          =>$arr["bpjs_kes"][$kode_payroll_detil]
                    , "bpjs_tk"           =>$arr["bpjs_tk"][$kode_payroll_detil]
                    , "pinjaman"          =>$arr["pinjaman"][$kode_payroll_detil]
                    , "gaji_nett"         =>$nett
                    , "bpjs_kes_pt"       =>$arr["bpjs_kes_pt"][$kode_payroll_detil]
                    , "bpjs_tk_pt"        =>$arr["bpjs_tk_pt"][$kode_payroll_detil]
                  );

            if(!empty($arr["kode_pinjaman_tagihan"][$kode_payroll_detil])) {
              $arr_data['kode_pinjaman_tagihan'] = $arr["kode_pinjaman_tagihan"][$kode_payroll_detil];
            }

            $where = array('kode_payroll_detil'=>$kode_payroll_detil);
            $this->payrollDetilModel->update($where, $arr_data);
          }

          // update kembali payroll nya
          $arr_data = array( "total_gaji_pokok" => $arr_total["gaji_pokok"]
              , "total_gaji_jabatan" => $arr_total["gaji_jabatan_tetap"]
              , "total_tunjangan" => $arr_total["tunjangan"]
              , "total_lembur" => $arr_total["lembur"]
              , "total_lain_lain" => $arr_total["lainlain"]
              , "total_thr" => $arr_total["thr"]
              , "total_gratifikasi" => $arr_total["gratifikasi"]
              , "total_rapel" => $arr_total["rapel"]
              , "total_bpjs_kes" => $arr_total["bpjs_kes"]
              , "total_bpjs_tk" => $arr_total["bpjs_tk"]
              , "total_pinjaman" => $arr_total["pinjaman"]
              , "total_gaji_net" => $arr_grand["nett"]
              , "total_bpjs_kes_pt" => $arr_total["bpjs_kes_pt"]
              , "total_bpjs_tk_pt" => $arr_total["bpjs_tk_pt"]
            );
          $where = array("kode_payroll"=>$kode_payroll);
          $this->payrollModel->update($where, $arr_data);

          $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Payroll telah diubah"));
        } else {
          $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll gagal diubah"));
        }

        return redirect()->to(base_url('payroll/daftar/ubah?kode='.$kode));
      }
    }
    $this->data['detils'] = $this->payrollDetilModel->getPayrollDetil(array('payroll_detil.kode_payroll'=>$kode))->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('payroll/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}