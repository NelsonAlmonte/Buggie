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
        <button type="button" class="btn btn-rounded btn-primary btn-block w-100 py-3" data-bs-dismiss="modal" @click="$dispatch('get-selected-projects', JSON.stringify(selectedProjects))">Save changes</button>
      </div>
    </div>
  </div>
</div>
