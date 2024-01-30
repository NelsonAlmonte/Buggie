<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Roles</h2>
    <?php if(in_array('role', session()->get('auth')['permissions'])): ?>
    <a class="btn btn-rounded btn-primary" href="<?=site_url('manage/role/add')?>">Add role</a>
    <?php endif; ?>
  </div>
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
            <a class="btn btn-success btn-rounded me-2" href="<?=site_url('manage/role/edit/') . $role['id']?>">Edit</a>
            <button class="btn btn-danger btn-rounded">Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>