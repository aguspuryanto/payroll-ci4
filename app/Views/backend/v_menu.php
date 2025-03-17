<?php  
$menuModel = new App\Models\MenuModel;
?>
<div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse overflow-y-auto">
      <ul class="nav flex-column mb-2 mt-2">
        <li class="nav-item">
          <a class="nav-link <?php if($uri->getSegment(1)=="home") echo "active"; ?>" aria-current="page" href="<?= base_url('home'); ?>">
            <i class="fa fa-home"></i>
            Home
          </a>
        </li>         
      </ul>
      <div class="accordion" id="accordionMenu">
        <?php  
          $menus = $menuModel->where(array('jenis'=>'Menu', 'isaktif'=>1))->orderBy('seq')->findAll();
          if($menus!=false) {
            foreach($menus as $menu) {
              // if(!in_array($menu->idmenus, $session->get('menu_citra')) && !$session->get('admin_citra')) continue;

              $collapsed = ($uri->getSegment(1)!=$menu->folder) ? 'collapsed' : '';
              $show = ($uri->getSegment(1)==$menu->folder) ? 'show' : '';
              $expanded = ($uri->getSegment(1)==$menu->folder) ? 'true' : 'false';
              $submenus = $menuModel->where(array('jenis'=>'Sub Menu', 'idparent'=>$menu->idmenus, 'isaktif'=>1))->orderBy('seq')->findAll();
              echo "<div class='accordion-item'>
                      <h2 class='accordion-header' id='heading".$menu->idmenus."'>
                        <button class='accordion-button $collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse".$menu->idmenus."' aria-expanded='$expanded' aria-controls='collapse".$menu->nama."'>
                          ".$menu->nama."
                        </button>
                      </h2>
                      <div id='collapse".$menu->idmenus."' class='accordion-collapse collapse $show' aria-labelledby='heading".$menu->idmenus."'>
                        <div class='accordion-body'>
                          <ul class='nav flex-column mb-2'>";
                if($submenus!=false) {
                  foreach($submenus as $submenu) {
                    // if(!in_array($submenu->idmenus, $session->get('menu_citra')) && !$session->get('admin_citra')) continue;

                    $active = ($uri->getSegment(1)==$menu->folder && $uri->getSegment(2)==$submenu->folder) ? 'active' : '';
                    echo "<li class='nav-item'>
                            <a class='nav-link $active' href='".base_url($submenu->link)."'>
                              <i class='".$submenu->icon."'></i>
                              ".$submenu->nama."
                            </a>
                          </li>"; 
                  }
                }
                         
              echo "      </ul>
                        </div>
                      </div>
                    </div>";
            }
          }
        ?>
        
      </div>
    </nav>
  </div>