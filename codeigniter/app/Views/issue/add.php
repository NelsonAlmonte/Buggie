<div class="container-fluid">
  <h2>Add a new issue</h2>
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
      <form class="row gx-5" action="<?=site_url('issue/save')?>" method="post" enctype="multipart/form-data">
        <input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <div class="col-12 mb-4">
          <h4><i class="bi bi-person-vcard text-primary me-3"></i>Information</h4>
        </div>
        <div class="col-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="title" name="title" required
              placeholder="Title" autocomplete="off">
            <label for="title">Title*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <div class="form-floating">
            <textarea class="form-control bg-dominant border-0" placeholder="Description" id="description" name="description" style="height: 200px"></textarea>
            <label for="description">Description*</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-file-earmark-text text-primary me-3"></i>Documents</h4>
        </div>
        <div class="col-12 mb-4" x-data>
          <div x-data="filesPreview">
            <button type="button" class="btn btn-rounded btn-dark bg-dominant p-3" @click="$refs.files.click()">
              <i class="bi bi-image me-2"></i>
              <span>Add documents*</span>
            </button>
            <div class="row">
              <template x-for="document in documents">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                  <img class="img-fluid rounded-4 mt-4" :src="document.result" :alt="document.name">
                  <span x-text="document.name"></span>
                </div>
              </template>
            </div>
            <input class="d-none" type="file" name="files" id="files" x-ref="files" @change="renderFiles($event)" multiple>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-people text-primary me-3"></i>Collaborators</h4>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="reporter" name="reporter" required
              placeholder="Reporter" autocomplete="off" disabled value="1">
            <label for="reporter">Reporter*</label>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="assignee" name="assignee" required
              placeholder="Assign to" autocomplete="off">
            <label for="assign to">Assign to*</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-info-circle text-primary me-3"></i>Status</h4>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="classification" name="classification" aria-label="classification">
              <?php foreach($status['classification'] as $classification): ?>
              <option value="<?=esc($classification['id'])?>"><?=esc($classification['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="classification">Classification</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="severity" name="severity" aria-label="severity">
              <?php foreach($status['severity'] as $classification): ?>
              <option value="<?=esc($classification['id'])?>"><?=esc($classification['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="severity">Severity</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="status" name="status" aria-label="status">
              <?php foreach($status['project_status'] as $classification): ?>
              <option value="<?=esc($classification['id'])?>"><?=esc($classification['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="status">Status</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-calendar3 text-primary me-3"></i>Dates</h4>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="start_date" name="start_date"
              value="<?=date('Y-m-d')?>" required>
            <label for="start_date">Start date*</label>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="end_date" name="end_date" required>
            <label for="end_date">End date*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Add issue</button>
        </div>
      </form>
    </div>
  </div>
</div>
