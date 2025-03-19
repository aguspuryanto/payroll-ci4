<?php

namespace App\Controllers\bon;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusHistoriModel;
use App\Models\CoaModel;
use App\Models\KasModel;
use App\Models\PencatatanJurnalUmumModel;
use App\Models\PinjamanTagihanModel;
use App\Models\PTKaryawanModel;

use CodeIgniter\Files\File;

class Pencatatan extends BaseController {
  
  private $bonModel;
  private $bonStatusHistoriModel;
  private $coaModel;
  private $kasModel;
  private $pencatatanJurnalUmumModel;
  private $pinjamanTagihanModel;
  private $ptKaryawanModel;
  protected $data;
  protected $afiliasiModel;
  
  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusHistoriModel = new BonStatusHistoriModel();
    $this->coaModel = new CoaModel();
    $this->kasModel = new KasModel();
    $this->pencatatanJurnalUmumModel = new PencatatanJurnalUmumModel();
    $this->pinjamanTagihanModel = new PinjamanTagihanModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus bon \'<i>'+nama+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      $afiliasis = $this->afiliasiModel->find($kode);
      if(!empty($afiliasis)) {
        $this->afiliasiModel->geserSeqSebelumDelete($kode);
        $this->afiliasiModel->delete($kode);
        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah dihapus"));
      } else {
        $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon gagal dihapus"));
      }
      return redirect()->to(base_url('adminku/lainlain/afiliasi'));
    }

    $where = "bon.kode_bon_status >=  ".BON_PENCATATAN_ACCOUNTING;
    $periode = $this->request->getGet('periode');
    $this->data['abaikan'] = $this->request->getGet('abaikan');
    if(!empty($periode)) {
      $this->data['tglawal'] = substr($periode, 0, 10);
      $this->data['tglakhir'] = substr($periode, 13, 10);
    } else {
      $this->data['tglawal'] = date("m/01/Y");
      $this->data['tglakhir'] = date("m/t/Y");
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(bon.tanggal_pencatatan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    $this->data['bons']=$this->bonModel->getBon($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/pencatatan/v_pencatatan', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    if(!in_array("ACTTBON", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('bon/daftar'));

    $kode = $this->request->getGet('kode');
    if(empty($kode)) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon tidak dikenali"));
      return redirect()->to(site_url('bon/daftar'));
    }
    // $where = array('bon.kode_bon'=>$kode, 'bon.kode_bon_status'=>BON_PENGAJUAN);
    // $where = array('bon.kode_bon'=>$kode, 'bon.kode_bon_status'=>BON_PERSETUJUAN_DIREKSI);
    $where = 'bon.kode_bon='.$kode.' AND bon.kode_bon_status='.BON_PENGAJUAN.' OR bon.kode_bon_status='.BON_PERSETUJUAN_DIREKSI;
    $this->data['bon'] = $this->bonModel->getBon($where)->first();
    // echo $this->bonModel->getLastQuery()->getQuery(); die();// Menampilkan query terakhir

    if(empty($this->data['bon'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon tidak dikenali"));
      return redirect()->to(site_url('bon/daftar'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
      var jurnal = []; 

     function ifCoaExsist(coa) {
        $.each(jurnal, function(index, data){          
          if(data[0]==coa) {
            jurnal.splice(index, 1);
            return;          
          } 
        });        
      }

      function cekTombolSimpan(total_debet, total_kredit){
        var nominal_bon = $('#nominal_bon').val();
        if(total_debet!=nominal_bon || total_kredit!=nominal_bon) {
          $('#btnSimpan').attr('disabled', true);
        } else {
          $('#btnSimpan').removeAttr('disabled');
        }
      }

      function generateBody() {
        var debet=0; var kredit=0; var jumlah=0;
        $('#jurnal-body').html(''); 
        $.each(jurnal, function(index, data){
          jumlah++;
          var row = '<tr>';
            row+='<td>'+data[0]+'</td>';
            row+='<td>'+data[1]+'</td>';
            if(data[3]=='KREDIT') { row+='<td></td>'; kredit+=data[2]*1; }
            row+='<td class=\"text-end\">'+data[2].toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\")+'</td>';
            if(data[3]=='DEBET') {row+='<td></td>'; debet+=data[2]*1; }
            row+='<td>';
              row+='<input type=\"hidden\" name=\"nominal['+data[0]+']\" value=\"'+data[2]+'\">';
              row+='<input type=\"hidden\" name=\"posisi['+data[0]+']\" value=\"'+data[3]+'\">';
            row+='<a href=\"#\" idx=\"'+index+'\" class=\"hapus\" title=\"Hapus\" coa=\"'+coa+'\"><i class=\"text-danger fa fa-times\"></i></a></td>';
          row+='</tr>';
          $('#jurnal-body').append(row);
        });
        $('#footer-debet').html(debet.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
        $('#footer-kredit').html(kredit.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, \"$1,\"));
        
        if(jumlah==0) {
          var row = '<tr><td colspan=\"5\" class=\"fst-italic\">Belum ada data</td></tr>';
          $('#jurnal-body').html(row);
        }
        cekTombolSimpan(debet, kredit);
      }

      $('body').on('click', '.hapus', function(){
        var idx = $(this).attr('idx');
        if(idx>-1) {
          jurnal.splice(idx, 1);
          generateBody();
        }
      });

      $('body').on('click', '#tambahkan', function(){
        var nominal = $('#nominal').val().replace(/,(?=\d{3})/g, '');
        var coa = $('#coa').val();
        var nama = $('#coa option:selected').attr('nama');
        if(coa.length < 1) {
          alert('Tentukan COA');
          $('#coa').focus();
          return;
        } else if(nominal==0) {
          alert('Masukkan nominal');
          $('#nominal').focus();
          return;
        } else if (!$('#debet').is(':checked') && !$('#kredit').is(':checked')) {
          alert('Tentukan posisi jurnal');
          $('#debet').focus();
          return;
        }
        var posisi = $('#debet').is(':checked') ? 'DEBET' : 'KREDIT';
        var jml = jurnal.length;
        ifCoaExsist(coa);
        var jurnal_row = [coa, nama, nominal, posisi];
        jurnal.push(jurnal_row);
        generateBody();        
      });
    ";

    if($this->request->getPost('btnSimpan')) {
      $arr_nominal = $this->request->getPost('nominal');
      $arr_posisi = $this->request->getPost('posisi');
      $kode_bon = $this->request->getPost('kode_bon');

      foreach($arr_nominal as $kode_coa=>$nominal) {
        if(isset($arr_posisi[$kode_coa])) {
          $nominal=str_replace(",", "", $nominal);
          $arr_data = array(
            'kode_bon'=>$kode_bon
            , 'kode_coa'=>$kode_coa
            , 'nominal'=>$nominal
            , 'posisi'=>$arr_posisi[$kode_coa]
            , 'tanggal_pencatatan'=>date('Y-m-d H:i:s')
          );
          $this->pencatatanJurnalUmumModel->insert($arr_data);
        }
      }

      $arr_data = array('kode_bon_status' => BON_PENCATATAN_ACCOUNTING, 'tanggal_pencatatan'=>date("Y-m-d H:i:s")); 
      $where = array('kode_bon'=>$kode_bon);
      $this->bonModel->update($where, $arr_data);

      $arr_data = array('kode_bon'=>$kode_bon, 'kode_bon_status'=>BON_PENCATATAN_ACCOUNTING);
      $this->bonStatusHistoriModel->insert($arr_data);

      $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Pencatatan telah ditambahkan"));
      
      return redirect()->to(base_url('bon/daftar?status='.BON_PENCATATAN_ACCOUNTING));
    }

    $this->data['coas']=$this->coaModel->getCoa()->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/pencatatan/v_tambah_pencatatan', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

}