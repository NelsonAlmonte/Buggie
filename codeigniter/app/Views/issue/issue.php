<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2><?=esc($issue['title'])?><span class="text-primary"> #<?=esc($issue['id'])?></span>
    </h2>
    <?php if (session()->get('id') == $issue['reporter'] || in_array('issue', session()->get('auth')['permissions'])): ?>
    <a class="btn btn-rounded btn-primary px-3" type="button"
      href="<?=site_url('issue/' . $slug . '/edit/' . $issue['id'])?>">
      Edit issue
    </a>
    <?php endif; ?>
  </div>
  <?php if(session()->getFlashdata('message') !== null): ?>
  <div class="alert alert-<?= session()->getFlashdata('color') ?> d-flex align-items-center my-4" role="alert">
    <i class="bi bi-check-circle flex-shrink-0 me-2"></i>
    <div>
      <?= session()->getFlashdata('message') ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="row my-4">
    <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">
      <div class="card bg-complementary border border-0 h-100">
        <div class="card-body text-light">
          <?=$issue['description']?>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12">
      <div class="card bg-complementary border border-0">
        <div class="card-body">
          <h6 class="text-white">Reporter</h6>
          <a class="text-light text-decoration-none" href="<?=site_url('collaborator/view/') . $issue['reporter']?>"><?=esc($issue['reporter_name'])?></a>
          <hr>
          <h6 class="text-white">Assignee</h6>
          <?php if(!empty($issue['assignee'])): ?>
            <a class="text-light text-decoration-none" href="<?=site_url('collaborator/view/') . $issue['assignee']?>"><?=esc($issue['assignee_name'])?></a>
          <?php else: ?>
            <span class="text-light">None</span>
          <?php endif; ?>
          <hr>
          <h6 class="text-white">Status</h6>
          <div class="status-badge d-inline-block text-capitalize me-2"
            style="color: #<?=esc($issue['classification_color'])?>; background-color: #<?=esc($issue['classification_color'])?>1a; border: 1px solid #<?=esc($issue['classification_color'])?>;">
            <?=esc($issue['classification_name'])?>
          </div>
          <div class="status-badge d-inline-block text-capitalize me-2"
            style="color: #<?=esc($issue['severity_color'])?>; background-color: #<?=esc($issue['severity_color'])?>1a; border: 1px solid #<?=esc($issue['severity_color'])?>;">
            <?=esc($issue['severity_name'])?>
          </div>
          <div class="status-badge d-inline-block text-capitalize"
            style="color: #<?=esc($issue['status_color'])?>; background-color: #<?=esc($issue['status_color'])?>1a; border: 1px solid #<?=esc($issue['status_color'])?>;">
            <?=esc($issue['status_name'])?>
          </div>
          <hr>
          <h6 class="text-white">Project</h6>
          <span class="text-light text-capitalize"><?=esc($projectName)?></span>
          <hr>
          <h6 class="text-white">Opened on</h6>
          <span class="text-light"><?=date_format(date_create($issue['start_date']), 'M j, Y')?></span>
          <hr>
          <h6 class="text-white">Due date</h6>
          <?php if($issue['end_date'] != '0000-00-00'): ?>
            <span class="text-light"><?=date_format(date_create($issue['end_date']), 'M j, Y')?></span>
          <?php else: ?>
            <span class="text-light">None</span>
          <?php endif; ?>
          <hr>
          <h6 class="text-white">Completion date</h6>
          <?php if(!empty($issue['completed_date'])): ?>
            <span class="text-light"><?=date_format(date_create($issue['completed_date']), 'M j, Y')?></span>
          <?php else: ?>
            <span class="text-light">None</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="card bg-complementary border border-0">
    <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
      <span class="fs-5 text-white">Files</span>
    </div>
    <div class="card-body">
      <div class="row">
        <?php if(!empty($files)): ?>
          <?php foreach($files as $file): ?>
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="bg-dominant rounded-3 p-3" style="height: 250px;">
              <div class="d-flex justify-content-start align-items-center">
                <i class="bi bi-filetype-<?=esc($file['type'])?> me-2 text-white"></i>
                <div class="d-flex align-items-center text-white">
                  <span class="d-inline-block text-truncate" style="max-width: 150px;"><?=esc($file['name'])?></span>
                  <span><?=esc($file['type'])?></span>
                </div>
              </div>
              <?php if(in_array($file['type'], ACCEPTED_IMAGES_TYPES)): ?>
              <a href="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>" class="glightbox">
                <img class="w-100 object-fit-cover rounded-2 mt-3" src="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>" alt="<?=esc($file['name'])?>" style="height: 180px;">
              </a>
              <?php else: ?>
              <a href="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>" class="glightbox">
                <div class="d-flex h-100 justify-content-center align-items-center">
                  <i class="bi bi-filetype-<?=esc($file['type'])?> fs-1 text-white"></i>
                </div>
              </a>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
        <div class="text-center p-5">
          <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
          <h5 class="mt-5">There is nothing here...</h5>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>