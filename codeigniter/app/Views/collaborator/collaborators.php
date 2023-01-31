<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <a class="btn btn-rounded btn-primary" href="<?=site_url('collaborator/add')?>">Add collaborator</a>
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