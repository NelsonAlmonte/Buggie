<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Roles</h2>
    <?php if(in_array('role', session()->get('auth')['permissions'])): ?>
    <a class="btn btn-rounded btn-primary" href="<?=site_url('manage/role/add')?>">Add role</a>
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
  <div class="card bg-complementary border-0 rounded-4 p-4 mt-4">
    <table class="table bg-dominant table-bordered table-responsive text-white">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($roles as $role): ?>
        <tr>
          <td><?=esc($role['name'])?></td>
          <td><?=esc($role['description'])?></td>
          <td>
            <a class="btn btn-success btn-rounded me-2" href="<?=site_url('manage/role/edit/') . $role['id']?>"><i
                class="bi bi-pencil me-2"></i>Edit</a>

            <button class="btn btn-danger btn-rounded"><i
                class="bi bi-trash me-2"></i>Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>