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
      <?php foreach($issues as $key => $issue): ?>
      <div class="issue-item d-flex justify-content-between align-items-center py-2 px-4 <?=$key != 0 ? 'border-top' : ''?>">
        <div>
          <a class="text-accent text-decoration-none fw-bold d-block"
            href="#"><?=esc($issue['title'])?></a>
          <small>
            <a class="text-accent text-decoration-none fw-bold" href="#"> #<?=esc($issue['id'])?></a>
            opened on <a class="text-accent text-decoration-none fw-bold" href="#">Jan 24, 2023</a>
            by <a class="text-accent text-decoration-none fw-bold" href="#"><?=esc($issue['reporter_name'])?></a>
            <?php if(!empty($issue['assignee'])): ?>
            assigned to <a class="text-accent text-decoration-none fw-bold" href="#"><?=esc($issue['assignee_name'])?></a>
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
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>