<div class="container-fluid">
  <h2>Add a new role</h2>
  <div class="card mt-4">
    <div class="card-body bg-complementary p-4">
      <form class="row gx-5" action="<?=site_url('manage/role/save')?>" method="post">
        <?= csrf_field() ?>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name" placeholder="Name"
              required autocomplete="off">
            <label for="name">Name*</label>
          </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
          <div class="form-floating">
            <textarea class="form-control bg-dominant border-0" placeholder="Enter a project description here"
              name="description" id="description" required style="height: 100px;"></textarea>
            <label for="description">Description*</label>
          </div>
        </div>
        <div class="col-md-12">
          <h4>Asignar permisos a este rol</h4>
          <table class="table bg-dominant table-hover table-responsive text-white">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Add permission</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($permissions as $permission): ?>
              <tr>
                <td><?=esc($permission['name'])?></td>
                <td><?=esc($permission['description'])?></td>
                <td>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="permissions[]"
                      value="<?=esc($permission['id'])?>">
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Save role</button>
        </div>
      </form>
    </div>
  </div>
</div>