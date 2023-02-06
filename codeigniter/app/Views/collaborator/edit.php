<div class="container-fluid">
  <h2>Edit collaborator</h2>
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
      <form class="row gx-5" action="<?=site_url('collaborator/update/' . $collaborator['id'])?>" method="post" enctype="multipart/form-data">
        <input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <div 
          x-data="{ selectedProjects: [] }" 
          x-init='selectedProjects = <?=json_encode($collaboratorProjects)?>' 
          @get-selected-projects.window="selectedProjects = $event.detail"
        >
          <input type="hidden" name="projects" x-model="JSON.stringify(selectedProjects)">
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
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="username" name="username" required
              placeholder="Username" autocomplete="off" value="<?=esc($collaborator['username'])?>">
            <label for="username">Username*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="password" class="form-control bg-dominant border-0" id="password" name="password"
              placeholder="Password" autocomplete="off">
            <label for="password">Password*</label>
          </div>
        </div>
        <div class="row text-center mt-4">
          <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
            <div 
              x-data="{ selectedProjects: [] }"
              x-init='selectedProjects = <?=json_encode($collaboratorProjects)?>'
              @get-selected-projects.window="selectedProjects = $event.detail"
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
          <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4" x-data>
            <div x-data="imagePreview">
              <button type="button" class="btn btn-rounded btn-dark bg-dominant p-3" @click="$refs.image.click()">
                <i class="bi bi-image me-2"></i>
                <span>Add picture*</span>
              </button>
              <template x-if="imageUrl === ''">
                <img class="img-fluid rounded-4 mt-4" src="<?='/uploads/profile_image/' . $collaborator['image']?>" alt="profile picture">
              </template>
              <template x-if="imageUrl !== ''">
                <img class="img-fluid rounded-4 mt-4" :src="imageUrl" alt="Profile picture">
              </template>
              <input class="d-none" type="file" name="image" id="image" x-ref="image" @change="renderImage($event)" accept="image/*">
            </div>
          </div>
        </div>
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
<div x-data="assignProjects" x-init='selectedProjects = <?=json_encode($collaboratorProjects)?>' class="modal fade" id="projects-modal" tabindex="-1" aria-labelledby="projects-modal-label"
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
            <div class="project-item d-inline-block text-white fw-bold rounded-5 px-3 py-2 m-1" x-text="project.name"
              @click="selectProject(project)">
            </div>
          </template>
        </div>
        <div>
          <h6 :class="selectedProjects.length <= 0 ? 'd-none' : ''">Projects to be assigned</h6>
          <template x-for="selectedProject in selectedProjects">
            <div
              class="project-item selected border-0 d-inline-block bg-primary text-white fw-bold rounded-5 px-3 py-2 m-1"
              x-text="selectedProject.name" @click="removeProject(selectedProject)">
            </div>
          </template>
        </div>
      </div>
      <div class="p-3">
        <button type="button" class="btn btn-rounded btn-primary btn-block w-100 py-3" data-bs-dismiss="modal"
          @click="$dispatch('get-selected-projects', selectedProjects)">Save changes</button>
      </div>
    </div>
  </div>
</div>