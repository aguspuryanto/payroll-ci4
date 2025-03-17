<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active" aria-current="page">Departmen</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar Departmen</h1>
      
    </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <a class="btn btn-primary" href="<?= base_url('master/departmen/tambah'); ?>">Tambah Departmen Baru</a>
        </div>
      </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Departmen</caption>
            <thead>
              <tr>
                <th style="max-width: 70px !important;">Kode</th>
                <th style="min-width: 150px; max-width: 150px !important;">Kebun</th>
                <th style="min-width: 220px; max-width: 220px !important;">PT</th>
                <th style="min-width: 200px !important;">Nama Departmen</th>
                <th style="max-width: 70px !important;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($departmens!=false) {
                  foreach($departmens as $departmen) {
                    echo "<tr>";
                      echo "<td>".$departmen->kode_departmen."</td>";
                      echo "<td>".$departmen->nama_kebun."</td>";
                      echo "<td>".$departmen->nama_pt."</td>";
                      echo "<td>".$departmen->nama."</td>";
                      echo "<td>";
                        echo "<a href='".base_url('master/departmen/ubah/?kode='.$departmen->kode_departmen)."' title='Ubah'><i class='fa fa-edit'></i></a>";
                        echo " &nbsp; <a href='#' title='Hapus' class='hapus' kode='".$departmen->kode_departmen."' nama='".$departmen->nama."' data-bs-toggle='modal' data-bs-target='#hapusModal'><i class='fa fa-trash text-danger'></i></a>";
                      echo "</td>";
                    echo "</tr>";
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