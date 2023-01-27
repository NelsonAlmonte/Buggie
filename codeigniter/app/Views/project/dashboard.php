<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <h2><?=$project['name']?></h2>
      <div class="status-badge d-inline-block text-capitalize"
        style="color: #0d6efd; background-color: #0d6efd1a; border: 1px solid #0d6efd;">
        <?=esc($project['status'])?>
      </div>
      <div class="d-inline-block mx-2">|</div>
      <div class="d-inline-block">
        Owner: <span class="text-white"><?=esc($project['owner'])?></span>
      </div>
    </div>
    <div>
      <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false"><i
          class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu p-2">
        <li>
          <a class="dropdown-item text-white"
            href="<?=site_url('project/' . $project['slug'] .'/edit/'. $project['id'])?>">
            <div class="d-inline-block">
              <i class="bi bi-pencil"></i>
            </div>
            <span>Edit</span>
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
            <span>Documents</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item text-white" href="#">
            <div class="d-inline-block">
              <i class="bi bi-people"></i>
            </div>
            <span>Collaborator</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-5">
      <a class="text-decoration-none" href="#">
        <div class="card bg-complementary border border-0 rounded-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-start align-items-center">
              <div class="info-card-icon d-flex justify-content-center align-items-center rounded-4 me-4 p-4"
                style="background-color: #0d6efd1a; border: 1px solid #0d6efd;">
                <i class="bi bi-bug fs-1" style="color: #0d6efd;"></i>
              </div>
              <div>
                <h3 class="mb-1">16</h3>
                <h5 class="text-white-50">Open issues</h5>
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
                style="background-color: #1987541a; border: 1px solid #198754;">
                <i class="bi bi-bug-fill fs-1" style="color: #198754;"></i>
              </div>
              <div>
                <h3 class="mb-1">32</h3>
                <h5 class="text-white-50">Closed issues</h5>
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
                style="background-color: #fd7e141a; border: 1px solid #fd7e14;">
                <i class="bi bi-people fs-1" style="color: #fd7e14;"></i>
              </div>
              <div>
                <h3 class="mb-1">64</h3>
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
                <i class="bi bi-file-earmark-text fs-1" style="color: #6610f2;"></i>
              </div>
              <div>
                <h3 class="mb-1">128</h3>
                <h5 class="text-white-50">Documents</h5>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="d-flex justify-content-between align-items-center card-header bg-complementary rounded-top-4 px-4 py-3">
          <span class="fs-5 text-white">Latest issues</span>
          <div>
            <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false"><i
                class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu p-2">
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
                    <i class="bi bi-plus-lg"></i>
                  </div>
                  <span>Add issue</span>
                </a>
              </li>
            </ul>
          </div>
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
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4">
        <div class="card-header bg-complementary fs-5 text-white rounded-top-4 p-4">Issues graph</div>
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