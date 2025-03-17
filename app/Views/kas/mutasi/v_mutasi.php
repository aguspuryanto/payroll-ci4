<?php  
use App\Models\PencatatanJurnalUmumModel;
$pencatatanJurnalUmumModel = new PencatatanJurnalUmumModel();
?>
<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Kas</li>
          <li class="breadcrumb-item active" aria-current="page">Mutasi Kas</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar Mutasi Kas</h1>
      
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
                        <select class="form-select" id="jenis" name="jenis" aria-label="Floating label select">
                          <option value="">-- Semua Jenis Mutasi --</option>
                          <option value="Masuk" <?php if($jenis=="Masuk") echo "selected"; ?>>Masuk</option>
                          <option value="Keluar" <?php if($jenis=="Keluar") echo "selected"; ?>>Keluar</option>
                        </select>
                        <label for="jenis">Jenis Mutasi</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="periode" class='text-muted'>Periode Tanggal Transaksi</label>
                      <div class="mt-2">
                      </div>
                      <div class="mt-2">
                        <input type="text" name="periode" id="periode" class="form-control daterange" value="<?php echo $tglawal." - ".$tglakhir; ?>" />

                        <input type="checkbox" class="form-check-input" <?php if($abaikan==1) echo "checked"; ?> id="abaikan" name="abaikan" value="1" > <label for="abaikan" class="form-check-label">Abaikan Periode Tanggal Transaksi</label>
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
            <caption class="bg-dark text-light p-2">Mutasi Kas</caption>
            <thead>
              <tr>
                <th style="max-width: 70px !important;">Kode</th>
                <th style="width: 100px !important;">Tanggal</th>
                <th style="width: 120px !important;">Kas</th>
                <th style="max-width: 120px !important;">Nominal Perubahan (Rp.)</th>
                <th style="max-width: 120px !important;">Nominal Awal (Rp.)</th>
                <th style="max-width: 120px !important;">Nominal Akhir (Rp.)</th>
                <th style="max-width: 100px !important;">Keterangan</th>
                <th style="max-width: 100px !important;">Transaksi</th>
                <th style="max-width: 100px !important;">Jenis</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($kas_mutasis!=false) {
                  foreach($kas_mutasis as $kas_mutasi) {
                    echo "<tr>";
                      echo "<td class='text-end'>".$kas_mutasi->kode_kas_mutasi."</td>";
                      echo "<td>
                        <div>Input: ".date("Y-m-d", strtotime($kas_mutasi->tanggal_input))."</div>
                        <div>Transaksi: ".($kas_mutasi->tanggal_transaksi ? date("Y-m-d", strtotime($kas_mutasi->tanggal_transaksi)) : "-")."</div>
                        </td>";
                      
                      echo "<td>".$kas_mutasi->nama_kas."</td>";
                      echo "<td class='text-end'>".number_format($kas_mutasi->nominal_perubahan,0,".", ",")."</td>";
                      echo "<td class='text-end'>".number_format($kas_mutasi->nominal_awal,0,".", ",")."</td>";
                      echo "<td class='text-end'>".number_format($kas_mutasi->nominal_akhir,0,".", ",")."</td>";
                      echo "<td>".$kas_mutasi->keterangan."</td>";
                      echo "<td>".$kas_mutasi->transaksi."</td>";
                      echo "<td>".$kas_mutasi->jenis."</td>";
                      
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
