<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Bon</li>
          <li class="breadcrumb-item"><a href="<?= site_url('bon/daftar?status=0'); ?>">Persetujuan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </nav>
      <h1 class="h2">Tambah Persetujuan</h1>        
    </div>

    <div class="row">
      <div class="col-md-12 text-danger">
        <?php 
          if(isset($errors)) {
            echo "<ul class='mb-3'>";
            foreach ($errors as $error){
              echo "<li>".esc($error)."</li>";
            }
            echo "</ul>";
          }
        ?>
      </div>
    </div>
    <form method="post">  
    <div class="row">
      <div class="col-md-6">
          <div class="row mb-3">
            <label for="jenis_bon" class="col-sm-4 col-form-label">Kode Bon</label>
            <div class="col-sm-6">
              <p class="form-control-plaintext"><?= $bon->kode_bon ?></p>
            </div>
          </div>           
          <div class="row mb-3">
            <label for="jenis_bon" class="col-sm-4 col-form-label">Jenis Bon</label>
            <div class="col-sm-6">
              <p class="form-control-plaintext"><?= $bon->jenis ?></p>
            </div>
          </div>         
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-4 col-form-label">Tanggal Pengajuan</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?= date("Y-m-d") ?></p>
              <input type="hidden" class="form-control" required id="tanggal_pengajuan" name="tanggal_pengajuan" value="<?= date("Y-m-d"); ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-4 col-form-label">PT</label>
            <div class="col-sm-6">
              <p class="form-control-plaintext"><?= $bon->nama_pt ?></p>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="karyawan" class="col-sm-4 col-form-label">Karyawan Yang Mengajukan</label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $bon->nama_lengkap ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nominal" class="col-sm-4 col-form-label">Nominal</label>
            <div class="col-sm-3">
              <p class="form-control-plaintext">Rp. <?= number_format($bon->nominal,0,".",","); ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
            <div class="col-sm-3">
              <p class="form-control-plaintext"><?= $bon->keterangan ?></p>              
            </div>
          </div>
          <div class="row mb-3">
            <label for="kas" class="col-sm-4 col-form-label">Kas Asal</label>
            <div class="col-sm-5">
              <p class="form-control-plaintext"><?= $bon->nama_kas ?></p>
            </div>
          </div>
          <?php if($bon->jenis == 'Pemindahan Kas') { ?>
            <div class="row mb-3">
              <label for="kas_tujuan" class="col-sm-4 col-form-label">Kas Tujuan</label>
              <div class="col-sm-5">
                <p class="form-control-plaintext"><?= $bon->nama_kas_tujuan ?></p>
              </div>
            </div>
          <?php } ?>
          <?php if($bon->jenis == 'Pinjaman') { ?>
          <div class="row mb-3">
            <label for="cicilan" class="col-sm-4 col-form-label">Lama Cicilan</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?= $bon->cicilan ?> bulan</p>
            </div>
          </div>
          <?php } ?>
          
      </div>
      <div class="col-md-6">
            <?php  
              if(!empty($catats)) {
                echo "<table class='table table-striped table-hover caption-top'>
                        <caption class='bg-dark text-light p-2'>Jurnal Umum</caption>
                        <thead>
                        <tr>
                          <th>Kode COA</th>
                          <th>Nama Transaksi</th>
                          <th>Debet</th>
                          <th>Kredit</th>
                        </tr>
                        </thead>";
                echo "<tbody>";
                foreach($catats as $catat) {
                  echo "<tr>";
                    echo "<td>".$catat->kode_coa."</td>";
                    echo "<td>".$catat->nama_transaksi."</td>";
                    if($catat->posisi=='KREDIT') echo "<td></td>";
                    echo "<td class='text-end'>".number_format($catat->nominal, 0, ".", ",")."</td>";
                    if($catat->posisi=='DEBET') echo "<td></td>";
                  echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
              }
            ?>
            <div class="row mb-3">
              <label for="kas" class="col-sm-4 col-form-label">Histori Status</label>
              <div class="col-sm-7">
                <?php  
                echo "<ul>";
                foreach($historis as $histori) {
                  echo "<li>".date("Y-m-d H:i:s", strtotime($histori->created_at))." - ".$histori->nama_bon_status."</li>";
                }
                echo "</ul>";
                ?>
              </div>
            </div>
          </div>
    </div>
    <div class="col-row">
      <div class="col-md-12">
          <div class="row mt-5 mb-3">
            <label for="cicilan" class="col-sm-2 col-form-label">Status Bon <span class="text-danger">*</span></label>
            <div class="col-sm-8 mt-2">
              <div class="form-check-inline">
                <input class="form-check-inputs" type="radio" name="status" id="disetujui" value="2" required>
                <label class="form-check-label" for="disetujui">
                  Data di atas sudah benar dan bisa disetujui
                </label>
              </div>
              <div class="form-check-inline ms-3">
                <input class="form-check-inputs" type="radio" name="status" id="tolak" value="6" required>
                <label class="form-check-label" for="tolak">
                  Tolak Bon ini
                </label>
              </div>
            </div>
          </div>
          <input type="hidden" name="kode_bon" value="<?= $bon->kode_bon; ?>">
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
       
      </div>
    </div>
  </form>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('bon/daftar'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>