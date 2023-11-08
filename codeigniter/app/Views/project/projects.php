<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Projects you are working on</h2>
    <?php if(in_array('project', session()->get('auth')['permissions'])): ?>
    <a class="btn btn-rounded btn-primary" href="<?=site_url('manage/project/add')?>">Add project</a>
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
  <div class="row mt-4">
    <?php foreach ($projects as $project): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4 h-100">
        <div class="card-body px-4 pt-3 pb-4">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="status-badge text-capitalize" style="<?=esc($project['statusStyle'])?>">
              <?=esc($project['status'])?></div>
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
                  <a class="dropdown-item text-white" href="<?=site_url('project/' . $project['slug'] .'/dashboard')?>">
                    <div class="d-inline-block">
                      <i class="bi bi-columns-gap"></i>
                    </div>
                    <span>Dashboard</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item text-white" href="<?=site_url('issue/' . $project['slug'])?>">
                    <div class="d-inline-block">
                      <i class="bi bi-bug"></i>
                    </div>
                    <span>Issues</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item text-white" href="<?=site_url('report/' . $project['slug'])?>">
                    <div class="d-inline-block">
                      <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <span>Reports</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item text-white" href="<?=site_url('file/' . $project['slug'])?>">
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
                <li>
                  <a class="dropdown-item text-white" href="<?=site_url('calendar/' . $project['slug'])?>">
                    <div class="d-inline-block">
                      <i class="bi bi-calendar3"></i>
                    </div>
                    <span>Calendar</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="d-flex flex-column h-100">
            <div class="mb-auto">
              <a class="text-decoration-none" href="<?=site_url('project/' . $project['slug'] .'/dashboard')?>">
                <h4 class="card-title text-white mb-3"><?=esc(character_limiter($project['name'], 50, '...'))?></h4>
              </a>
              <?php if($project['end_date'] != '0000-00-00'): ?>
              <?php $endDate = new DateTime($project['end_date']);?>
              <?php $startDate = new DateTime($project['start_date']);?>
              <p><?=$endDate->diff($startDate)->format("Ends in %m months and %d days")?></p>
              <?php endif; ?>
            </div>
            <?= view_cell('App\Cells\Project\IssuesCount\IssuesCount::render', ['project' => $project]); ?>
          </div>
        </div>
        <a class="text-decoration-none text-white mt-5"
          href="<?=site_url('project/' . $project['slug'] .'/dashboard')?>">
          <div
            class="card-footer d-flex justify-content-center bg-complementary rounded-bottom-4 py-3 project-card-footer">
            Dashboard
          </div>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>