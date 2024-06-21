<div 
  class="modal fade" 
  id="collaborators-modal" 
  tabindex="-1"
  aria-labelledby="collaborators-modal-label" 
  aria-hidden="true" 
  x-data="{ step: 0, titles: ['Select projects', 'Select collaborators', 'Confirmation'] }"
  x-ref="modal"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dominant border-0">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div class="modal-header-placeholder">
          <div class="modal-header-icon" :class="step === 0 ? 'd-none' : ''" @click="step --">
            <i class="bi bi-arrow-left-short"></i>
          </div>
        </div>
        <h1 
          class="modal-title fs-5" 
          id="collaborators-modal-label"
          x-text="titles[step]"
        >Assign collaborators</h1>
        <div class="modal-header-icon" data-bs-dismiss="modal" aria-label="Close">
          <i class="bi bi-x"></i>
        </div>
      </div>
      <div class="modal-body">
        <div 
          x-data="searchSelect" 
          x-init='
            options = {
              url: "/v1/project/searchProjects"
            }
          '
          :class="step === 0 ? '' : 'd-none'"
        >
          <div class="form-floating mb-3">
            <input 
              class="form-control bg-complementary border-0" 
              type="text" 
              id="project" 
              placeholder="project"
              autocomplete="off" 
              x-model="query" 
              @input.debounce.500ms="getItems(options)"
            >
            <label for="project">Search for projects</label>
          </div>
          <div>
            <h6 :class="items.length <= 0 ? 'd-none' : ''">Suggestions</h6>
            <template x-for="item in items">
              <div 
                class="project-item d-inline-block text-white fw-bold rounded-5 px-3 py-2 m-1" 
                x-text="item.name"
                @click="selectItem(item)"
              >
              </div>
            </template>
          </div>
          <div class="mt-2">
            <h6 :class="selectedItems.length <= 0 ? 'd-none' : ''">Projects to be assigned</h6>
            <template x-for="selectedItem in selectedItems">
              <div
                class="project-item selected border-0 d-inline-block bg-primary text-white fw-bold rounded-5 px-3 py-2 m-1"
                x-text="selectedItem.name" 
                @click="removeItem(selectedItem)"
              >
              </div>
            </template>
          </div>
          <button 
            class="btn btn-rounded btn-primary btn-block w-100 mt-4 py-3"
            type="button"
            :class="selectedItems.length > 0 ? '' : 'disabled'"
            @click="
              $dispatch('get-selected-projects', JSON.stringify(selectedItems));
              step ++;
            "
          >
            Select projects
          </button>
        </div>
        <div 
          x-data="
            searchSelect, 
            selectedProjects = []
          "
          x-init='
            options = {
              url: "/v1/collaborator/searchCollaborators",
            }
          '
          :class="step === 1 ? '' : 'd-none'"
          @get-selected-projects.window="selectedProjects = JSON.parse($event.detail)"
        >
          <div class="form-floating mb-3">
            <input 
              class="form-control bg-complementary border-0" 
              type="text" 
              id="collaborator" 
              placeholder="collaborator"
              autocomplete="off" 
              x-model="query" 
              @input.debounce.500ms="getItems(options)"
            >
            <label for="collaborator">Search for collaborators</label>
          </div>
          <div>
            <h6 :class="items.length <= 0 ? 'd-none' : ''">Suggestions</h6>
            <template x-for="item in items">
              <div class="collaborator-item d-inline-block rounded-5 px-2 py-2 m-1" @click="selectItem(item)">
                <div class="d-flex align-items-center">
                  <img class="collaborator-item-image" :src="'<?=PATH_TO_VIEW_PROFILE_IMAGE?>' + item.image">
                  <span class="text-white fw-bold mx-2" x-text="item.name"></span>
                </div>
              </div>
            </template>
          </div>
          <div class="mt-2">
            <h6 :class="selectedItems.length <= 0 ? 'd-none' : ''">Selected collaborators</h6>
            <template x-for="selectedItem in selectedItems">
              <div class="collaborator-item d-inline-block border-0 bg-primary rounded-5 px-2 py-2 m-1" @click="removeItem(selectedItem)">
                <div class="d-flex align-items-center">
                  <img class="collaborator-item-image" :src="'<?=PATH_TO_VIEW_PROFILE_IMAGE?>' + selectedItem.image">
                  <span class="text-white fw-bold mx-2" x-text="selectedItem.name"></span>
                </div>
              </div>
            </template>
          </div>
          <button 
            class="btn btn-rounded btn-primary btn-block w-100 mt-4 py-3"
            type="button"
            :class="selectedItems.length > 0 ? '' : 'disabled'"
            @click="
              $dispatch('get-selected-items', JSON.stringify({projects: selectedProjects, collaborators: selectedItems}));
              step ++;
            "
          >
            Assign to projects
          </button>
        </div>
        <div 
          x-data="{ payload: [] }"
          :class="step === 2 ? '' : 'd-none'"
          @get-selected-items.window="payload = JSON.parse($event.detail)"
        >
          <h6>These collaborators</h6>
          <template x-for="collaborator in payload.collaborators">
            <div class="collaborator-item d-inline-block border-0 bg-primary rounded-5 px-2 py-2 m-1">
              <div class="d-flex align-items-center">
                <img class="collaborator-item-image" :src="'<?=PATH_TO_VIEW_PROFILE_IMAGE?>' + collaborator.image">
                <span class="text-white fw-bold mx-2" x-text="collaborator.name"></span>
              </div>
            </div>
          </template>
          <h6 class="mt-3">Will be assigned to these projects</h6>
          <template x-for="project in payload.projects">
            <div
              class="project-item border-0 d-inline-block bg-primary text-white fw-bold rounded-5 px-3 py-2 m-1"
              x-text="project.name" 
            >
          </template>
          <div 
            x-data="saveItem" 
            x-init="
              url = '/v1/collaborator/assignProjects';
              $watch('isLoading', value => {
                if (!value) {
                  bootstrap.Modal.getInstance($refs.modal).hide();
                  step = 0;
                }
              });
            "
          >
            <button 
              class="btn btn-rounded btn-primary btn-block w-100 mt-4 py-3"
              type="button"
              x-init="status = 'Save changes'"
              :disabled="isLoading"
              @click="
                item = { projects: payload.projects, collaborators: payload.collaborators };
                saveItem();
              "
            >
              <span class="spinner-border spinner-border-sm me-1" aria-hidden="true" x-show="isLoading"></span>
              <span role="status" x-text="status"></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
