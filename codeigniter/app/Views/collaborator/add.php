<div class="container-fluid">
  <h2>Add a new collaborator</h2>
  <div class="card mt-4">
    <div class="card-body bg-complementary p-4">
      <?php if(session()->getFlashdata('message') !== null): ?>
      <div class="alert alert-<?= session()->getFlashdata('color') ?> d-flex align-items-center my-4" role="alert">
        <i class="bi bi-check-circle flex-shrink-0 me-2"></i>
        <div>
          <?= session()->getFlashdata('message') ?>
        </div>
      </div>
      <?php endif; ?>
      <form class="row gx-5" action="<?=site_url('collaborator/save')?>" method="post">
        <input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <div 
          x-data="{ selectedProjects: [] }" 
          @get-selected-projects.window="selectedProjects = $event.detail"
        >
          <input type="text" name="projects" x-model="selectedProjects">
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="name" name="name" required
              placeholder="Name" autocomplete="off">
            <label for="name">Name*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="last" name="last" required
              placeholder="Last" autocomplete="off">
            <label for="last">Last*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="email" class="form-control bg-dominant border-0" id="email" name="email" required
              placeholder="Email" autocomplete="off">
            <label for="email">Email*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="username" name="username" required
              placeholder="Username" autocomplete="off">
            <label for="username">Username*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="password" class="form-control bg-dominant border-0" id="password" name="password" required
              placeholder="Password" autocomplete="off">
            <label for="password">Password*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="file" class="form-control bg-dominant border-0" id="image" name="image" placeholder="Image"
              autocomplete="off">
            <label for="image">Profile picture*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <button type="button" class="btn btn-rounded btn-dark bg-dominant p-3" data-bs-toggle="modal"
            data-bs-target="#projects-modal">
            <i class="bi bi-briefcase me-2"></i>
            <span>Assign projects</span>
          </button>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Add collaborator</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div x-data="assignProjects" class="modal fade" id="projects-modal" tabindex="-1" aria-labelledby="projects-modal-label"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dominant border-0">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div class="modal-header-placeholder"></div>
        <h1 class="modal-title fs-5" id="projects-modal-label">Assign projects</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-floating mb-3">
          <input type="text" class="form-control bg-complementary border-0" id="projects" placeholder="project"
            autocomplete="off" x-model="query" @input="getProjects">
          <label for="projects">Search for projects</label>
        </div>
        <div class="mb-4">
          <h6 :class="projects.length <= 0 ? 'd-none' : ''">Suggestions</h6>
          <template x-for="project in projects">
            <div 
              class="project-item d-inline-block text-white fw-bold rounded-5 px-3 py-2 m-1"
              x-text="project.name" 
              @click="selectProject(project)"
            >
            </div>
          </template>
        </div>
        <div>
          <h6 :class="selectedProjects.length <= 0 ? 'd-none' : ''">Projects to be assigned</h6>
          <template x-for="selectedProject in selectedProjects">
            <div 
              class="project-item selected border-0 d-inline-block bg-primary text-white fw-bold rounded-5 px-3 py-2 m-1"
              x-text="selectedProject.name" 
              @click="removeProject(selectedProject)"
            >
            </div>
          </template>
        </div>
      </div>
      <div class="p-3">
        <button type="button" class="btn btn-rounded btn-primary btn-block w-100 py-3" @click="$dispatch('get-selected-projects', JSON.stringify(selectedProjects))">Save changes</button>
      </div>
    </div>
  </div>
</div>