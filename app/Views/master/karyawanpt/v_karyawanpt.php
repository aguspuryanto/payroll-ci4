<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active" aria-current="page">Karyawan PT</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar Karyawan PT</h1>
      
    </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <a class="btn btn-primary" href="<?= base_url('master/karyawanpt/tambah'); ?>">Tambah Karyawan PT Baru</a>
        </div>
      </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Karyawan PT</caption>
            <thead>
              <tr>
                <th style="max-width: 90px !important;">No. Urut</th>
                <th style="min-width: 70px !important;">NIK</th>
                <th style="min-width: 70px !important;">NIP</th>
                <th style="min-width: 120px; max-width: 220px !important;">PT</th>
                <th style="min-width: 180px !important;">Nama Departmen</th>
                <th style="min-width: 200px !important;">Nama Karyawan</th>
                <th style="min-width: 150px !important;">Jabatan</th>
                <th style="min-width: 130px !important;">BPJS Kes</th>
                <th style="min-width: 130px !important;">BPJS TK</th>
                <th style="min-width: 80px !important;">PTKP</th>
                <th style="min-width: 80px !important;">Status</th>
                <th style="max-width: 70px !important;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($karyawans!=false) {
                  foreach($karyawans as $karyawan) {
                    echo "<tr>";
                      echo "<td>".$karyawan->no_urut."</td>";
                      echo "<td>".$karyawan->nik."</td>";
                      echo "<td>".$karyawan->nip."</td>";
                      echo "<td>".$karyawan->nama_pt."</td>";
                      echo "<td>".$karyawan->nama_departmen."</td>";
                      echo "<td>".$karyawan->nama_lengkap;
                        if ($karyawan->nama_alias) echo " (".$karyawan->nama_alias.")";
                      echo "</td>";
                      echo "<td>".$karyawan->jabatan."</td>";
                      echo "<td>".$karyawan->bpjs_kes."</td>";
                      echo "<td>".$karyawan->bpjs_tk."</td>";
                      echo "<td>".$karyawan->ptkp."</td>";
                      echo "<td>";
                        if($karyawan->isaktif)
                          echo "<span class='badge bg-success'>Aktif</span>";
                        else
                          echo "<span class='badge bg-danger'>Tidak Aktif</span>";
                      echo "</td>";
                      echo "<td>";
                        echo "<a href='".base_url('master/karyawanpt/ubah/?kode='.$karyawan->kode_pt_karyawan)."' title='Ubah'><i class='fa fa-edit'></i></a>";
                        echo " &nbsp; <a href='#' title='Hapus' class='hapus' kode='".$karyawan->kode_pt_karyawan."' nama='".$karyawan->nama_lengkap."' data-bs-toggle='modal' data-bs-target='#hapusModal'><i class='fa fa-trash text-danger'></i></a>";
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