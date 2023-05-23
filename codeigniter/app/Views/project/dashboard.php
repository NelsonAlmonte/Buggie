<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <h2><?=$project['name']?></h2>
      <div class="status-badge d-inline-block text-capitalize" style="<?=esc($project['statusStyle'])?>">
        <?=esc($project['status'])?>
      </div>
      <div class="d-inline-block mx-2">|</div>
      <?php if($project['end_date'] != '0000-00-00'): ?>
      <?php $endDate = new DateTime($project['end_date']);?>
      <?php $startDate = new DateTime($project['start_date']);?>
      <span><?=$endDate->diff($startDate)->format("Ends in %m months and %d days")?></span>
      <?php endif; ?>
      <div class="d-inline-block mx-2">|</div>
      <div class="d-inline-block">
        Owner: <span class="text-white"><?=esc($project['owner'])?></span>
      </div>
    </div>
    <div>
      <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false"><i
          class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu p-2">
        <?php if(in_array('project', session()->get('auth')['permissions'])): ?>
        <li>
          <a class="dropdown-item text-white"
            href="<?=site_url('manage/project/' . $project['slug'] .'/edit/'. $project['id'])?>">
            <div class="d-inline-block">
              <i class="bi bi-pencil"></i>
            </div>
            <span>Edit</span>
          </a>
        </li>
        <?php endif; ?>
        <li>
          <a class="dropdown-item text-white" href="<?=site_url('issue/' . $project['slug'])?>">
            <div class="d-inline-block">
              <i class="bi bi-bug"></i>
            </div>
            <span>Issues</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item text-white" href="#">
            <div class="d-inline-block">
              <i class="bi bi-file-earmark-bar-graph"></i>
            </div>
            <span>Reports</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item text-white" href="#">
            <div class="d-inline-block">
              <i class="bi bi-file-earmark-text"></i>
            </div>
            <span>Files</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item text-white" href="<?=site_url('collaborator/' . $project['slug'])?>">
            <div class="d-inline-block">
              <i class="bi bi-people"></i>
            </div>
            <span>Collaborators</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-5">
      <a class="text-decoration-none" href="<?=site_url('issue/' . $project['slug'])?>">
        <div class="card bg-complementary border border-0 rounded-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-start align-items-center">
              <div class="info-card-icon d-flex justify-content-center align-items-center rounded-4 me-4 p-4"
                style="background-color: #0d6efd1a; border: 1px solid #0d6efd;">
                <i class="bi bi-bug fs-2" style="color: #0d6efd;"></i>
              </div>
              <div>
                <h4 class="mb-1"><?=count($openIssues)?></h4>
                <h5 class="text-white-50">Open issues</h5>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-5">
      <a class="text-decoration-none" href="<?=site_url('issue/' . $project['slug'] . '?status=closed')?>">
        <div class="card bg-complementary border border-0 rounded-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-start align-items-center">
              <div
                class="info-card-icon d-flex justify-content-center align-items-center bg-dominant rounded-4 me-4 p-4"
                style="background-color: #1987541a; border: 1px solid #198754;">
                <i class="bi bi-bug-fill fs-2" style="color: #198754;"></i>
              </div>
              <div>
                <h4 class="mb-1"><?=count($closedIssues)?></h4>
                <h5 class="text-white-50">Closed issues</h5>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-5">
      <a class="text-decoration-none" href="<?=site_url('collaborator/' . $project['slug'])?>">
        <div class="card bg-complementary border border-0 rounded-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-start align-items-center">
              <div
                class="info-card-icon d-flex justify-content-center align-items-center bg-dominant rounded-4 me-4 p-4"
                style="background-color: #fd7e141a; border: 1px solid #fd7e14;">
                <i class="bi bi-people fs-2" style="color: #fd7e14;"></i>
              </div>
              <div>
                <h4 class="mb-1"><?=esc(count($collaborators))?></h4>
                <h5 class="text-white-50">Collaborators</h5>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-5">
      <a class="text-decoration-none" href="#">
        <div class="card bg-complementary border border-0 rounded-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-start align-items-center">
              <div
                class="info-card-icon d-flex justify-content-center align-items-center bg-dominant rounded-4 me-4 p-4"
                style="background-color: #6610f21a; border: 1px solid #6610f2;">
                <i class="bi bi-file-earmark-text fs-2" style="color: #6610f2;"></i>
              </div>
              <div>
                <h4 class="mb-1">128</h4>
                <h5 class="text-white-50">Files</h5>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
          <span class="fs-5 text-white">Latest issues</span>
        </div>
        <div class="issues-card card-body overflow-y-auto p-0">
          <?php if(!empty($issues)): ?>
            <?php foreach($issues as $key => $issue): ?>
            <div
              class="issue-item d-flex justify-content-between align-items-center py-2 px-4 <?=$key != 0 ? 'border-top' : ''?>">
              <div>
                <a class="text-accent text-decoration-none fw-bold d-block" href="#"><?=esc($issue['title'])?></a>
                <small>
                  <a class="text-accent text-decoration-none fw-bold" href="#"> #<?=esc($issue['id'])?></a>
                  opened on <a class="text-accent text-decoration-none fw-bold" href="#"><?=date_format(date_create($issue['start_date']), 'M j, Y')?></a>
                  by <a class="text-accent text-decoration-none fw-bold" href="#"><?=esc($issue['reporter_name'])?></a>
                  <?php if(!empty($issue['assignee'])): ?>
                  assigned to <a class="text-accent text-decoration-none fw-bold"
                    href="#"><?=esc($issue['assignee_name'])?></a>
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
          <?php else: ?>
          <div class="text-center m-5 p-4">
            <img class="card-empty-icon" src="/assets/img/empty.svg" alt="empty">
            <h5 class="mt-5">There is nothing here...</h5>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-header bg-complementary fs-5 text-white rounded-top-4 px-4 py-3">Issues graph</div>
        <div class="issues-card card-body p-4">
          <div class="text-center m-5">
            <img class="card-empty-icon" src="/assets/img/empty.svg" alt="empty">
            <h5 class="mt-5">There is nothing here...</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>