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
        <div class="modal-header-icon" data-bs-dismiss="modal" aria-label="Close">
          <i class="bi bi-x"></i>
        </div>
      </div>
      <div class="modal-body">
        <div x-data="searchSelect" :class="step === 0 ? '' : 'd-none'">
          <div class="form-floating mb-3">
            <input 
              class="form-control bg-complementary border-0" 
              type="text" 
              id="project" 
              placeholder="project"
              autocomplete="off" 
              x-model="query" 
              @input="getItems('project', 'searchProjects')"
            >
            <label for="project">Search for projects</label>
          </div>
          <div class="mb-4">
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
          <div>
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
          x-data="searchSelect, selectedProjects = []" 
          :class="step === 1 ? '' : 'd-none'"
          @get-selected-projects.window="selectedProjects = JSON.parse($event.detail)"
        >
          <h6>Assign these projects to</h6>
          <template x-for="selectedProject in selectedProjects" :key="selectedProject.id">
            <div
              class="project-item selected border-0 d-inline-block bg-primary text-white fw-bold rounded-5 px-3 py-2 m-1"
              x-text="selectedProject.name"
            >
            </div>
          </template>
          <div class="form-floating my-3">
            <input 
              class="form-control bg-complementary border-0" 
              type="text" 
              id="collaborator" 
              placeholder="collaborator"
              autocomplete="off" 
              x-model="query" 
              @input="getItems('collaborator', 'searchCollaborators')"
            >
            <label for="collaborator">Search for collaborators</label>
          </div>
          <div class="mb-4">
            <h6 :class="items.length <= 0 ? 'd-none' : ''">Suggestions</h6>
            <template x-for="item in items">
              <div class="collaborator-item d-inline-block rounded-5 px-2 py-2 m-1" @click="selectItem(item)">
                <div class="d-flex align-items-center">
                  <img class="collaborator-item-image" :src="'/uploads/profile-image/' + item.image">
                  <span class="text-white fw-bold mx-2" x-text="item.name"></span>
                </div>
              </div>
            </template>
          </div>
          <div>
            <h6 :class="selectedItems.length <= 0 ? 'd-none' : ''">Selected collaborators</h6>
            <template x-for="selectedItem in selectedItems">
              <div class="project-item d-inline-block border-0 bg-primary rounded-5 px-2 py-2 m-1" @click="removeItem(selectedItem)">
                <div class="d-flex align-items-center">
                  <img class="collaborator-item-image" :src="'/uploads/profile-image/' + selectedItem.image">
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
              $dispatch('get-selected-items', JSON.stringify(selectedItems));
              step ++;
            "
          >
            Assign to projects
          </button>
        </div>
        <div x-data :class="step === 2 ? '' : 'd-none'">
          
        </div>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
