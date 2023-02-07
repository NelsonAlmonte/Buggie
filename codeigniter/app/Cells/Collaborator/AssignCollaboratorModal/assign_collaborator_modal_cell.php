<div x-data="assignCollaboratorsToProjects" class="modal fade" id="collaborators-modal" tabindex="-1"
  aria-labelledby="collaborators-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dominant border-0">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div class="modal-header-placeholder"></div>
        <h1 class="modal-title fs-5" id="collaborators-modal-label">Assign collaborators</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-floating mb-3">
          <input type="text" class="form-control bg-complementary border-0" id="project" placeholder="project"
            autocomplete="off" x-model="queryProject" @input="getProjects">
          <label for="project">Search for projects</label>
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
        <button type="button" class="btn btn-rounded btn-primary btn-block w-100 py-3" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('assignCollaboratorsToProjects', () => ({
      queryProject: '',
      queryCollaborator: '',
      projects: [],
      selectedProjects: [],
      collaborators: [],
      selectedCollaborators: [],
      async getProjects() {
        const data = {
          url: '/project/searchProjects',
          query: this.queryProject,
          unwanted: this.selectedProjects,
        };

        const [response, error] = await useFetch(data);

        console.log(response);
        console.log(error);
      }
    }));
  });

  async function useFetch(data) {
    try {
      const csrfSelector = document.querySelector('.csrf');
			const csrfName = csrfSelector.attributes.name.value;
			const csrfHash = csrfSelector.value;

      data[csrfName] = csrfHash;
      console.log(data);
      const source = await fetch(data.url, {
        method: 'POST',
        headers: {
					'Content-Type': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
        body: JSON.stringify(data.body),
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