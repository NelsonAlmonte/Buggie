<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Issues on <span class="text-primary"><?=esc($project['name'])?></span>
    </h2>
    <a class="btn btn-rounded btn-primary px-3" type="button" href="<?=site_url('issue/' . $slug . '/add')?>">
      Add issue
    </a>
  </div>
  <?php if(session()->getFlashdata('message') !== null): ?>
  <div class="alert alert-<?= session()->getFlashdata('color') ?> d-flex align-items-center my-4" role="alert">
    <i class="bi bi-check-circle flex-shrink-0 me-2"></i>
    <div>
      <?= session()->getFlashdata('message') ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="card bg-complementary border border-0 mt-4">
    <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
      <span class="fs-5 text-white">Latest issues</span>
    </div>
    <div class="card-body p-0">
      <?php if(!empty($issues)): ?>
        <?php foreach($issues as $key => $issue): ?>
        <div 
          class="issue-item d-flex justify-content-between align-items-center py-2 px-4 <?=$key != 0 ? 'border-top' : ''?>"
          x-data
          x-ref="issue"
        >
          <div>
            <a class="text-accent text-decoration-none fw-bold d-block" href="<?=site_url('issue/' . $slug . '/issue/' . $issue['id'])?>"><?=esc($issue['title'])?></a>
            <small>
              <a class="text-accent text-decoration-none fw-bold" href="<?=site_url('issue/' . $slug . '/issue/' . $issue['id'])?>"> #<?=esc($issue['id'])?></a>
              opened on <span class="text-accent text-decoration-none fw-bold"><?=date_format(date_create($issue['start_date']), 'M j, Y')?></span>
              by <a class="text-accent text-decoration-none fw-bold" href="<?=site_url('collaborator/view/' . $issue['reporter'])?>"><?=esc($issue['reporter_name'])?></a>
              <?php if(!empty($issue['assignee'])): ?>
              assigned to <a class="text-accent text-decoration-none fw-bold"
                href="<?=site_url('collaborator/view/' . $issue['assignee'])?>"><?=esc($issue['assignee_name'])?></a>
              <?php endif; ?>
            </small>
          </div>
          <div>
            <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
              style="color: #<?=esc($issue['classification_color'])?>; background-color: #<?=esc($issue['classification_color'])?>1a; border: 1px solid #<?=esc($issue['classification_color'])?>;">
              <?=esc($issue['classification_name'])?>
            </div>
            <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
              style="color: #<?=esc($issue['severity_color'])?>; background-color: #<?=esc($issue['severity_color'])?>1a; border: 1px solid #<?=esc($issue['severity_color'])?>;">
              <?=esc($issue['severity_name'])?>
            </div>
            <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
              style="color: #<?=esc($issue['status_color'])?>; background-color: #<?=esc($issue['status_color'])?>1a; border: 1px solid #<?=esc($issue['status_color'])?>;">
              <?=esc($issue['status_name'])?>
            </div>
            <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots"></i>
            </a>
            <ul class="dropdown-menu p-2">
              <li>
                <a class="dropdown-item text-white"
                  href="<?=site_url('issue/' . $slug . '/edit/' . $issue['id'])?>">
                  <div class="d-inline-block">
                    <i class="bi bi-pencil"></i>
                  </div>
                  <span>Edit</span>
                </a>
              </li>
              <?php if(empty($issue['assignee'])): ?>
              <li>
                <a class="dropdown-item text-white" href="#" role="button">
                  <div class="d-inline-block">
                    <i class="bi bi-person-plus"></i>
                  </div>
                  <span>Assign</span>
                </a>
              </li>
              <?php endif; ?>
              <li>
                <button 
                  class="dropdown-item text-white" 
                  x-data="deleteItem"
                  x-init='
                    item = <?=json_encode($issue)?>; 
                    url = "/issue/deleteIssue"; 
                    key = "issue"
                  '
                  @click="deleteItem($refs.issue)"
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
        <?php endforeach; ?>
      <?php else: ?>
      <div class="text-center m-5 p-4">
        <img class="card-empty-icon" src="/assets/img/empty.svg" alt="empty">
        <h5 class="mt-5">There is nothing here...</h5>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
