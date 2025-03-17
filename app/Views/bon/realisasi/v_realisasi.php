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
          <li class="breadcrumb-item">Bon</li>
          <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
        </ol>
      </nav>
      <h1 class="h2">Daftar Realisasi</h1>
      
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
                    
                    <div class="col-md-6 mb-3">
                      <label for="periode" class='text-muted'>Periode Tanggal Realisasi</label>
                      <div class="mt-2">
                      </div>
                      <div class="mt-2">
                        <input type="text" name="periode" id="periode" class="form-control daterange" value="<?php echo $tglawal." - ".$tglakhir; ?>" />

                        <input type="checkbox" class="form-check-input" <?php if($abaikan==1) echo "checked"; ?> id="abaikan" name="abaikan" value="1" > <label for="abaikan" class="form-check-label">Abaikan Periode Tanggal Realisasi</label>
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
        <div class="col-md-12">
          <a class="btn btn-primary" href="<?= base_url('bon/daftar?status=2'); ?>">Tambah Realisasi Baru</a>
        </div>
      </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Realisasi</caption>
            <thead>
              <tr>
                <th style="max-width: 70px !important;">Kode Bon</th>
                <th style="min-width: 150px !important;">Bukti</th>
                <th style="min-width: 100px !important;">Tanggal</th>
                <th style="min-width: 120px !important;">Karyawan</th>
                <th style="max-width: 120px !important;">Jenis</th>
                <th style="max-width: 100px !important;">Nominal (Rp.)</th>
                <th style="min-width: 100px !important;">Kas Asal</th>
                <th style="min-width: 100px !important;">Kas Tujuan</th>
                <th style="min-width: 300px; max-width: 300px !important;">Pencatatan Jurnal Umum</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                if($bons!=false) {
                  foreach($bons as $bon) {
                    echo "<tr>";
                      echo "<td class='text-end'>".$bon->kode_bon."</td>";
                      echo "<td><img class='bukti' src='".base_url('assets/realisasi/'.$bon->bukti_realisasi)."'></td>";
                      echo "<td>
                        <div>Pengajuan: ".date("Y-m-d", strtotime($bon->tanggal_pengajuan))."</div>
                        <div>Persetujuan: ".($bon->tanggal_persetujuan ? date("Y-m-d", strtotime($bon->tanggal_persetujuan)) : "-")."</div>
                        <div>Realisasi: ".($bon->tanggal_realisasi ? date("Y-m-d", strtotime($bon->tanggal_realisasi)) : "-")."</div>
                        </td>";
                      
                      echo "<td class='text-end'>".$bon->nama_lengkap."</td>";
                      echo "<td>".$bon->jenis."</td>";
                      echo "<td class='text-end'>".number_format($bon->nominal,0,".", ",")."</td>";
                      echo "<td>".$bon->nama_kas."</td>";
                      echo "<td>".$bon->nama_kas_tujuan."</td>";
                      echo "<td>";
                        $where = array('bon.kode_bon'=>$bon->kode_bon);
                        $catats = $pencatatanJurnalUmumModel->getPencatatanJurnalUmum($where)->find();
                        if(!empty($catats)) {
                          echo "<table class='table table-striped table-hover'>
                                  <tr>
                                    <th>Kode COA</th>
                                    <th>Nama Transaksi</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                  </tr>";
                          foreach($catats as $catat) {
                            echo "<tr>";
                              echo "<td>".$catat->kode_coa."</td>";
                              echo "<td>".$catat->nama_transaksi."</td>";
                              if($catat->posisi=='KREDIT') echo "<td></td>";
                              echo "<td class='text-end'>".number_format($catat->nominal, 0, ".", ",")."</td>";
                              if($catat->posisi=='DEBET') echo "<td></td>";
                            echo "</tr>";
                          }
                          echo "</table>";
                        }
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

<style type="text/css">
  .bukti { max-width: 140p; max-height: 200px; }
</style>