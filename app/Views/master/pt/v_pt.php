<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active" aria-current="page">PT</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar PT</h1>
      
    </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <a class="btn btn-primary" href="<?= base_url('master/pt/tambah'); ?>">Tambah PT Baru</a>
        </div>
      </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">PT</caption>
            <thead>
              <tr>
                <th style="max-width: 70px !important;">Kode</th>
                <th style="min-width: 150px !important;">Kebun</th>
                <th style="min-width: 200px !important;">Nama PT</th>
                <th style="max-width: 70px !important;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($pts!=false) {
                  foreach($pts as $pt) {
                    echo "<tr>";
                      echo "<td>".$pt->kode_pt."</td>";
                      echo "<td>".$pt->nama_kebun."</td>";
                      echo "<td>".$pt->nama."</td>";
                      echo "<td>";
                        echo "<a href='".base_url('master/pt/ubah/?kode='.$pt->kode_pt)."' title='Ubah'><i class='fa fa-edit'></i></a>";
                        echo " &nbsp; <a href='#' title='Hapus' class='hapus' kode='".$pt->kode_pt."' nama='".$pt->nama."' data-bs-toggle='modal' data-bs-target='#hapusModal'><i class='fa fa-trash text-danger'></i></a>";
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