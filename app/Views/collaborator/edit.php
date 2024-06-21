<div class="container-fluid">
  <h2>Edit collaborator</h2>
  <div class="card border-0 rounded-4 mt-4">
    <div class="card-body rounded-4 bg-complementary p-4">
      <form class="row gx-5" action="<?=site_url('collaborator/update/' . $collaborator['id'])?>" method="post" enctype="multipart/form-data">
        <input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <div 
          x-data="{ selectedProjects: [] }" 
          x-init='
            selectedProjects = <?=json_encode($collaboratorProjects)?>;
            selectedProjects = JSON.stringify(selectedProjects);
          ' 
          @get-selected-projects.window="selectedProjects = $event.detail"
        >
          <input type="hidden" name="projects" x-model="selectedProjects">
        </div>
        <div class="col-12 mb-4">
          <h4><i class="bi bi-person-vcard text-primary me-3"></i>Basic info</h4>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name" required
              placeholder="Name" autocomplete="off" value="<?=esc($collaborator['name'])?>">
            <label for="name">Name*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="last" name="last" required
              placeholder="Last" autocomplete="off" value="<?=esc($collaborator['last'])?>">
            <label for="last">Last*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="email" class="form-control bg-dominant border-0" id="email" name="email" required
              placeholder="Email" autocomplete="off" value="<?=esc($collaborator['email'])?>">
            <label for="email">Email*</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-box-arrow-in-right text-primary me-3"></i>Login credentials</h4>
        </div>
        <?php if(in_array('collaborator', session()->get('auth')['permissions'])): ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="username" name="username" required
              placeholder="Username" autocomplete="off" value="<?=esc($collaborator['username'])?>">
            <label for="username">Username*</label>
          </div>
        </div>
        <?php endif; ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="password" class="form-control bg-dominant border-0" id="password" name="password"
              placeholder="Password" autocomplete="off">
            <label for="password">Password*</label>
          </div>
        </div>
        <?php if(in_array('collaborator', session()->get('auth')['permissions'])): ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="role" name="role"
              aria-label="role">
              <?php foreach($roles as $role): ?>
              <option <?=$collaborator['role'] == $role['id'] ? 'selected' : '' ?> value="<?=esc($role['id'])?>"><?=esc($role['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="role">Role</label>
          </div>
        </div>
        <?php endif; ?>
        <div class="col-12 my-4">
          <h4><i class="bi bi-images text-primary me-3"></i>Multimedia</h4>
        </div>
        <div class="col-12 mb-4" x-data>
          <div x-data="imagePreview">
            <button type="button" class="btn btn-rounded btn-dark bg-dominant p-3" @click="$refs.image.click()">
              <i class="bi bi-image me-2"></i>
              <span>Add picture*</span>
            </button>
            <template x-if="imageUrl === ''">
              <?php if($collaborator['image'] != DEFAULT_PROFILE_IMAGE): ?>
              <img class="d-block w-25 rounded-4 mt-4" src="<?=PATH_TO_VIEW_PROFILE_IMAGE . $collaborator['image']?>" alt="profile picture">
              <?php endif; ?>
            </template>
            <template x-if="imageUrl !== ''">
              <img class="d-block w-25 rounded-4 mt-4" :src="imageUrl" alt="Profile picture">
            </template>
            <input class="d-none" type="file" name="image" id="image" x-ref="image" @change="renderImage($event)" accept="image/*">
          </div>
        </div>
        <?php if(in_array('collaborator', session()->get('auth')['permissions'])): ?>
        <div class="col-12 my-4">
          <h4><i class="bi bi-briefcase text-primary me-3"></i>Projects</h4>
        </div>
        <div class="col-12 mb-4">
          <div 
            x-data="{ selectedProjects: [] }"
            x-init='selectedProjects = <?=json_encode($collaboratorProjects)?>'
            @get-selected-projects.window="selectedProjects = JSON.parse($event.detail)"
          >
            <button type="button" class="btn btn-rounded btn-dark bg-dominant p-3" data-bs-toggle="modal"
              data-bs-target="#projects-modal">
              <i class="bi bi-briefcase me-2"></i>
              <span>Assign projects</span>
            </button>
            <template x-if="selectedProjects.length > 0">
              <div class="mt-4">
                <h6>Assigned projects</h6>
                <template x-for="selectedProject in selectedProjects">
                  <div 
                    class="project-item selected border-0 d-inline-block bg-primary text-white fw-bold rounded-5 px-3 py-2 m-1"
                    x-text="selectedProject.name" 
                  >
                  </div>
                </template>
              </div>
            </template>
          </div>
        </div>
        <?php endif; ?>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Update collaborator</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= view_cell('App\Cells\Collaborator\AssignProjectModal\AssignProjectModal::render', ['collaboratorProjects' => $collaboratorProjects]); ?>
