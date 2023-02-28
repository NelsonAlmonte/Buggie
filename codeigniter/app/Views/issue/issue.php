<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2><?=esc($issue['title'])?><span class="text-primary"> #<?=esc($issue['id'])?></span>
    </h2>
    <a class="btn btn-rounded btn-primary px-3" type="button"
      href="<?=site_url('issue/' . $slug . '/edit/' . $issue['id'])?>">
      Edit issue
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
</div>