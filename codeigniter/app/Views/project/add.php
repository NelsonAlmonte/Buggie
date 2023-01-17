<div class="container-fluid">
  <h2>Add a new project for your organization</h2>
  <div class="card">
    <div class="card-body bg-complementary p-4">
      <h4 class="alert-heading text-secondary"><?= session()->getFlashdata('message') ?></h4>
      <form class="row gx-5" action="<?=site_url('project/save')?>" method="post">
        <?= csrf_field() ?>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name" placeholder="The name of the project" required
              autocomplete="off">
            <label for="name">Project name</label>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="owner" name="owner" placeholder="Project owner/client" required
              autocomplete="off">
            <label for="name">Owner</label>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="deadline" name="deadline" placeholder="Project deadline"
              autocomplete="off" required autocomplete="off">
            <label for="deadline">Deadline</label>
          </div>
        </div>
        <div class="col-6 mb-4">
          <label class="mb-1">Project status</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input bg-dominant border-0" type="radio" name="status" id="active" checked>
            <label class="form-check-label" for="active">
              Active
            </label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input bg-dominant border-0" type="radio" name="status" id="on_hold">
            <label class="form-check-label" for="on_hold">
              On hold
            </label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input bg-dominant border-0" type="radio" name="status" id="cancelled">
            <label class="form-check-label" for="cancelled">
              Cancelled
            </label>
          </div>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Save project</button>
        </div>
      </form>
    </div>
  </div>
</div>