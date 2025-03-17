<?php  
use App\Models\HakAksesModel;
use App\Models\MenuModel;

$hakAksesModel = new HakAksesModel();
$menuModel = new MenuModel();
?>
<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Setting</li>
          <li class="breadcrumb-item active" aria-current="page">Hak Akses</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar Menu</h1>
      
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class='table-responsive'>
          <table class="table table-hover table-striped table-bordered caption-top">
            <caption class="bg-dark text-light p-2">Daftar Menu</caption>
            <thead>
              <tr>
                <th class='text-end' style="width: 60px;">No.</th>
                <th style="min-width: 230px;">Menu</th>
                <?php  
                if($profils!=false) {
                  foreach($profils as $profil) {
                    echo "<th style='width: 100px; max-width: 100px;' class='text-center'>".$profil->nama."</th>";
                  }
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php 
                $no_menu=1;
                if($menus!=false) {
                  foreach($menus as $menu) {
                    echo "<tr>";
                      echo "<td class='text-end'>$no_menu <a href='#' class='expand-menu' nomor='m$no_menu' status='open'><i class='icon-menu fa fa-minus-square' nomor='m$no_menu'></i></a></td>";
                      echo "<td>".$menu->nama."</td>";
                      
                      // internal user
                      if($profils!=false) {
                        foreach($profils as $profil) {
                          $haks = $hakAksesModel->getMenuUntukProfil($profil->kode_profil);
                          echo "<td class='text-center'>";
                            $checked = in_array($menu->idmenus, $haks) ? "checked" : "";
                            echo "<input type='checkbox' class='form-check-input jiu' value='".$menu->idmenus."' name='jiu[]' jiu='".$profil->kode_profil."' $checked>";
                          echo "</td>";
                        }
                      }
                    echo "</tr>";

                    //sub menu
                    $no_sub_menu=1;
                    $submenus = $menuModel->getMenu(array('menus_keuangan.jenis'=>'Sub Menu', 'menus_keuangan.idparent'=>$menu->idmenus))->find();
                    if($submenus!=false) {
                      foreach($submenus as $submenu) {
                        echo "<tr nomor='m$no_menu'>";
                          echo "<td class='text-end'></td>";
                          echo "<td>$no_menu.$no_sub_menu &nbsp; ".$submenu->nama."</td>";
                           
                          //internal user
                          if($profils!=false) {
                            foreach($profils as $profil) {
                              $haks = $hakAksesModel->getMenuUntukProfil($profil->kode_profil);
                              echo "<td class='text-center'>";
                                $checked = in_array($submenu->idmenus, $haks) ? "checked" : "";
                                echo "<input type='checkbox' class='form-check-input jiu' value='".$submenu->idmenus."' name='jiu[]' jiu='".$profil->kode_profil."' $checked>";
                              echo "</td>";
                            }
                          }
                        echo "</tr>";

                        // action
                        $no_action=1;
                        $actions = $menuModel->getMenu(array('menus_keuangan.jenis'=>'Action', 'menus_keuangan.idparent'=>$submenu->idmenus))->find();
                        if($actions!=false) {
                          foreach($actions as $action) {
                            echo "<tr nomor='m$no_menu'>";
                              echo "<td class='text-end'></td>";
                              echo "<td> &nbsp; &nbsp; $no_menu.$no_sub_menu.$no_action &nbsp; ".$action->nama."</td>";
                               
                              //internal user
                              if($profils!=false) {
                                foreach($profils as $profil) {
                                  $haks = $hakAksesModel->getMenuUntukProfil($profil->kode_profil);
                                  echo "<td class='text-center'>";
                                    $checked = in_array($action->idmenus, $haks) ? "checked" : "";
                                    echo "<input type='checkbox' class='form-check-input jiu'  value='".$action->idmenus."' name='jiu[]' jiu='".$profil->kode_profil."' $checked>";
                                  echo "</td>";
                                }
                              }
                            echo "</tr>";

                            $no_action++;
                          }
                        }

                        $no_sub_menu++;
                      }
                    }

                    $no_menu++;
                  }
                } 

              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </main>
</div>