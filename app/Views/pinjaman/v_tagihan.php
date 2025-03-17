<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Pinjaman</li>
          <li class="breadcrumb-item active" aria-current="page">Tagihan</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar Tagihan</h1>
      
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
                          <option value="Tagihan" <?php if($status=="Tagihan") echo "selected"; ?>>Tagihan</option>
                          <option value="Lunas" <?php if($status=="Lunas") echo "selected"; ?>>Lunas</option>
                        </select>
                        <label for="status">Status Tagihan</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="periode" class='text-muted'>Periode Tanggal Tagihan</label>
                      <div class="mt-2">
                      </div>
                      <div class="mt-2">
                        <input type="text" name="periode" id="periode" class="form-control daterange" value="<?php echo $tglawal." - ".$tglakhir; ?>" />

                        <input type="checkbox" class="form-check-input" <?php if($abaikan==1) echo "checked"; ?> id="abaikan" name="abaikan" value="1" > <label for="abaikan" class="form-check-label">Abaikan Periode Tanggal Tagihan</label>
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
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Tagihan</caption>
            <thead>
              <tr>
                <th style="min-width: 100px !important; max-width: 170px !important;">PT</th>
                <th style="max-width: 130px !important;">Kode Bon Pinjaman</th>
                <th style="max-width: 130px !important;">Kode Bon Payroll</th>
                <th style="min-width: 100px !important;">Tgl. Tagihan</th>
                <th style="min-width: 120px !important;">Karyawan</th>
                <th style="max-width: 120px !important;">Nominal (Rp.)</th>
                <th style="max-width: 100px !important;">Status</th>             
              </tr>
            </thead>
            <tbody>
              <?php   
                if($tagihans!=false) {
                  foreach($tagihans as $tagihan) {
                    echo "<tr>";
                      echo "<td>".$tagihan->nama_pt."</td>";
                      echo "<td>".$tagihan->kode_bon."</td>";
                      echo "<td>".$tagihan->kode_bon_payroll."</td>";
                      echo "<td>".date("Y-m-d", strtotime($tagihan->tanggal_tagihan))."</td>";
                      echo "<td>".$tagihan->nama_lengkap;
                      
                      echo "</td>";
                      echo "<td class='text-end'>".number_format($tagihan->nominal,0,".", ",")."</td>";
                      echo "<td>".$tagihan->status."</td>";                     
                      
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