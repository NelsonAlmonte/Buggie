<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Collaborators <span class="text-primary"><?=!empty($slug) ? 'on ' . $project['name'] : '' ; ?></span>
    </h2>
    <?php if(in_array('collaborator', session()->get('auth')['permissions'])): ?>
    <button type="button" class="btn btn-rounded btn-primary dropdown-toggle px-3" data-bs-toggle="dropdown"
      aria-expanded="false">
      Add collaborator
      <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-2">
      <li class="mb-2">
        <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('manage/collaborator/add')?>">
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
    <?php endif; ?>
  </div>
  <div class="row mt-4">
    <?php foreach ($collaborators as $collaborator): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" x-data x-ref="collaborator">
      <div class="card bg-complementary border border-0 rounded-4 h-100">
        <div class="position-relative">
          <div class="position-absolute top-0 end-0 mt-3 me-4">
            <a role="button" class="btn btn-rounded btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false"><i
                class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu p-2">
              <li>
                <a class="dropdown-item text-white" href="<?=site_url('collaborator/view/'. $collaborator['id'])?>">
                  <div class="d-inline-block">
                    <i class="bi bi-eye"></i>
                  </div>
                  <span>View</span>
                </a>
              </li>
              <?php if(in_array('collaborator', session()->get('auth')['permissions'])): ?>
                <li>
                  <a class="dropdown-item text-white"
                    href="<?=site_url('collaborator/edit/'. $collaborator['id'])?>">
                    <div class="d-inline-block">
                      <i class="bi bi-pencil"></i>
                    </div>
                    <span>Edit</span>
                  </a>
                </li>
                <?php if(isset($project)): ?>
                  <li>
                    <button 
                      class="dropdown-item text-white"
                      x-data="deleteItem"
                      x-init="
                        options = {
                          payload: {
                            collaborator: '<?=$collaborator['id']?>',
                            project: '<?=$project['id']?>'
                          },
                          alert: {
                            title: 'Remove collaborator from this project?',
                            text: 'Are you sure you want to remove this collaborator from this project?',
                            confirmButtonText: 'Yes, remove it'
                          },
                          toast: {
                            text: 'Collaborator removed successfully'
                          },
                          element: $refs.collaborator
                        },
                        url = '/v1/collaborator/removeCollaboratorFromProject'
                      "
                      @click="deleteItem()"
                    >
                      <div class="d-inline-block">
                        <i class="bi bi-person-x"></i>
                      </div>
                      <span>Remove</span>
                    </button>
                  </li>
                <?php endif; ?>
                <?php if(session()->get('id') != $collaborator['id']): ?>
                  <li>
                    <button 
                      class="dropdown-item text-white"
                      x-data="deleteItem"
                      x-init="
                        options = {
                          payload: {
                            collaborator: '<?=$collaborator['id']?>'
                          },
                          alert: {
                            title: 'Delete this collaborator?',
                            text: 'Are you sure you want to delete this collaborator?',
                            confirmButtonText: 'Yes, delete it'
                          },
                          toast: {
                            text: 'Collaborator deleted successfully'
                          },
                          element: $refs.collaborator
                        },
                        url = '/v1/collaborator/deleteCollaborator'
                      "
                      @click="deleteItem()"
                    >
                      <div class="d-inline-block">
                        <i class="bi bi-trash"></i>
                      </div>
                      <span>Delete</span>
                    </button>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
            </ul>
          </div>
        </div>
        <div class="card-body text-center p-5">
          <?php if($collaborator['image'] != DEFAULT_PROFILE_IMAGE): ?>
          <img class="collaborator-image" src="<?=PATH_TO_VIEW_PROFILE_IMAGE . $collaborator['image']?>"
            alt="<?=$collaborator['image']?>">
          <?php else: ?>
          <img class="collaborator-image" src="/assets/img/<?=DEFAULT_PROFILE_IMAGE?>"
            alt="<?=$collaborator['image']?>">
          <?php endif; ?>
          <a class="text-decoration-none" href="<?=site_url('collaborator/view/'. $collaborator['id'])?>">
            <h4 class="card-title text-white mt-3 mb-0">
              <?=esc($collaborator['name'])?>
              <?=esc($collaborator['last'])?>
            </h4>
          </a>
          <p class="mb-4"><?=esc($collaborator['username'])?></p>
          <?= view_cell('App\Cells\Collaborator\CollaboratorSummary\CollaboratorSummary::render', ['collaborator' => $collaborator['id']]); ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?= view_cell('App\Cells\Collaborator\AssignCollaboratorModal\AssignCollaboratorModal::render'); ?>