<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Laporan</li>
          <li class="breadcrumb-item active" aria-current="page">Karyawan</li>
        </ol>
      </nav>
      <h1 class="h2">Laporan Karyawan</h1>
      
    </div>

    <div class="row mb-3">
      <div class="col-md-12 text-end">
        <a target='_blank' href="<?= site_url('laporan/karyawan/cetak?'.$_SERVER['QUERY_STRING']); ?>" class='btn btn-outline-primary'><i class='fa fa-print'></i> Cetak</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Karyawan</caption>
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