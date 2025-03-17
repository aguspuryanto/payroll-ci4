<?php

namespace App\Controllers\setting;
use App\Controllers\BaseController;

use App\Models\HakAksesModel;
use App\Models\MenuModel;
use App\Models\ProfilModel;
use App\Models\OtentikasiModel;

use CodeIgniter\Files\File;

class HakAkses extends BaseController {
  public function __construct() {
    $this->hakAksesModel = new HakAksesModel();
    $this->menuModel = new MenuModel();
    $this->profilModel = new ProfilModel();
    $this->otentikasiModel = new OtentikasiModel();
  }

  public function index() {
    $this->data['jquery'] = "   
      $('body').on('click', '.jiu', function(){
        var idmenus=$(this).val();
        var kode_profil = $(this).attr('jiu');
        action = ($(this).is(':checked')) ? 'tambah' : 'hapus';
        $.post('".site_url('setting/hakakses/ajaxProfil')."', {idmenus: idmenus, kode_profil: kode_profil, action: action});

      });

      $('body').on('click', '.expand-menu', function(){
        var status=$(this).attr('status');
        var nomor=$(this).attr('nomor');
        if(status=='open') {
          $(this).attr('status', 'close');
          $('.icon-menu[nomor='+nomor+']').removeClass('fa-minus-square');
          $('.icon-menu[nomor='+nomor+']').addClass('fa-plus-square');
          $('tr[nomor='+nomor+']').fadeOut();
        } else {          
          $(this).attr('status', 'open');
          $('.icon-menu[nomor='+nomor+']').removeClass('fa-plus-square');
          $('.icon-menu[nomor='+nomor+']').addClass('fa-minus-square');
          $('tr[nomor='+nomor+']').fadeIn();
        }
      });
    ";

    $this->data['profils'] = $this->profilModel->where("aplikasi LIKE '%keuangan%'")->findAll();
    $this->data['menus'] = $this->menuModel->getMenu(array('menus_keuangan.jenis'=>'Menu'))->find();


    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('setting/hakakses/v_hakakses', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function ajaxProfil() {
    $idmenus = $this->request->getPost('idmenus');
    $kode_profil = $this->request->getPost('kode_profil');
    $action = $this->request->getPost('action');
    $arr_data = array('idmenus'=>$idmenus, 'kode_profil'=>$kode_profil);
    if($action=='tambah')
      echo $this->hakAksesModel->addHakAkses($arr_data);
    else
      $this->hakAksesModel->where($arr_data)->delete();

    // update session hak akses
    $this->otentikasiModel->UpdateSessionHakAkses();
  }
}