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

class PPh21 extends BaseController {
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

  public function ajaxPayroll() {
    if($this->request->isAjax()) {
      $hasil = ""; 
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
                <td class='text-end footer-total' tag='bpjs_tk_pt'>0</td>
              </tr>";

      $arr_total = array(
              "gaji_pokok"=>0, "gaji_jabatan_tetap"=>0, "tunjangan"=>0, "lembur"=>0, "lainlain"=>0,
              "thr"=>0, "gratifikasi"=>0, "rapel"=>0, "total"=>0, "bpjs_kes"=>0, "bpjs_tk"=>0,
              "pinjaman"=>0, "gaji_nett"=>0, "bpjs_kes_pt"=>0, "bpjs_tk_pt"=>0
            );

      $where = array('pt.kode_pt'=>$this->request->getPost('kode_pt'));
      $karyawans = $this->ptKaryawanModel->getPTKaryawan($where, 'pt_karyawan.no_urut, pt_karyawan.nama_lengkap')->find();
      if(!empty($karyawans)) {
        foreach($karyawans as $karyawan) {
          $total = $karyawan->gaji_pokok + $karyawan->gaji_jabatan_tetap;
          
          $where = array('bon.kode_pt_karyawan'=>$karyawan->kode_pt_karyawan, 'pinjaman_tagihan.status'=>'Tagihan');
          $pinjaman = $this->pinjamanTagihanModel->getPinjamanTagihan($where)->first();
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
            $hasil .= "<td><input type='text' tag='bpjs_tk_pt' class='form-control number bpjs_tk_pt text-end bpjs_pt' name='bpjs_tk_pt[$karyawan->kode_pt_karyawan]' kode='$karyawan->kode_pt_karyawan' value='0'></td>";

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
                <td class='text-end footer-total' tag='bpjs_tk_pt'>".number_format($arr_total["bpjs_tk_pt"],0,".",",")."</td>
              </tr>";

      } else {
        $hasil = "<td colspan='19' class='fst-italic'>Belum ada data</td>";
      } 

      $output = array ("body"=>$hasil, "footer"=>$footer);

      echo json_encode($output);
    }
  }

  private function jquery() {
    $jquery = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});

      function updateFooterPayroll(tag) {
        var total=0;
        $('.pph21[tag='+tag+']').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.footer-total[tag='+tag+']').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
      }
      
      $('body').on('keyup', 'input.number', function(){
        var kode_karyawan = $(this).attr('kode');

        var total = 0;
        $('.pph21[kode='+kode_karyawan+']').each(function(){
          total += $(this).val().replace(/,(?=\d{3})/g, '')*1;
        });
        $('.total[kode='+kode_karyawan+']').html(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));

        updateFooterPayroll($(this).attr('tag'));
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

  public function index() {
    if(!in_array("AUPPH21", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('payroll/daftar'));
    
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('payroll/daftar'));

    $this->data['payroll'] = $this->payrollModel->getPayroll(array('payroll.kode_payroll'=>$kode, 'payroll.status'=>'Lunas'))->first();
    if(empty($this->data['payroll'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Payroll tidak ditemukan"));

      return redirect()->to(base_url('payroll/daftar'));
    }

    $this->data['jquery'] = $this->jquery()."
      $('#label-upload').html('Upload file CSV PPh 21');

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
              $.each(payrolls, function(i, item){
                if(i==0) return true;
                var nik=item[0];
                
                var pph21 = item[2];
                $('.pph21[nik='+nik+']').val(pph21);
                
              });
              $('input.number').keyup();
            }
          }
        });
      });   
      ";

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
          $arr["pph21"] = $this->request->getPost('pph21');
          
          $arr_total = array(
              "pph21"=>0
            );


          foreach($arr["pph21"] as $kode_payroll_detil => $pph21) {
            $pph21 = str_replace(",", "", $arr["pph21"][$kode_payroll_detil])  ;
            $arr_total["pph21"] += $pph21;                 

            $arr_data = array(
                 "pph21" => $pph21                  
              );

            $where = array('kode_payroll_detil'=>$kode_payroll_detil);
            $this->payrollDetilModel->update($where, $arr_data);
          }

          // update kembali payroll nya
          $arr_data = array( "total_pph21" => $arr_total["pph21"]              
            );
          $where = array("kode_payroll"=>$kode_payroll);
          $this->payrollModel->update($where, $arr_data);

          $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"PPh 21 telah diperbarui"));
        } else {
          $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"PPh 21 gagal diperbarui"));
        }

        return redirect()->to(base_url('payroll/pph21?kode='.$kode));
      }
    }
    $this->data['detils'] = $this->payrollDetilModel->getPayrollDetil(array('payroll_detil.kode_payroll'=>$kode))->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('payroll/v_pph21', $this->data);  
    echo view('payroll/v_fragment_upload', $this->data); 
    echo view('backend/v_footer', $this->data);
  }
}