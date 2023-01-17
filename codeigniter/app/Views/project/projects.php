<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Projects view works</h2>
    <a class="btn btn-rounded btn-primary" href="<?=site_url('project/add')?>">Add project</a>
  </div>
  <h4 class="alert-heading text-primary"><?= session()->getFlashdata('message') ?></h4>

</div>