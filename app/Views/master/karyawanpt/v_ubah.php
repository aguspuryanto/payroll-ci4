<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item"><a href="<?= site_url('master/karyawanpt'); ?>">Karyawan PT</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
      </nav>
      <h1 class="h2">Ubah Karyawan PT</h1>        
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
        <form method="post" action="<?= base_url('master/karyawanpt/ubah?kode='.$karyawan->kode_pt_karyawan) ?>" >
          <div class="row mb-3">
            <label for="no_urut" class="col-sm-2 col-form-label">No. Urut <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <input type="text" class="form-control number text-end" required id="no_urut" name="no_urut" maxlength="5" value="<?= $karyawan->no_urut; ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="kode_departmen" class="col-sm-2 col-form-label">Departmen <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" required name="kode_departmen" id="kode_departmen" autofocus>
                <option value=''>-- Pilih Departmen --</option>
                <?php  
                $pt = "";
                foreach($departmens as $departmen) {
                  if($pt != $departmen->nama_pt) {
                    if(!empty($pt)) echo "</optgroup>";
                    echo "<optgroup label='".$departmen->nama_pt."'>";
                    $pt=$departmen->nama_pt;
                  }
                  $selected = ($karyawan->kode_departmen==$departmen->kode_departmen) ? "selected" : "";
                  echo "<option value='".$departmen->kode_departmen."' id_kebun='".$departmen->id_kebun."' $selected>".$departmen->nama."</option>";
                }
                echo "</optgroup>";
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="afdeling" class="col-sm-2 col-form-label">Afdeling</label>
            <div class="col-sm-2">
              <select class="form-select select2"  name="afdeling" id="afdeling">
                <option value=''>-- Pilih Afdeling --</option>
                <?php  
                foreach($afdelings as $afdeling) {                  
                  $selected = ($karyawan->id_afdeling==$afdeling->id) ? "selected" : "";
                  echo "<option value='".$afdeling->id."' $selected>".$afdeling->nama."</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label for="karyawan_jenis" class="col-sm-2 col-form-label">Jenis Karyawan <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" required name="karyawan_jenis" id="karyawan_jenis">
                <option value=''>-- Pilih Jenis Karyawan --</option>
                <?php  
                foreach($karyawan_jeniss as $karyawan_jenis) {                  
                  $selected = ($karyawan->kode_karyawan_jenis==$karyawan_jenis->kode_karyawan_jenis) ? "selected" : "";
                  echo "<option value='".$karyawan_jenis->kode_karyawan_jenis."' $selected>".$karyawan_jenis->nama."</option>";
                }
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" maxlength="75"  required value="<?= $karyawan->nama_lengkap; ?>">
              <span class="text-muted">Maks. 75 karakter</span>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nama_alias" class="col-sm-2 col-form-label">Nama Alias</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="nama_alias" name="nama_alias" maxlength="20" value="<?= $karyawan->nama_alias; ?>">
              <span class="text-muted">Maks. 20 karakter</span>
            </div>
          </div>

          <div class="row mb-3">
            <label for="Pria" class="col-sm-2 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
            <div class="col-sm-5 mt-2">
              <input type="radio" class="form-check-input" name="jenis_kelamin" id="Pria" value="Pria" required <?php if($karyawan->jenis_kelamin=="Pria") echo "checked"; ?>> <label for="Pria" class="form-check-label">Pria</label> &nbsp;
              <input type="radio" class="form-check-input" name="jenis_kelamin" id="Wanita" value="Wanita" required <?php if($karyawan->jenis_kelamin=="Wanita") echo "checked"; ?>> <label for="Wanita" class="form-check-label">Wanita</label>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nik" class="col-sm-2 col-form-label">NIK <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <input type="text" pattern="[0-9]{16}" placeholder="16 digit" maxlength="16" class="form-control" name="nik" id="nik" required value="<?= $karyawan->nik; ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="nip" class="col-sm-2 col-form-label">NIP <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="nip" id="nip" maxlength="30" required value="<?= $karyawan->nip; ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="npwp" class="col-sm-2 col-form-label">NPWP</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="npwp" id="npwp" maxlength="16" value="<?= $karyawan->npwp; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat/ Tgl Lahir <span class="text-danger">*</span></label>
            <div class="col-auto">
              <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required value="<?= $karyawan->tempat_lahir; ?>">
            </div>
            <div class="col-auto"><p class="form-control-plaintext">/</p></div>
            <div class="col-auto">
              <input type="date" class="form-control" name="tanggal_lahir" max="<?= date("Y-m-d"); ?>" id="tanggal_lahir" required value="<?= date("Y-m-d", strtotime($karyawan->tanggal_lahir)); ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="agama" class="col-sm-2 col-form-label">Agama <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <select class="form-select select2" required name="agama" id="agama">
                <option value=''>-- Pilih Agama --</option>
                <?php  
                foreach($agamas as $agama) {
                  
                  $selected = ($karyawan->kode_agama==$agama->kode_agama) ? "selected" : "";
                  echo "<option value='".$agama->kode_agama."' $selected>".$agama->nama."</option>";
                }
                ?>
              </select>
            </div>
          </div> 
          
          <div class="row mb-3">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat <span class="text-danger">*</span></label>
            <div class="col-sm-6">
              <textarea class="form-control" required name="alamat" id="alamat"><?= $karyawan->alamat; ?></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <label for="telepon" class="col-sm-2 col-form-label">Telepon <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <input type="text" class="form-control" maxlength="25" name="telepon" id="telepon" required value="<?= $karyawan->telepon; ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="pendidikan" class="col-sm-2 col-form-label">Pendidikan <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <select class="form-select select2" name="pendidikan" id="pendidikan" required>
                <option value=''>-- Pilih Pendidikan --</option>
                <option value="SD" <?php if($karyawan->pendidikan=="SD") echo "selected"; ?>>SD</option>
                <option value="SMP" <?php if($karyawan->pendidikan=="SMP") echo "selected"; ?>>SMP</option>
                <option value="SMA" <?php if($karyawan->pendidikan=="SMA") echo "selected"; ?>>SMA</option>
                <option value="Diploma" <?php if($karyawan->pendidikan=="Diploma") echo "selected"; ?>>Diploma</option>
                <option value="S1" <?php if($karyawan->pendidikan=="S1") echo "selected"; ?>>S1</option>
                <option value="S2" <?php if($karyawan->pendidikan=="S2") echo "selected"; ?>>S2</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="status_perkawinan" class="col-sm-2 col-form-label">Status Perkawinan <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" name="status_perkawinan" id="status_perkawinan" required>
                <option value=''>-- Pilih Status Perkawinan --</option>
                <option value="Tidak Kawin"  <?php if($karyawan->status_perkawinan=="Tidak Kawin") echo "selected"; ?>>Tidak Kawin</option>
                <option value="Kawin"  <?php if($karyawan->status_perkawinan=="Kawin") echo "selected"; ?>>Kawin</option>
                <option value="Duda/Janda"  <?php if($karyawan->status_perkawinan=="Duda/Janda") echo "selected"; ?>>Duda/Janda</option>                
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label for="rekening_no" class="col-sm-2 col-form-label">No. Rekening <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <input type="text" class="form-control" maxlength="25" name="rekening_no" id="rekening_no" required value="<?= $karyawan->rekening_no; ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="mulai_bekerja" class="col-sm-2 col-form-label">Mulai Bekerja <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <input type="date" class="form-control" name="mulai_bekerja" id="mulai_bekerja" required value="<?= date("Y-m-d", strtotime($karyawan->tanggal_bekerja)); ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="jabatan" class="col-sm-2 col-form-label">Jabatan <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <input type="text" class="form-control" maxlength="45" name="jabatan" id="jabatan" required value="<?= $karyawan->jabatan; ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-4">
              <textarea class="form-control" name="keterangan" id="keterangan"><?= $karyawan->keterangan; ?></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <label for="bpjs_kes" class="col-sm-2 col-form-label">Nomor BPJS <span class="text-danger">*</span></label>
            <div class="col-auto">
              <input type="text" class="form-control" maxlength="30" name="bpjs_kes" id="bpjs_kes" required value="<?= $karyawan->bpjs_kes; ?>">
              <p class="text-muted">BPJS Kesehatan</p>
            </div>
            <div class="col-auto"><p class="form-control-plaintext">/</p></div>
            <div class="col-auto">
              <input type="text" class="form-control" maxlength="30" name="bpjs_tk" id="bpjs_tk" required value="<?= $karyawan->bpjs_tk; ?>">
              <p class="text-muted">BPJS Ketenagakerjaan</p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="gaji_pokok" class="col-sm-2 col-form-label">Gaji <span class="text-danger">*</span></label>
            <div class="col-auto">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control number text-end" required value="<?= $karyawan->gaji_pokok; ?>">
              </div>
              <p class="text-muted text-end">Gaji Pokok</p>
            </div>
            <div class="col-auto"><p class="form-control-plaintext">/</p></div>
            <div class="col-auto">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" name="gaji_jabatan_tetap" id="gaji_jabatan_tetap" class="form-control number text-end" required value="<?= $karyawan->gaji_jabatan_tetap; ?>">
              </div>
              <p class="text-muted text-end">Gaji Jabatan Tetap</p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="ptkp" class="col-sm-2 col-form-label">PTKP <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <input type="text" class="form-control" maxlength="8" name="ptkp" id="ptkp" required value="<?= $karyawan->ptkp; ?>">
            </div>
          </div>
          <input type="hidden" name="kode" value="<?= $karyawan->kode_pt_karyawan; ?>">
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('master/karyawanpt'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>