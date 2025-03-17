<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">User</li>
          <li class="breadcrumb-item"><a href="<?= site_url('user/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </nav>
      <h1 class="h2">Tambah User</h1>        
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
            <label for="profil" class="col-sm-2 col-form-label">Profil <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" required name="profil" id="profil" autofocus>
                <option value=''>-- Pilih Profil --</option>
                <?php  
                foreach($profils as $profil) {
                  $selected = (set_value('profil')==$profil->kode_profil) ? "selected" : "";
                  echo "<option value='".$profil->kode_profil."' $selected>".$profil->nama."</option>";
                }
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="pt" class="col-sm-2 col-form-label">PT</label>
            <div class="col-sm-3">
              <select class="form-select select2" name="pt" id="pt" >
                <option value=''>-- Tanpa PT --</option>
                <?php  
                foreach($pts as $pt) {
                  $selected = (set_value('pt')==$pt->kode_pt) ? "selected" : "";
                  echo "<option value='".$pt->kode_pt."' $selected>".$pt->nama."</option>";
                }
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="nama" class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="nama" name="nama" maxlength="45" required value="<?= set_value('nama'); ?>">
              <span class="text-muted">Maks. 45 karakter</span>
            </div>
          </div>
          <div class="row mb-3">
            <label for="username" class="col-sm-2 col-form-label">Username <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="username" name="username" maxlength="45" required value="<?= set_value('username'); ?>">
              <span class="text-muted">Maks. 45 karakter</span>
            </div>
          </div>
          <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">Password <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <input type="password" class="form-control" id="password" name="password" required value="<?= set_value('password'); ?>" placeholder="Min. 6 karakter">
            </div>
          </div>
          <div class="row mb-3">
            <label for="password2" class="col-sm-2 col-form-label">Ulangi Password <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <input type="password" class="form-control" id="password2" name="password2" required value="<?= set_value('password2'); ?>" placeholder="Min. 6 karakter">
            </div>
          </div>
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('user/daftar'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>