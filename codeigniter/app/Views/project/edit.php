<div class="container-fluid">
  <h2>Edit <?=esc($project['name'])?></h2>
  <div class="card border border-0 rounded-4 mt-4">
    <div class="card-body rounded-4 bg-complementary p-4">
      <form class="row gx-5"
        action="<?=site_url('manage/project/' . esc($project['slug']) . '/update/' . esc($project['id']))?>" method="post">
        <?= csrf_field() ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name"
              placeholder="The name of the project" value="<?=esc($project['name'])?>" required autocomplete="off">
            <label for="name">Project name*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="owner" name="owner"
              placeholder="Project owner/client" value="<?=esc($project['owner'])?>" required autocomplete="off">
            <label for="name">Owner*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0" id="status" name="status" aria-label="Project status">
              <?php foreach($projectStatus as $status): ?>
              <option class="text-capitalize" value="<?=esc($status['id'])?>"
                <?=esc($status['name']) == esc($project['status']) ? 'selected' : '' ;?>>
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
              value="<?=esc($project['start_date'])?>" required>
            <label for="start_date">Start date*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="end_date" name="end_date"
              value="<?=esc($project['end_date'])?>">
            <label for="end_date">End date</label>
          </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-4">
          <div class="form-floating">
            <textarea class="form-control bg-dominant border-0" placeholder="Enter a project description here"
              name="description" id="description" required
              style="height: 100px;"><?=esc($project['description'])?></textarea>
            <label for="description">Description*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Update project</button>
        </div>
      </form>
    </div>
  </div>
</div>