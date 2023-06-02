<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <h2>
        <?=$collaborator['name']?>
        <?=$collaborator['last']?>
      </h2>
    </div>
    <?php if(in_array('collaborator', session()->get('auth')['permissions'])): ?>
    <div>
      <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false"><i
          class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu p-2">
        <li>
          <a class="dropdown-item text-white" href="<?=site_url('collaborator/edit/'. $collaborator['id'])?>">
            <div class="d-inline-block">
              <i class="bi bi-pencil"></i>
            </div>
            <span>Edit</span>
          </a>
        </li>
      </ul>
    </div>
    <?php endif; ?>
  </div>
  <div class="row">
    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
          <div class="card bg-complementary border border-0 rounded-4">
            <div class="card-body text-center p-5">
              <?php if($collaborator['image'] != DEFAULT_PROFILE_IMAGE): ?>
              <img class="collaborator-image" src="<?=PATH_TO_VIEW_PROFILE_IMAGE . $collaborator['image']?>"
                alt="<?=$collaborator['image']?>">
              <?php else: ?>
              <img class="collaborator-image" src="/assets/img/<?=DEFAULT_PROFILE_IMAGE?>"
                alt="<?=$collaborator['image']?>">
              <?php endif; ?>
              <h4 class="card-title text-white mt-3 mb-0">
                <?=esc($collaborator['name'])?>
                <?=esc($collaborator['last'])?>
              </h4>
              <p class="mb-0"><?=esc($collaborator['username'])?></p>
              <p class="mb-0"><?=esc($collaborator['email'])?></p>
            </div>
          </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
          <div class="card bg-complementary border border-0 rounded-4">
            <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
              <span class="fs-5 text-white"><?=esc($collaborator['name'])?> <?=esc($collaborator['last'])?>
                projects</span>
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
        </div>
      </div>
    </div>
    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12">
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
      </div>
      <div>
        <div class="card bg-complementary border border-0 rounded-4">
          <div class="card-header bg-complementary rounded-top-4 px-4 py-3">
            <span class="fs-5 text-white">Latest issues</span>
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
                  <?php if(!empty($issue['assignee_id'])): ?>
                  assigned to <a class="text-accent text-decoration-none fw-bold"
                    href="<?=site_url('collaborator/view/') . $issue['assignee_id']?>"><?=esc($issue['assignee_name'])?></a>
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
    </div>
  </div>
</div>