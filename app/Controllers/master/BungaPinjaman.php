<?php

namespace App\Controllers\master;
use App\Controllers\BaseController;

use App\Models\PTModel;
use App\Models\KebunModel;

use CodeIgniter\Files\File;

class BungaPinjaman extends BaseController {
  public function __construct() {
    $this->PTModel = new PTModel();
    $this->kebunModel = new KebunModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
       
      });
    ";

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts']=$this->PTModel->getPT($where)->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/bungapinjaman/v_bungapinjaman', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function ubah() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('master/bungapinjaman'));

    $this->data['pts'] = $this->PTModel->getPT(array('kode_pt'=>$kode))->first();
    if(empty($this->data['pts'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"PT tidak ditemukan"));

      return redirect()->to(base_url('master/bungapinjaman'));
    }
    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 2, prefix: ''});
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'bunga'  => [
            'label' => 'Bunga Pinjaman',
            'rules' => 'required',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $kode = $this->request->getPost('kode');
        $bunga = $this->request->getPost('bunga');
        $bunga = str_replace(",", "", $bunga);
        $arr_data = array('bunga_pinjaman'=>$bunga); 
        $arr_where = array('kode_pt'=>$kode);
        $this->PTModel->update($arr_where, $arr_data);
        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bunga Pinjaman telah diubah"));

        return redirect()->to(base_url('master/bungapinjaman'));
      }
    }

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/bungapinjaman/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  
}