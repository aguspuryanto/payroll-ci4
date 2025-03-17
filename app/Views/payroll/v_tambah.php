<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Payroll</li>
          <li class="breadcrumb-item"><a href="<?= site_url('payroll/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </nav>
      <h1 class="h2">Tambah Payroll</h1>        
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
            <label for="pt" class="col-sm-2 col-form-label">PT <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <select class="form-select select2" id="pt" name="pt" required>
                <option value=''>-- Pilih PT --</option>
                <?php  
                foreach($pts as $pt) {
                  echo "<option value='$pt->kode_pt'>$pt->nama</option>";
                }
                ?>
              </select>
              <div class="text-danger error"></div>
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
            <label for="keterangan" class="col-sm-2 col-form-label">Detil</label>
            <div class="col-sm-10 text-end">
              <a href="#" class="btn btn-outline-primary upload disabled" data-bs-toggle='modal' data-bs-target='#uploadModal'><i class="fa fa-file-csv"></i> Upload CSV</a>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-12 table-responsive">
              <table class="table table-hover table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="min-width: 80px;">No Urut</th>
                    <th style="min-width: 90px;">NIK</th>
                    <th style="min-width: 200px; ">Nama</th>
                    <th style="min-width: 120px;">Jabatan</th>
                    <th style="min-width: 110px;">Gaji Pokok</th>
                    <th style="min-width: 100px;">Gaji Jabatan Tetap</th>
                    <th style="min-width: 120px;">Tunjangan</th>
                    <th style="min-width: 120px;">Lembur</th>
                    <th style="min-width: 120px;">Lain-lain</th>
                    <th style="min-width: 120px;">THR</th>
                    <th style="min-width: 120px;">Gratifikasi</th>
                    <th style="min-width: 120px;">Rapel</th>
                    <th style="min-width: 100px;">Total</th>
                    <th style="min-width: 100px;">BPJS Kes</th>
                    <th style="min-width: 100px;">BPJS TK</th>
                    <th style="min-width: 100px;">Pinjaman Pegawai</th>
                    <th style="min-width: 100px;">Gaji Nett</th>
                    <th style="min-width: 100px;">BPJS Kes PT</th>
                    <th style="min-width: 100px;">BPJS JKM PT</th>
                    <th style="min-width: 100px;">BPJS JKK PT</th>
                    <th style="min-width: 100px;">BPJS JP PT</th>
                    <th style="min-width: 100px;">BPJS JHT PT</th>
                  </tr>
                </thead>
                <tbody class="body-payroll">
                  <tr>
                    <td colspan='22' class='fst-italic'>Belum ada data</td>
                  </tr>
                </tbody>
                <tfoot class="footer-payroll fw-bold">
                  <tr>
                    <td class="text-end" colspan="4">TOTAL</td>
                    <td class="text-end footer-total" tag="gaji_pokok">0</td>
                    <td class="text-end footer-total" tag="gaji_jabatan_tetap">0</td>
                    <td class="text-end footer-total" tag="tunjangan">0</td>
                    <td class="text-end footer-total" tag="lembur">0</td>
                    <td class="text-end footer-total" tag="lainlain">0</td>
                    <td class="text-end footer-total" tag="thr">0</td>
                    <td class="text-end footer-total" tag="gratifikasi">0</td>
                    <td class="text-end footer-total" tag="rapel">0</td>
                    <td class="text-end footer-total" tag="total">0</td>
                    <td class="text-end footer-total" tag="biaya_bpjs_kes">0</td>
                    <td class="text-end footer-total" tag="biaya_bpjs_tk">0</td>
                    <td class="text-end footer-total" tag="pinjaman">0</td>
                    <td class="text-end footer-total" tag="nett">0</td>
                    <td class="text-end footer-total" tag="bpjs_kes_PT">0</td>
                    <td class="text-end footer-total" tag="bpjs_jkm_PT">0</td>
                    <td class="text-end footer-total" tag="bpjs_jkk_PT">0</td>
                    <td class="text-end footer-total" tag="bpjs_jp_PT">0</td>
                    <td class="text-end footer-total" tag="bpjs_jht_PT">0</td>
                  </tr>
                </tfoot>
              </table>              
            </div>
          </div>  
          
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary disabled" id="simpan">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('payroll/daftar'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>