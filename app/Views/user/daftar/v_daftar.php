<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">User</li>
          <li class="breadcrumb-item active" aria-current="page">User</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar User</h1>
      
    </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <a class="btn btn-primary" href="<?= base_url('user/daftar/tambah'); ?>">Tambah User Baru</a>
        </div>
      </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Kas</caption>
            <thead>
              <tr>
                <th style="min-width: 120px; max-width: 150px !important;">Username</th>
                <th style="min-width: 220px; ">Nama</th>
                <th style="min-width: 170px; max-width: 170px !important;">Profil</th>
                <th style="min-width: 170px; max-width: 170px !important;">PT</th>
                <th style="max-width: 100px !important;" class="text-center">Admin?</th>
                <th style="max-width: 100px !important;" class="text-center">Status</th>
                <th style="max-width: 70px !important;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($users!=false) {
                  foreach($users as $user) {
                    echo "<tr>";
                      echo "<td>".$user->username."</td>";
                      echo "<td>".$user->nama."</td>";
                      echo "<td>".$user->nama_profil."</td>";
                      echo "<td>".$user->nama_pt."</td>";
                      echo "<td class='text-center'>";
                        if($user->isadmin) {
                          echo "<i class='text-success fa fa-check'></i>";
                        }
                      echo "</td>";
                      echo "<td class='text-center'>";
                        if($user->isaktif) {
                          echo "<span class='badge bg-success'>Aktif";
                        } else {
                          echo "<span class='badge bg-danger'>Tidak Aktif";
                        }
                        echo "</span>";
                      echo "</td>";
                      echo "<td>";
                        echo "<a href='".base_url('user/daftar/ubah/?username='.$user->username)."' title='Ubah'><i class='fa fa-edit'></i></a>";
                        if(!$user->isadmin)
                        echo " &nbsp; <a href='#' title='Hapus' class='hapus' username='".$user->username."' nama='".$user->nama."' data-bs-toggle='modal' data-bs-target='#hapusModal'><i class='fa fa-trash text-danger'></i></a>";
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