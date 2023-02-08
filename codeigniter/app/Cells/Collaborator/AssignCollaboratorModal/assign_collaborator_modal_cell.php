<div class="modal fade" id="collaborators-modal" tabindex="-1"
  aria-labelledby="collaborators-modal-label" aria-hidden="true" x-data="{ step: 0 }">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dominant border-0">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div class="modal-header-placeholder">
          <div class="modal-header-icon" :class="step === 0 ? 'd-none' : ''" @click="step --">
            <i class="bi bi-arrow-left-short"></i>
          </div>
        </div>
        <h1 class="modal-title fs-5" id="collaborators-modal-label">Assign collaborators</h1>
        <div class="modal-header-icon" type="button" data-bs-dismiss="modal" aria-label="Close">
          <i class="bi bi-x"></i>
        </div>
      </div>
      <div class="modal-body">
        <div x-data="assignCollaboratorsToProjects" :class="step === 0 ? '' : 'd-none'">
          <div class="form-floating mb-3">
            <input 
              class="form-control bg-complementary border-0" 
              type="text" 
              id="project" 
              placeholder="project"
              autocomplete="off" 
              x-model="query" 
              @input="getProjects"
            >
            <label for="project">Search for projects</label>
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
          <button 
            class="btn btn-rounded btn-primary btn-block w-100 mt-4 py-3"
            type="button" 
            @click="step ++"
          >
            Select projects
          </button>
        </div>
        <div x-data :class="step === 1 ? '' : 'd-none'">
          <h1>Siuuu</h1>
          <button 
            class="btn btn-rounded btn-primary btn-block w-100 mt-4 py-3"
            type="button" 
            @click="step ++"
          >
            Save changes
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('assignCollaboratorsToProjects', () => ({
      query: '',
      projects: [],
      selectedProjects: [],
      async getProjects() {
        const data = {
          url: '/project/searchProjects',
          query: this.query,
          unwanted: this.selectedProjects,
        };

        const [response, error] = await useFetch(data);

        this.projects = response.data;
      },
      selectProject(selectedProject) {
        if (this.selectedProjects.includes(selectedProject)) return;
        this.selectedProjects.push(selectedProject);
        this.projects = [];
        this.query = '';
      },
      removeProject(selectedProject) {
        this.selectedProjects = this.selectedProjects.filter(
          project => project.id !== selectedProject.id
        );
      },
    }));
  });

  async function useFetch(data) {
    try {
      const csrfSelector = document.querySelector('.csrf');
			const csrfName = csrfSelector.attributes.name.value;
			const csrfHash = csrfSelector.value;
      data[csrfName] = csrfHash;

      const source = await fetch(data.url, {
        method: 'POST',
        headers: {
					'Content-Type': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
        body: JSON.stringify(data),
      });
      const response = await source.json();
			csrfSelector.value = response.token;
      return [response, null];
    } catch (error) {
      console.log(error);
      return [null, error];
    }
  }
</script>