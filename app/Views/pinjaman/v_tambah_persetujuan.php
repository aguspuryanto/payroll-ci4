<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Bon</li>
          <li class="breadcrumb-item"><a href="<?= site_url('bon/daftar'); ?>">Persetujuan</a></li>
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
    <div class="row">
      <div class="col-md-12">
        <form method="post">          
          <div class="row mb-3">
            <label for="jenis_bon" class="col-sm-2 col-form-label">Jenis Bon</label>
            <div class="col-sm-6">
              <p class="form-control-plaintext"><?= $bon->jenis ?></p>
            </div>
          </div>         
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-2 col-form-label">Tanggal Pengajuan</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?= date("Y-m-d") ?></p>
              <input type="hidden" class="form-control" required id="tanggal_pengajuan" name="tanggal_pengajuan" value="<?= date("Y-m-d"); ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-2 col-form-label">PT</label>
            <div class="col-sm-6">
              <p class="form-control-plaintext"><?= $bon->nama_pt ?></p>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="karyawan" class="col-sm-2 col-form-label">Karyawan Yang Mengajukan</label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $bon->nama_lengkap ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nominal" class="col-sm-2 col-form-label">Nominal</label>
            <div class="col-sm-3">
              <p class="form-control-plaintext">Rp. <?= number_format($bon->nominal,0,".",","); ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-3">
              <p class="form-control-plaintext"><?= $bon->keterangan ?></p>              
            </div>
          </div>
          <div class="row mb-3">
            <label for="kas" class="col-sm-2 col-form-label">Kas Asal</label>
            <div class="col-sm-5">
              <p class="form-control-plaintext"><?= $bon->nama_kas ?></p>
            </div>
          </div>
          <?php if($bon->jenis == 'Pemindahan Kas') { ?>
            <div class="row mb-3">
              <label for="kas_tujuan" class="col-sm-2 col-form-label">Kas Tujuan</label>
              <div class="col-sm-5">
                <p class="form-control-plaintext"><?= $bon->nama_kas_tujuan ?></p>
              </div>
            </div>
          <?php } ?>
          <?php if($bon->jenis == 'Pinjaman') { ?>
          <div class="row mb-3">
            <label for="cicilan" class="col-sm-2 col-form-label">Lama Cicilan</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?= $bon->cicilan ?> bulan</p>
            </div>
          </div>
          <?php } ?>
          <div class="row mt-5 mb-3">
            <div class="col-sm-12">
              <input type="checkbox" name="sudah" value="sudah" id="sudah" required> <label for="sudah">Data di atas sudah benar <span class='text-danger'>*</span></label>
            </div>
          </div>
          <input type="hidden" name="kode_bon" value="<?= $bon->kode_bon; ?>">
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('bon/daftar'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>