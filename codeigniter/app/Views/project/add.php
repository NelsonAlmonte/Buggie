<div class="container-fluid">
  <h2>Add a new project for your organization</h2>
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
      <form class="row gx-5" action="<?=site_url('project/save')?>" method="post">
        <?= csrf_field() ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name"
              placeholder="The name of the project" required autocomplete="off">
            <label for="name">Project name*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="owner" name="owner"
              placeholder="Project owner/client" required autocomplete="off">
            <label for="owner">Owner*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0" id="status" name="status" aria-label="Project status">
              <?php foreach($projectStatus as $status): ?>
              <option class="text-capitalize" value="<?=esc($status['id'])?>">
                <?=esc($status['name'])?>
              </option>
              <?php endforeach; ?>
            </select>
            <label for="status">Status</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="start_date" name="start_date"
              value="<?=date('Y-m-d')?>" required>
            <label for="start_date">Start date*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="end_date" name="end_date">
            <label for="end_date">End date</label>
          </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-4">
          <div class="form-floating">
            <textarea class="form-control bg-dominant border-0" placeholder="Enter a project description here"
              name="description" id="description" required style="height: 100px;"></textarea>
            <label for="description">Description*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Save project</button>
        </div>
      </form>
    </div>
  </div>
</div>