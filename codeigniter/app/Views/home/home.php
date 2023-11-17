<div class="container-fluid">
  <h2>Welcome <span class="text-primary"><?=session()->get('name') . ' ' . session()->get('last')?></span></h2>
  <h4 class="text-secondary mb-5">This is your work</h4>
  <div class="row">
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-start align-items-center">
            <div class="info-card-icon d-flex justify-content-center align-items-center rounded-4 me-4 p-4"
              style="background-color: #6f42c11a; border: 1px solid #6f42c1;">
              <i class="bi bi-briefcase fs-2" style="color: #6f42c1;"></i>
            </div>
            <div>
              <h4 class="mb-1"><?=esc(count($collaboratorProjects) + count($projectsWithoutAccess))?></h4>
              <h5 class="text-white-50">Assigned projects</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-start align-items-center">
            <div class="info-card-icon d-flex justify-content-center align-items-center rounded-4 me-4 p-4"
              style="background-color: #0d6efd1a; border: 1px solid #0d6efd;">
              <i class="bi bi-bug fs-2" style="color: #0d6efd;"></i>
            </div>
            <div>
              <h4 class="mb-1"><?=esc(count($collaboratorIssues))?></h4>
              <h5 class="text-white-50">Assigned issues</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-start align-items-center">
            <div class="info-card-icon d-flex justify-content-center align-items-center rounded-4 me-4 p-4"
              style="background-color: #1987541a; border: 1px solid #198754;">
              <i class="bi bi-bug-fill fs-2" style="color: #198754;"></i>
            </div>
            <div>
              <h4 class="mb-1"><?=esc(count($closedCollaboratorIssues))?></h4>
              <h5 class="text-white-50">Closed issues</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
      <div class="row">
        <div class="mb-4">
          <div class="card bg-complementary border border-0 rounded-4">
            <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
              <span class="fs-5 text-white">My issues</span>
            </div>
            <div class="issues-card card-body overflow-y-auto p-0">
              <?php if(!empty($collaboratorIssues)): ?>
              <?php foreach($collaboratorIssues as $key => $issue): ?>
              <div
                class="issue-item d-flex justify-content-between align-items-center py-2 px-4 <?=$key != 0 ? 'border-top' : ''?>">
                <div>
                  <a class="text-accent text-decoration-none fw-bold d-block"
                    href="<?=site_url('issue/' . $issue['project_slug'] . '/issue/' . $issue['id'])?>"><?=esc($issue['title'])?></a>
                  <small>
                    <a class="text-accent text-decoration-none fw-bold"
                      href="<?=site_url('issue/' . $issue['project_slug'] . '/issue/' . $issue['id'])?>">
                      #<?=esc($issue['id'])?></a>
                    opened on <a class="text-accent text-decoration-none fw-bold"
                      href="#"><?=date_format(date_create($issue['start_date']), 'M j, Y')?></a>
                    by <a class="text-accent text-decoration-none fw-bold"
                      href="<?=site_url('collaborator/view/') . $issue['reporter_id']?>"><?=esc($issue['reporter_name'])?></a>
                    on <a class="text-accent text-decoration-none fw-bold"
                      href="<?=site_url('project/' . $issue['project_slug'] . '/dashboard/')?>"><?=esc($issue['project_name'])?></a>
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
                <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
                <h5 class="mt-5">There is nothing here...</h5>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
  
        <div class="mb-4">
          <div class="card bg-complementary border border-0 rounded-4">
            <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
              <span class="fs-5 text-white">My overdue issues</span>
            </div>
            <div class="issues-card card-body overflow-y-auto p-0">
              <?php if(!empty($collaboratorOpenIssues)): ?>
              <?php foreach($collaboratorOpenIssues as $key => $issue): ?>
              <?php if($issue['overdueDays'] != '0'): ?>
              <div
                class="issue-item d-flex justify-content-between align-items-center py-2 px-4 <?=$key != 0 ? 'border-top' : ''?>">
                <div>
                  <a class="text-accent text-decoration-none fw-bold d-block"
                    href="<?=site_url('issue/' . $issue['project_slug'] . '/issue/' . $issue['id'])?>"><?=esc($issue['title'])?></a>
                  <small>
                    <a class="text-accent text-decoration-none fw-bold"
                      href="<?=site_url('issue/' . $issue['project_slug'] . '/issue/' . $issue['id'])?>">
                      #<?=esc($issue['id'])?></a>
                    opened on <a class="text-accent text-decoration-none fw-bold"
                      href="#"><?=date_format(date_create($issue['start_date']), 'M j, Y')?></a>
                    by <a class="text-accent text-decoration-none fw-bold"
                      href="<?=site_url('collaborator/view/') . $issue['reporter_id']?>"><?=esc($issue['reporter_name'])?></a>
                    on <a class="text-accent text-decoration-none fw-bold"
                      href="<?=site_url('project/' . $issue['project_slug'] . '/dashboard/')?>"><?=esc($issue['project_name'])?></a>
                  </small><br>
                  <small class="fw-bold text-danger">Late by <?=$issue['overdueDays']?> days</small>
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
              <?php endif; ?>
              <?php endforeach; ?>
              <?php else: ?>
              <div class="text-center m-5 p-4">
                <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
                <h5 class="mt-5">There is nothing here...</h5>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
      <div class="card bg-complementary border border-0 rounded-4 mb-4">
        <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
          <span class="fs-5 text-white">Your projects</span>
        </div>
        <div class="card-body text-center p-4">
          <?php foreach($collaboratorProjects as $project): ?>
          <a class="project-item d-inline-block bg-primary border-0 rounded-5 text-white fw-bold text-decoration-none px-3 py-2 m-1"
            href="<?=site_url('project/' . $project['slug'] . '/dashboard')?>">
            <?=esc($project['name'])?>
          </a>
          <?php endforeach; ?>
          <?php if(count($projectsWithoutAccess) > 0): ?>
          <span
            class="project-item d-inline-block bg-secondary border-0 rounded-5 text-white fw-bold text-decoration-none px-3 py-2 m-1"
            title="You don't have access to these projects">
            <?=esc(count($projectsWithoutAccess))?> more
          </span>
          <?php endif; ?>
        </div>
      </div>

      <div 
        class="card bg-complementary border border-0 rounded-4"
        x-data="reportChart"
        x-init='
          initChart($refs.chart);
          project = <?=json_encode($collaboratorProjects[0]['id'])?>;
          selectedProject = <?=json_encode($collaboratorProjects[0]['name'])?>;
        '
      >
        <div class="card-header d-flex justify-content-between align-items-center bg-complementary fs-5 text-white rounded-top-4 px-4 py-3">
          <div class="d-flex justify-content-start align-items-center">
            <?php if(count($collaboratorProjects) > 1): ?>
              <span>Report on</span>
              <div class="dropdown ms-2">
                <button 
                  class="btn btn-primary rounded-3 dropdown-toggle" 
                  type="button" 
                  data-bs-toggle="dropdown" 
                  aria-expanded="false"
                  x-text="selectedProject"
                >
                </button>
                <ul class="dropdown-menu p-2">
                  <?php foreach($collaboratorProjects as $project): ?>
                    <li>
                      <a 
                        class="dropdown-item text-white" 
                        role="button"
                        @click='
                          project = <?=json_encode($project['id'])?>;
                          selectedProject = <?=json_encode($project['name'])?>;
                          getChart($refs.chart);
                        '
                      ><?=$project['name']?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php else: ?>
              <h2>Report on <span class="text-primary"><?=$collaboratorProjects[0]['name']?></span></h2>
            <?php endif; ?>
          </div>
          <div class="dropdown">
            <button 
              class="btn btn-primary rounded-3 dropdown-toggle text-capitalize" 
              type="button" 
              data-bs-toggle="dropdown" 
              aria-expanded="false"
            >
              <span class="text-capitalize" x-text="selectedType"></span>
            </button>
            <ul class="dropdown-menu p-2">
              <template x-for="type in types">
                <li>
                  <a 
                    class="dropdown-item text-white" 
                    role="button"
                    @click='
                      selectedType = type
                      getChart($refs.chart)
                    '
                  >
                    <span class="text-capitalize" x-text="type"></span>
                  </a>
                </li>
              </template>
            </ul>
          </div>
        </div>
        <div class="issues-card card-body p-4">
          <div class="d-flex justify-content-center" x-ref="chartContainer" style="height:300px;">
            <canvas x-ref="chart"></canvas>
          </div>
          <div class="text-center m-5 d-none" x-ref="empty">
            <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
            <h5 class="mt-5">There is nothing here...</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
