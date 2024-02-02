<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buggie</title>
  <link rel="stylesheet" href="/assets/third-party/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/assets/css/template.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>

  </style>
</head>

<body class="login-background">
  <header id="header" class="header fixed-top d-flex align-items-center justify-content-between bg-transparent shadow-none">
    <div class="d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <a href="#" class="logo d-flex align-items-center">
          <i class="bi bi-bug me-2"></i>
          <span>Buggie</span>
        </a>
      </div>
    </div>
  </header>
  <main class="main">
    <div class="d-flex align-items-center justify-content-start vh-100">
      <div class="mx-4 vw-50">
        <div class="py-4">
          <p class="mb-3 fw-bold text-uppercase text-white" style="letter-spacing: 1px;">Your one simple <span class="text-primary">bugtracker</span></p>
          <h1 class="mb-4 fw-bold">Welcome to <span class="text-primary">Buggie</span></h1>
          <p class="mb-4 fw-bold text-white">Use the credentials assigned by your project manager</p>
        </div>
        <form class="row g-3 pb-4" action="<?= site_url('auth/authenticate') ?>" method="post">
          <?= csrf_field() ?>
          <div class="col-12 mb-4">
            <div class="form-floating mb-2">
              <input type="text" class="form-control bg-dominant border-0" id="username" name="username" placeholder="Username" autocomplete="off" autofocus>
              <label for="username">Username</label>
            </div>
          </div>
          <div class="col-12 mb-5">
            <div class="form-floating">
              <input type="password" class="form-control bg-dominant border-0" id="password" name="password" placeholder="Password">
              <label for="password">Password</label>
            </div>
          </div>
          <div class="col-12">
            <button class="btn btn-rounded btn-primary p-3 w-100" type="submit">Log in</button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?= view_cell('App\Cells\Shared\Toast\Toast::render'); ?>
  <script type="text/javascript" src="/assets/third-party/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="/assets/third-party/sweetalert2/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="/assets/js/components.js"></script>
</body>

</html>