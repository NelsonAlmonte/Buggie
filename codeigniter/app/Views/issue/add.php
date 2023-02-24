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
      <form class="row gx-5" action="<?=site_url('issue/'. $project['slug'] . '/save')?>" method="post"
        enctype="multipart/form-data">
        <input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <input type="hidden" id="project" name="project" value="<?=esc($project['id'])?>">
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
            <textarea class="form-control bg-dominant border-0" placeholder="Description" id="description"
              name="description" style="height: 200px"></textarea>
            <label for="description">Description*</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-file-earmark-text text-primary me-3"></i>Files</h4>
        </div>
        <div class="col-12 mb-4">
          <div x-data="filesPreview">
            <button type="button" class="btn btn-rounded btn-dark bg-dominant p-3" @click="$refs.files.click()">
              <i class="bi bi-image me-2"></i>
              <span>Add files*</span>
            </button>
            <div class="row mt-4">
              <template x-for="(file, index) in filesPreview">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                  <div class="bg-dominant rounded-4 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex justify-content-start align-items-center text-white">
                        <div class="bg-primary rounded-3 p-2 me-2">
                          <small class="text-uppercase" x-text="file.type"></small>
                        </div>
                        <span x-text="file.name"></span>
                      </div>
                      <div class="d-flex justify-content-start align-items-center">
                        <button class="btn btn-rounded btn-primary me-2" type="button">
                          <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-rounded btn-danger" type="button"
                          @click="removeFile(index, $refs.files, file)">
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
            <input class="d-none" type="file" name="files" id="files" x-ref="files" @change="renderFiles($event)"
              multiple>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-people text-primary me-3"></i>Collaborators</h4>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="reporter" name="reporter" required
              placeholder="Reporter" autocomplete="off" value="1">
            <label for="reporter">Reporter*</label>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="assignee" name="assignee"
              placeholder="Assign to" autocomplete="off">
            <label for="assign to">Assign to</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-info-circle text-primary me-3"></i>Status</h4>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="classification" name="classification"
              aria-label="classification">
              <?php foreach($status['classification'] as $classification): ?>
              <option value="<?=esc($classification['id'])?>"><?=esc($classification['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="classification">Classification</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="severity" name="severity"
              aria-label="severity">
              <?php foreach($status['severity'] as $classification): ?>
              <option value="<?=esc($classification['id'])?>"><?=esc($classification['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="severity">Severity</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="status" name="status"
              aria-label="status">
              <?php foreach($status['issue_status'] as $classification): ?>
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
            <input type="date" class="form-control bg-dominant border-0" id="end_date" name="end_date">
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
