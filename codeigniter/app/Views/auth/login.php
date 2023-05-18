
<div class="card mb-3">

<div class="card-body">

  <div class="pt-4 pb-2">
    <h5 class="card-title text-center pb-0 fs-4">Inicio de sesión</h5>
    <p class="text-center small">Ingresa con tu usuario y contraseña</p>
    <p class="text-center text-danger"><?= session()->getFlashdata('message') ?></p>
  </div>
  <form class="row g-3 pb-4" action="<?=site_url('auth/authenticate')?>" method="post">
    <?= csrf_field() ?>
    <div class="col-12">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="username" name="username" placeholder="Usuario"
          autocomplete="off">
        <label for="username">Usuario</label>
      </div>
    </div>

    <div class="col-12">

      <div class="form-floating">
        <input type="password" class="form-control" id="password" name="password"
          placeholder="Password">
        <label for="password">Contraseña</label>
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
    </div>
  </form>

</div>
</div>