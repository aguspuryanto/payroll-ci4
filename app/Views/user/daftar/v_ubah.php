<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">User</li>
          <li class="breadcrumb-item"><a href="<?= site_url('user/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
      </nav>
      <h1 class="h2">Ubah User</h1>        
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
        <form method="post" action="?username=<?= $user->username; ?>">

          <div class="row mb-3">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $user->username; ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="profil" class="col-sm-2 col-form-label">Profil <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" required name="profil" id="profil" autofocus>
                <option value=''>-- Pilih Profil --</option>
                <?php  
                foreach($profils as $profil) {
                  $selected = ($user->kode_profil==$profil->kode_profil) ? "selected" : "";
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
                  $selected = ($user->kode_pt==$pt->kode_pt) ? "selected" : "";
                  echo "<option value='".$pt->kode_pt."' $selected>".$pt->nama."</option>";
                }
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="nama" class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="nama" name="nama" maxlength="45" required value="<?= $user->nama; ?>">
              <span class="text-muted">Maks. 45 karakter</span>
            </div>
          </div>
          <input type="hidden" name="username" value="<?= $user->username; ?>">
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