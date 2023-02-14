<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Collaborators <span class="text-primary"><?=!empty($slug) ? 'on ' . $project['name'] : '' ; ?></span>
    </h2>
    <button type="button" class="btn btn-rounded btn-primary dropdown-toggle px-3" data-bs-toggle="dropdown"
      aria-expanded="false">
      Add collaborator
      <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-2">
      <li class="mb-2">
        <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('collaborator/add')?>">
          <i class="bi bi-person-plus"></i>
          <span>New collaborator</span>
        </a>
      </li>
      <li>
        <a class="dropdown-item d-flex align-items-center text-white" href="#" data-bs-toggle="modal"
          data-bs-target="#collaborators-modal">
          <i class="bi bi-briefcase"></i>
          <span>To project</span>
        </a>
      </li>
    </ul>
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
    <?php foreach ($collaborators as $collaborator): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card bg-complementary border border-0 rounded-4 h-100">
        <div class="position-relative">
          <div class="position-absolute top-0 end-0 mt-3 me-3">
            <a role="button" class="btn btn-rounded btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false"><i
                class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu p-2">
              <li>
                <a class="dropdown-item text-white" href="#">
                  <div class="d-inline-block">
                    <i class="bi bi-eye"></i>
                  </div>
                  <span>View</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item text-white" href="<?=site_url('collaborator/edit/'. $collaborator['id'])?>">
                  <div class="d-inline-block">
                    <i class="bi bi-pencil"></i>
                  </div>
                  <span>Edit</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item text-white" href="#">
                  <div class="d-inline-block">
                    <i class="bi bi-trash"></i>
                  </div>
                  <span>Delete</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-body text-center p-5">
          <img class="collaborator-image" src="<?='/uploads/profile-image/' . $collaborator['image']?>"
            alt="<?=$collaborator['image']?>">
          <a class="text-decoration-none" href="#">
            <h4 class="card-title text-white mt-3 mb-0">
              <?=esc($collaborator['name'])?>
              <?=esc($collaborator['last'])?>
            </h4>
          </a>
          <p class="mb-4"><?=esc($collaborator['username'])?></p>
          <div class="d-flex justify-content-between">
            <div class="text-center">
              <i class="bi bi-briefcase text-white fs-4"></i>
              <small class="d-block">Projects</small>
              <h5 class="fw-bold mb-0">42</h5>
            </div>
            <div class="text-center">
              <i class="bi bi-bug text-white fs-4"></i>
              <small class="d-block">Issues</small>
              <h5 class="fw-bold mb-0">42</h5>
            </div>
            <div class="text-center">
              <i class="bi bi-bug-fill text-white fs-4"></i>
              <small class="d-block">Closed</small>
              <h5 class="fw-bold mb-0">42</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?= view_cell('App\Cells\Collaborator\AssignCollaboratorModal\AssignCollaboratorModal::render'); ?>
