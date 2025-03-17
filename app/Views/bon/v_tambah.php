<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Bon</li>
          <li class="breadcrumb-item"><a href="<?= site_url('bon/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </nav>
      <h1 class="h2">Tambah Bon</h1>        
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
            <label for="jenis_bon" class="col-sm-2 col-form-label">Jenis Bon <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" id="jenis_bon" name="jenis_bon" required>
                <option value=''>-- Pilih Jenis Bon --</option>
                <option value='Pengeluaran' tag='keluar'>Pengeluaran</option>
                <option value='Pinjaman' tag='pinjam'>Pinjaman</option>
                <option value='Pemindahan Kas' tag='pindah'>Pemindahan Kas</option>
              </select>
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
            <label for="karyawan" class="col-sm-2 col-form-label">Karyawan Yg Mengajukan <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <select class="form-select select2" required name="karyawan" id="karyawan">
                <option value=''>-- Pilih Karyawan --</option>
                <?php  
                foreach ($karyawans as $karyawan) {
                  echo "<option value='".$karyawan->kode_pt_karyawan."'>";
                    echo "[".$karyawan->nip."] ".$karyawan->nama_lengkap;
                  echo "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nominal" class="col-sm-2 col-form-label">Nominal <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" class="form-control number text-end" required id="nominal" name="nominal" value="<?= empty(set_value('nominal')) ? 0 : set_value('nominal'); ?>">
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-3">
              <textarea class="form-control" name="keterangan" id="keterangan"><?= set_value('keterangan'); ?></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <label for="kas" class="col-sm-2 col-form-label">Kas Asal <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <select class="form-select select2" required name="kas" id="kas">
                <option value=''>-- Pilih Kas Asal --</option>
                <?php  
                foreach ($kass as $kas) {
                  echo "<option value='".$kas->kode_kas."'>";
                    echo  $kas->nama;
                  echo "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="kas_tujuan" class="col-sm-2 col-form-label">Kas Tujuan <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <select class="form-select select2 pindah opsi" disabled required name="kas_tujuan" id="kas_tujuan">
                <option value=''>-- Pilih Kas Tujuan --</option>
                <?php  
                foreach ($kass as $kas) {
                  echo "<option value='".$kas->kode_kas."'>";
                    echo  $kas->nama;
                  echo "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="cicilan" class="col-sm-2 col-form-label">Lama Cicilan <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <div class="input-group">
                <input type="text" class="form-control number text-end pinjam opsi" disabled required id="cicilan" name="cicilan" value="<?= set_value('cicilan'); ?>">
                <span class="input-group-text">Bulan</span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label for="cicilan" class="col-sm-2 col-form-label">Persetujuan</label>
            <div class="col-sm-3 mt-2">
              <input type="checkbox" class="keluar pinjam opsi" disabled name="persetujuan" value="1" id="persetujuan"> &nbsp; <label for="persetujuan">Ya, butuh persetujuan Direksi</label>
            </div>
          </div>
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