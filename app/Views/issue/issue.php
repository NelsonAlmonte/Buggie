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
  <div class="row my-4">
    <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4 h-100">
        <div class="card-body text-light">
          <?=$issue['description']?>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-body">
          <h6 class="text-white">Reporter</h6>
          <?php if($issue['reporter_is_active'] != '0'): ?>
            <a class="text-light text-decoration-none" href="<?=site_url('collaborator/view/') . $issue['reporter']?>"><?=esc($issue['reporter_name'])?></a>
          <?php else: ?>
            <span><?=esc($issue['reporter_name'])?></span>
          <?php endif; ?>
          <hr>
          <h6 class="text-white">Assignee</h6>
          <?php if(!empty($issue['assignee'])): ?>
            <?php if($issue['assignee_is_active'] != '0'): ?>
              <a class="text-light text-decoration-none" href="<?=site_url('collaborator/view/') . $issue['assignee']?>"><?=esc($issue['assignee_name'])?></a>
            <?php else: ?>
              <span><?=esc($issue['assignee_name'])?></span>
            <?php endif; ?>
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
          <?php if($issue['end_date'] != null): ?>
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
  <div class="card bg-complementary border border-0 rounded-4">
    <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
      <span class="fs-5 text-white">Files</span>
    </div>
    <div class="card-body">
    <div class="row mt-4">
      <?php if(!empty($files)): ?>
      <?php foreach($files as $file): ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="bg-dominant rounded-4 p-3">
          <div class="d-flex flex-column justify-content-between h-100">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex justify-content-start align-items-center">
                <i class="bi bi-filetype-<?=esc($file['type'])?> me-2 text-white"></i>
                <div class="d-flex align-items-center text-white fw-medium">
                  <span class="d-inline-block text-truncate" title="<?=esc($file['name'])?>" style="max-width: 150px;"><?=esc($file['name'])?></span>
                  <span><?=esc($file['type'])?></span>
                </div>
              </div>
              <div>
                <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0px 4px;">
                  <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu p-2">
                  <li>
                    <a class="dropdown-item text-white" href="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>" download>
                      <div class="d-inline-block">
                        <i class="bi bi-download"></i>
                      </div>
                      <span>Download</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <?php if(in_array($file['type'], ACCEPTED_IMAGES_TYPES)): ?>
            <a href="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>" class="glightbox">
              <img class="w-100 object-fit-cover rounded-2 mt-3" src="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>"
                alt="<?=esc($file['name'])?>" style="height: 180px;">
            </a>
            <?php else: ?>
            <a href="<?=PATH_TO_VIEW_ISSUES_FILES . $file['name']?>" class="glightbox">
              <div class="d-flex justify-content-center align-items-center bg-complementary rounded-2 mt-3" style="height: 180px;">
                <i class="bi bi-filetype-<?=esc($file['type'])?> fs-1 text-white"></i>
              </div>
            </a>
            <?php endif; ?>
            <div class="d-flex align-items-center mt-3" style="font-size: 14px;">
              <?php if($file['is_active'] != '0'): ?>
                <a class="text-decoration-none" href="<?=site_url('collaborator/view/' . $file['collaborator'])?>" target="_blank" title="Added by <?=esc($file['collaborator_name'] . ' ' . $file['last'])?>">
                  <img class="collaborator-item-image me-2" src="<?=PATH_TO_VIEW_PROFILE_IMAGE . $file['image']?>" alt="collaborator image" style="width: 24px; height: 24px;">
                </a>
              <?php else: ?>
                <img class="collaborator-item-image me-2" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . DEFAULT_PROFILE_IMAGE?>" alt="collaborator image" style="width: 24px; height: 24px;">
              <?php endif; ?>
              <span class="text-white" title="<?=esc($issue['title'])?>">On this issue</span>
              <span class="text-white mx-2">•</span>
              <span class="text-white" title="Added on <?=esc($file['created_at'])?>">
                <?=date_format(date_create($file['created_at']), 'd M Y')?>
              </span>
            </div>
          </div>
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