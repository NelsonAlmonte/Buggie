<div class="container-fluid">
  <h2>Files <span class="text-primary">on <?=esc($project['name'])?></span></h2>
  <div class="row mt-4">
    <?php if(!empty($files)): ?>
    <?php foreach($files as $file): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" x-data x-ref="file">
      <div class="bg-complementary rounded-4 p-3">
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
                <li>
                  <a class="dropdown-item text-white" href="<?=site_url('issue/' . $project['slug'] . '/issue/' . $file['issue_id'])?>" target="_blank">
                    <div class="d-inline-block">
                      <i class="bi bi-bug"></i>
                    </div>
                    <span>Issue</span>
                  </a>
                </li>
                <li>
                  <button 
                    class="dropdown-item text-white" 
                    x-data="deleteItem" 
                    x-init='
                      item = <?=json_encode($file)?>; 
                      url = "/issue/deleteIssueFile"; 
                      key = "file"
                    ' 
                    @click="deleteItem($refs.file)"
                  >
                    <div class="d-inline-block">
                      <i class="bi bi-trash"></i>
                    </div>
                    <span>Delete</span>
                  </button>
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
            <div class="d-flex justify-content-center align-items-center rounded-2 bg-dominant mt-3" style="height: 180px;">
              <i class="bi bi-filetype-<?=esc($file['type'])?> fs-1 text-white"></i>
            </div>
          </a>
          <?php endif; ?>
          <div class="d-flex align-items-center mt-3" style="font-size: 14px;">
            <a class="text-decoration-none" href="<?=site_url('collaborator/view/' . $file['collaborator'])?>" target="_blank" title="Added by <?=esc($file['collaborator_name'] . ' ' . $file['last'])?>">
              <img class="collaborator-item-image me-2" src="<?=PATH_TO_VIEW_PROFILE_IMAGE . $file['image']?>" alt="collaborator image" style="width: 24px; height: 24px;">
            </a>
            <a class="text-accent text-decoration-none text-truncate"
            href="<?=site_url('issue/' . $project['slug'] . '/issue/' . $file['issue_id'])?>"
            style="max-width: 150px;" target="_blank" title="<?=esc($file['title'])?>"><?=esc($file['title'])?></a>
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
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
