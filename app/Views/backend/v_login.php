<style type="text/css">
html, body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
  text-align: center;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 400;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
    
<main class="form-signin">
  <form method="post" action="<?= base_url('login'); ?>">
    <div class="text-center">
      <h2>KEUANGAN</h2>
    </div>
    <div class="form-floating">
      <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username" autofocus required>
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <p class="text-danger"><?php if(isset($notif)) echo $notif; ?>&nbsp;</p>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit" name="btn_signin" value="signin">Sign in</button>
    <div class="mt-2">
      <div class='float-end'><a href='#' data-bs-toggle='modal' data-bs-target='#lupaPasswordLoginModal' title="Lupa Password">Lupa Password</a></div>
    </div>
  </form>
</main>
    