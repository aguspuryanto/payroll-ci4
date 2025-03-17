<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Bon</li>
          <li class="breadcrumb-item"><a href="<?= site_url('bon/daftar?status=1'); ?>">Pencatatan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </nav>
      <h1 class="h2">Tambah Pencatatan</h1>        
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
      <div class="col-md-5">
        <div class="row mb-3">
          <label for="jenis_bon" class="col-sm-2 col-form-label">Kode Bon</label>
          <div class="col-sm-6">
            <p class="form-control-plaintext"><?= $bon->kode_bon ?></p>
          </div>
        </div>            
        <div class="row mb-3">
          <label for="jenis_bon" class="col-sm-5 col-form-label">Jenis Bon</label>
          <div class="col-sm-6">
            <p class="form-control-plaintext"><?= $bon->jenis ?></p>
          </div>
        </div>         
        <div class="row mb-3">
          <label for="tanggal_pengajuan" class="col-sm-5 col-form-label">Tanggal Pengajuan</label>
          <div class="col-sm-4">
            <p class="form-control-plaintext"><?= date("Y-m-d") ?></p>
            <input type="hidden" class="form-control" required id="tanggal_pengajuan" name="tanggal_pengajuan" value="<?= date("Y-m-d"); ?>">
          </div>
        </div>
        <div class="row mb-3">
          <label for="tanggal_pengajuan" class="col-sm-5 col-form-label">PT</label>
          <div class="col-sm-6">
            <p class="form-control-plaintext"><?= $bon->nama_pt ?></p>
          </div>
        </div> 
        <div class="row mb-3">
          <label for="karyawan" class="col-sm-5 col-form-label">Karyawan Yang Mengajukan</label>
          <div class="col-sm-4">
            <p class="form-control-plaintext"><?= $bon->nama_lengkap ?></p>
          </div>
        </div>
        <div class="row mb-3">
          <label for="nominal" class="col-sm-5 col-form-label">Nominal</label>
          <div class="col-sm-3">
            <input type="hidden" id="nominal_bon" value="<?= $bon->nominal ?>">
            <p class="form-control-plaintext">Rp. <?= number_format($bon->nominal,0,".",","); ?></p>
          </div>
        </div>
        <div class="row mb-3">
          <label for="keterangan" class="col-sm-5 col-form-label">Keterangan</label>
          <div class="col-sm-3">
            <p class="form-control-plaintext"><?= $bon->keterangan ?></p>              
          </div>
        </div>
        <div class="row mb-3">
          <label for="kas" class="col-sm-5 col-form-label">Kas Asal</label>
          <div class="col-sm-5">
            <p class="form-control-plaintext"><?= $bon->nama_kas ?></p>
          </div>
        </div>
        <?php if($bon->jenis == 'Pemindahan Kas') { ?>
          <div class="row mb-3">
            <label for="kas_tujuan" class="col-sm-5 col-form-label">Kas Tujuan</label>
            <div class="col-sm-5">
              <p class="form-control-plaintext"><?= $bon->nama_kas_tujuan ?></p>
            </div>
          </div>
        <?php } ?>
        <?php if($bon->jenis == 'Pinjaman') { ?>
        <div class="row mb-3">
          <label for="cicilan" class="col-sm-5 col-form-label">Lama Cicilan</label>
          <div class="col-sm-2">
            <p class="form-control-plaintext"><?= $bon->cicilan ?> bulan</p>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="col-md-7">
        <fieldset>
          <legend>Jurnal Umum</legend>
        <div class="row mb-3">
          <label for="coa" class="col-sm-3 col-form-label">COA <span class="text-danger">*</span></label>
          <div class="col-sm-7">
            <select name='coa' id="coa" class="form-select select2">
              <option value=''>-- Pilih COA --</option>
              <?php  
              $jns = "";
              //if(!empty($coas)) {
                foreach($coas as $coa) {
                  if($jns != $coa->nama_coa_jenis_biaya) {
                    if(!empty($jns)) echo "</optgroup>";
                    echo "<optgroup label='".$coa->nama_coa_jenis_biaya."'>";
                    $jns = $coa->nama_coa_jenis_biaya;               
                  }
                  echo "<option value='".$coa->kode_coa."' nama='".$coa->nama_transaksi."'>".$coa->kode_coa." - ".$coa->nama_transaksi."</option>";

                }
                echo "</optgroup>";
              //}
              ?>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="nominal" class="col-sm-3 col-form-label">Nominal <span class="text-danger">*</span></label>
          <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" class="form-control number text-end" required id="nominal" name="nominal" value="<?= empty(set_value('nominal')) ? 0 : set_value('nominal'); ?>">
              </div>
          </div>
        </div>
        <div class="row mb-3">
          <label for="nominal" class="col-sm-3 col-form-label">Posisi <span class="text-danger">*</span></label>
          <div class="col-sm-4 mt-2">
            <input type="radio" class="form-check-input" name="posisi" id="debet" value="DEBET" required> <label for="debet">Debet</label> &nbsp; 
            <input type="radio" class="form-check-input" name="posisi" id="kredit" value="KREDIT" required> <label for="kredit">Kredit</label>
          </div>
        </div>
        <input type="button" class="btn btn-primary" name="tambahkan" id="tambahkan" value="Tambahkan">
        <div class="row mb-3 mt-3">
          <div class="col-sm-12 table-responsive">
            <table class="table table-hover table-stripped table-bordered">
              <tr>
                <th style="width: 120px;">Kode</th>
                <th style="min-width: 150px;">Nama Transaksi</th>
                <th style="max-width: 130px; width: 130px;">Debet</th>
                <th style="max-width: 130px; width: 130px;">Kredit</th>
                <th style="max-width: 80px; width: 80px;">Hapus</th>
              </tr>
              <tbody id="jurnal-body">
                <tr>
                  <td colspan='5' class='fst-italic'>Belum ada data</td>
                </tr>
              </tbody>
              <tfoot id="jurnal-foot">
                <tr>
                  <td colspan='2' class='text-end'>TOTAL</td>
                  <td class='text-end' id='footer-debet'>0</td>
                  <td class='text-end' id='footer-kredit'>0</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
            <i>*) Klik tombol simpan jika sudah selesai</i>
          </div>
        </div>
      </fieldset>
      </div>
    </div>
      <div class="row">
        <div class="col-sm-12">
          <input type="hidden" name="kode_bon" value="<?= $bon->kode_bon; ?>">
          <button type="submit" name="btnSimpan" id="btnSimpan" disabled value="simpan" class="btn btn-primary">Simpan</button>
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