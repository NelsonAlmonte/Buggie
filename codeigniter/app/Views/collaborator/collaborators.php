<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Collaborators on <span class="text-primary"><?=esc($project['name'])?></span></h2>
    <div class="btn-group" role="group">
      <a class="btn btn-rounded btn-primary" href="<?=site_url('collaborator/add')?>">Add collaborator</a>
      <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown"
        aria-expanded="false">
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
          <a class="dropdown-item d-flex align-items-center text-white" href="#">
            <i class="bi bi-briefcase"></i>
            <span>To this project</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <?php if(session()->getFlashdata('message') !== null): ?>
  <div class="alert alert-<?= session()->getFlashdata('color') ?> d-flex align-items-center my-4" role="alert">
    <i class="bi bi-check-circle flex-shrink-0 me-2"></i>
    <div>
      <?= session()->getFlashdata('message') ?>
    </div>
  </div>
  <?php endif; ?>
</div>