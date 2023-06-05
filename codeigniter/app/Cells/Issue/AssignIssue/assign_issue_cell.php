<div 
  class="modal fade" 
  id="assign-issue-modal" 
  tabindex="-1" 
  aria-labelledby="assign-issue-modal-label"
  aria-hidden="true"
  x-data="searchSelect" 
  @get-issue-id.window="$refs.issue.value = $event.detail.issue"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-complementary border-0">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div class="modal-header-placeholder"></div>
        <h1 class="modal-title fs-5" id="assign-issue-modal-label">Assign this issue</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-floating auto-complete">
          <input 
            type="text" 
            class="form-control bg-dominant border-0" 
            id="assignee-search" 
            name="assignee-search"
            placeholder="Assign to" 
            autocomplete="off" 
            x-model="query"
            @input="getItems('collaborator', 'searchCollaborators')"
          >
          <ul x-show="items.length > 0">
            <template x-for="item in items">
              <li 
                x-text="`${item.name} ${item.last} - ${item.username}`" 
                @click="
                  query = `${item.name} ${item.last}`; 
                  items = [];                   
                  $refs.collaborator.value = item.id;"
                @click.outside="items = []">
              </li>
            </template>
          </ul>
          <label for="assign to">Assign to</label>
          <input type="hidden" x-ref="issue">
          <input type="hidden" x-ref="collaborator">
        </div>
      </div>
      <div class="px-3 pb-3">
        <button 
          class="btn btn-rounded btn-primary btn-block w-100 py-3" 
          type="button"
          @click="assignIssue($refs.issue.value, $refs.collaborator.value)"
        >Assign issue</button>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<script>
  async function assignIssue(issue, collaborator) {
    try {
			const csrfSelector = document.querySelector('.csrf');
			const csrfName = csrfSelector.attributes.name.value;
			const csrfHash = csrfSelector.value;
      const payload = {
        [csrfName]: csrfHash,
        url: '/issue/assignIssue',
        issue: issue,
        collaborator: collaborator,
      }

			const source = await fetch(payload.url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
				body: JSON.stringify(payload),
			});
			const response = await source.json();
			csrfSelector.value = response.token;
			console.log(response);
		} catch (error) {
			console.log(error);
		}
  }
</script>
