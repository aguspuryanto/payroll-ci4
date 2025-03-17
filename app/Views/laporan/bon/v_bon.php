<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Laporan</li>
          <li class="breadcrumb-item active" aria-current="page">Bon</li>
        </ol>
      </nav>
      <h1 class="h2">Laporan Bon</h1>
      
    </div>
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Filter
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form>
                  <div class="row">
                    <div class="col-md-6">                      
                      <div class="form-floating mb-3">
                        <select class="form-select" id="status" name="status" aria-label="Floating label select">
                          <option value="">-- Semua Status --</option>
                          <?php  
                          foreach($bon_statuss as $bon_status) {
                            $selected = ($status==$bon_status->kode_bon_status) ? "selected" : "";
                            echo "<option value='".$bon_status->kode_bon_status."' $selected>".$bon_status->nama."</option>";
                          }
                          ?>
                        </select>
                        <label for="status">Status Bon</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="periode" class='text-muted'>Periode Tanggal Pengajuan</label>
                      <div class="mt-2">
                      </div>
                      <div class="mt-2">
                        <input type="text" name="periode" id="periode" class="form-control daterange" value="<?php echo $tglawal." - ".$tglakhir; ?>" />

                        <input type="checkbox" class="form-check-input" <?php if($abaikan==1) echo "checked"; ?> id="abaikan" name="abaikan" value="1" > <label for="abaikan" class="form-check-label">Abaikan Periode Tanggal Pengajuan</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-12 text-end">
        <a target='_blank' href="<?= site_url('laporan/bon/cetak?'.$_SERVER['QUERY_STRING']); ?>" class='btn btn-outline-primary'><i class='fa fa-print'></i> Cetak</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Bon</caption>
            <thead>
              <tr>
                <th style="max-width: 70px !important;">Kode</th>
                <th style="min-width: 100px !important;">Tanggal</th>
                <th style="max-width: 100px !important;">Status</th>
                <th style="min-width: 120px !important;">Karyawan</th>
                <th style="max-width: 120px !important;">Jenis</th>
                <th style="max-width: 100px !important;">Nominal (Rp.)</th>
                <th style="max-width: 100px !important;">Kas Asal</th>
                <th style="max-width: 100px !important;">Kas Tujuan</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($bons!=false) {
                  foreach($bons as $bon) {
                    echo "<tr>";
                      echo "<td class='text-end'>".$bon->kode_bon."</td>";
                      echo "<td>
                        <div>Pengajuan: ".date("Y-m-d H:i:s", strtotime($bon->tanggal_pengajuan))."</div>
                        <div>Persetujuan: ".($bon->tanggal_persetujuan ? date("Y-m-d H:i:s", strtotime($bon->tanggal_persetujuan)) : "-")."</div>
                        <div>Realisasi: ".($bon->tanggal_realisasi ? date("Y-m-d H:i:s", strtotime($bon->tanggal_realisasi)) : "-")."</div>
                        <div>Konfirmasi: ".($bon->tanggal_konfirmasi ? date("Y-m-d H:i:s", strtotime($bon->tanggal_konfirmasi)) : "-")."</div>
                        </td>";
                      echo "<td>";
                      $bg = "bg-secondary";
                      switch($bon->kode_bon_status) {
                        case 0:
                          $bg = "text-bg-info"; break;
                        case 4:
                          $bg = "text-bg-success"; break;
                        case 5:
                          $bg = "text-bg-danger px-3"; break;
                        case 6:
                          $bg = "text-bg-danger"; break;
                      } 

                      echo "<span class='badge $bg'>$bon->nama_bon_status</span></td>";
                      echo "<td>".$bon->nama_lengkap."</td>";
                      echo "<td>".$bon->jenis."</td>";
                      echo "<td class='text-end'>".number_format($bon->nominal,0,".", ",")."</td>";
                      echo "<td>".$bon->nama_kas."</td>";
                      echo "<td>".$bon->nama_kas_tujuan."</td>";
                      
                     
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