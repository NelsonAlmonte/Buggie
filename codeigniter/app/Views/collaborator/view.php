<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <h2>
        <?=$collaborator['name']?>
        <?=$collaborator['last']?>
      </h2>
    </div>
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
  </div>
  <div class="row">
    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
          <div class="card bg-complementary border border-0 rounded-4">
            <div class="card-body text-center p-5">
              <img class="collaborator-image" src="<?='/uploads/profile-image/' . $collaborator['image']?>"
                alt="<?=$collaborator['image']?>">
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
              <span class="fs-5 text-white"><?=esc($collaborator['name'])?> <?=esc($collaborator['name'])?> projects</span>
            </div>
            <div class="card-body text-center p-4">
              <?php foreach($collaboratorProjects as $project): ?>
                <a class="project-item d-inline-block bg-primary border-0 rounded-5 text-white fw-bold text-decoration-none px-3 py-2 m-1" href="<?=site_url('project/' . $project['slug'] . '/dashboard')?>">
                  <?=esc($project['name'])?>
                </a>
              <?php endforeach; ?>
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
                  <h4 class="mb-1"><?=esc(count($collaboratorProjects))?></h4>
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
                  <h4 class="mb-1">16</h4>
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
                  <h4 class="mb-1">16</h4>
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
            <span class="fs-5 text-white"><?=esc($collaborator['name'])?> <?=esc($collaborator['name'])?> issues</span>
          </div>
          <div class="issues-card card-body overflow-y-auto p-4">
            <?php for ($i=0; $i < 7; $i++): ?>
            <div class="issue d-flex justify-content-between align-items-center mb-2">
              <div>
                <a class="text-accent text-decoration-none fw-bold d-block"
                  href="#"><?=esc(character_limiter('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 50, '...'))?></a>
                <small>
                  <a class="text-accent text-decoration-none fw-bold" href="#"> #124</a>
                  opened on <a class="text-accent text-decoration-none fw-bold" href="#">Jan 24, 2023</a>
                  by <a class="text-accent text-decoration-none fw-bold" href="#">Moquito</a>
                </small>
              </div>
              <div>
                <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
                  style="color: #0d6efd; background-color: #0d6efd1a; border: 1px solid #0d6efd;">
                  Open
                </div>
                <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
                  style="color: #dc3545; background-color: #dc35451a; border: 1px solid #dc3545;">
                  Crash
                </div>
                <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
                  style="color: #fd7e14; background-color: #fd7e141a; border: 1px solid #fd7e14;">
                  Major
                </div>
              </div>
            </div>
            <hr>
            <?php endfor; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>