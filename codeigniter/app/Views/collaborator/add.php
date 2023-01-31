<div class="container-fluid">
  <h2>Add a new collaborator</h2>
  <div class="card mt-4">
    <div class="card-body bg-complementary p-4">
      <?php if(session()->getFlashdata('message') !== null): ?>
      <div class="alert alert-<?= session()->getFlashdata('color') ?> d-flex align-items-center my-4" role="alert">
        <i class="bi bi-check-circle flex-shrink-0 me-2"></i>
        <div>
          <?= session()->getFlashdata('message') ?>
        </div>
      </div>
      <?php endif; ?>
      <form class="row gx-5" action="<?=site_url('collaborator/save')?>" method="post">
        <?= csrf_field() ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name" required
              placeholder="Name" autocomplete="off">
            <label for="name">Name*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="last" name="last" required
              placeholder="Last" autocomplete="off">
            <label for="last">Last*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="email" class="form-control bg-dominant border-0" id="email" name="email" required
              placeholder="Email" autocomplete="off">
            <label for="email">Email*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="username" name="username" required
              placeholder="Username" autocomplete="off">
            <label for="username">Username*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="password" class="form-control bg-dominant border-0" id="password" name="password" required
              placeholder="Password" autocomplete="off">
            <label for="password">Password*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0" id="project" name="project" aria-label="project">
              <?php foreach($projects as $project): ?>
              <option class="text-capitalize" value="<?=esc($project['id'])?>">
                <?=esc($project['name'])?>
              </option>
              <?php endforeach; ?>
            </select>
            <label for="project">Project</label>
          </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="file" class="form-control bg-dominant border-0" id="image" name="image" required
              placeholder="Image" autocomplete="off">
            <label for="image">Profile picture*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Add collaborator</button>
        </div>
      </form>
    </div>
  </div>
</div>