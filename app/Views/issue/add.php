<div class="container-fluid">
  <h2>Add a new issue</h2>
  <div class="card border-0 rounded-4 mt-4">
    <div class="card-body rounded-4 bg-complementary p-4">
      <form class="row gx-5" action="<?=site_url('issue/'. $project['slug'] . '/save')?>" method="post"
        enctype="multipart/form-data">
        <input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <input type="hidden" id="project" name="project" value="<?=esc($project['id'])?>">
        <div class="col-12 mb-4">
          <h4><i class="bi bi-person-vcard text-primary me-3"></i>Information</h4>
        </div>
        <div class="col-12 mb-4">
          <div class="form-floating">
            <input type="text" class="form-control bg-dominant border-0" id="title" name="title" required
              placeholder="Title" autocomplete="off">
            <label for="title">Title*</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <textarea 
            x-data="froalaEditor" 
            x-init="initFroala($el)" 
            id="description" 
            name="description"
          ></textarea>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-file-earmark-text text-primary me-3"></i>Files</h4>
        </div>
        <div class="col-12 mb-4">
          <div x-data="filesPreview">
            <button 
              type="button" 
              class="btn btn-rounded btn-dark bg-dominant p-3" 
              @click="$refs.files.click()"
            >
              <i class="bi bi-image me-2"></i>
              <span>Add files</span>
            </button>
            <div class="row mt-4">
              <template x-for="(file, index) in filesPreview">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                  <div class="bg-dominant rounded-4 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex justify-content-start align-items-center text-white">
                        <div class="bg-primary rounded-3 p-2 me-2">
                          <small class="text-uppercase" x-text="file.type"></small>
                        </div>
                        <span x-text="file.name"></span>
                      </div>
                      <div class="d-flex justify-content-start align-items-center">
                        <button 
                          class="btn btn-rounded btn-danger" 
                          type="button"
                          @click="removeFile(index, $refs.files, file)"
                        >
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
            <input class="d-none" type="file" name="files[]" id="files" x-ref="files" @change="renderFiles($event)"
              multiple>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-people text-primary me-3"></i>Collaborators</h4>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div 
            x-data="searchSelect" 
            x-init='
              query = <?=json_encode($auth["fullname"])?>,
              options = {
                url: "/v1/collaborator/searchCollaborators",
                project: <?=json_encode($project['id'])?>
              }
            ' 
            class="form-floating auto-complete"
          >
            <input 
              type="text" 
              class="form-control bg-dominant border-0" 
              id="reporter-search" 
              name="reporter-search" 
              required
              placeholder="Reporter" 
              autocomplete="off" 
              x-model="query"
              @input.debounce.500ms="getItems(options)"
            >
            <ul x-show="items.length > 0">
              <template x-for="item in items">
                <li 
                  x-text="`${item.name} ${item.last} - ${item.username}`" 
                  @click="
                    query = `${item.name} ${item.last}`; 
                    items = []; 
                    $refs.reporter.value = item.id;"
                  @click.outside="items = []">
                </li>
              </template>
            </ul>
            <label for="reporter-search">Reporter*</label>
            <input 
              type="hidden" 
              id="reporter" 
              name="reporter" 
              x-ref="reporter" 
              value="<?=esc($auth["id"])?>"
            >
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div 
            x-data="searchSelect" 
            x-init='
              options = {
                url: "/v1/collaborator/searchCollaborators",
                project: <?=json_encode($project['id'])?>
              }
            '
            class="form-floating auto-complete"
          >
            <input 
              type="text" 
              class="form-control bg-dominant border-0" 
              id="assignee-search" 
              name="assignee-search"
              placeholder="Assign to" 
              autocomplete="off" 
              x-model="query"
              @input.debounce.500ms="getItems(options)"
            >
            <ul x-show="items.length > 0">
              <template x-for="item in items">
                <li 
                  x-text="`${item.name} ${item.last} - ${item.username}`" 
                  @click="
                    query = `${item.name} ${item.last}`; 
                    items = []; 
                    $refs.assignee.value = item.id;"
                  @click.outside="items = []">
                </li>
              </template>
            </ul>
            <label for="assign to">Assign to</label>
            <input 
              type="hidden" 
              id="assignee" 
              name="assignee" 
              x-ref="assignee"
            >
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-info-circle text-primary me-3"></i>Status</h4>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="classification" name="classification"
              aria-label="classification">
              <?php foreach($status['classification'] as $classification): ?>
              <option value="<?=esc($classification['id'])?>"><?=esc($classification['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="classification">Classification</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="severity" name="severity"
              aria-label="severity">
              <?php foreach($status['severity'] as $severity): ?>
              <option value="<?=esc($severity['id'])?>"><?=esc($severity['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="severity">Severity</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <select class="form-select bg-dominant border-0 text-capitalize" id="status" name="status"
              aria-label="status">
              <?php foreach($status['issue_status'] as $issueStatus): ?>
              <option value="<?=esc($issueStatus['id'])?>"><?=esc($issueStatus['name'])?></option>
              <?php endforeach; ?>
            </select>
            <label for="status">Status</label>
          </div>
        </div>
        <div class="col-12 my-4">
          <h4><i class="bi bi-calendar3 text-primary me-3"></i>Dates</h4>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="start_date" name="start_date"
              value="<?=date('Y-m-d')?>" required>
            <label for="start_date">Start date*</label>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
          <div class="form-floating">
            <input type="date" class="form-control bg-dominant border-0" id="end_date" name="end_date">
            <label for="end_date">End date</label>
          </div>
        </div>
        <div class="col-12 mb-4">
          <small>Fields with * are required.</small>
        </div>
        <div class="col-12">
          <button class="btn btn-rounded btn-primary" type="submit">Add issue</button>
        </div>
      </form>
    </div>
  </div>
</div>