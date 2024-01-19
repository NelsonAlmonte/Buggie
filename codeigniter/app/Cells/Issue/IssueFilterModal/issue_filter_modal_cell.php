<div class="modal fade" id="issues-filter-modal" tabindex="-1" aria-labelledby="issues-filter-modal-label"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-complementary border-0">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div class="modal-header-placeholder"></div>
        <h1 class="modal-title fs-5" id="issues-filter-modal-label">Filter issues by</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form 
          action="<?=site_url('issue/' . $slug)?>" 
          method="get" 
          x-data 
          @submit="disableEmptyInputs($el)"
        >
          <div class="form-floating auto-complete mb-3">
            <input 
              class="form-control bg-dominant border-0" 
              type="text" 
              name="title"
              placeholder="title"
              autocomplete="off" 
              value="<?=isset($_GET['title']) ? $_GET['title'] : '' ;?>"
            >
            <label class="text-capitalize" for="title">Title</label>
          </div>
          <?php foreach($filters as $key => $filter): ?>
            <div 
              class="form-floating auto-complete mb-3"
              x-data="searchSelect" 
              x-init="
                query = '<?=isset($_GET[$key]) ? $_GET[$key] : '' ;?>',
                options = {
                  controller: '<?=$filter['controller']?>',
                  method: '<?=$filter['method']?>',
                  project: '<?=$project['id']?>'
                }
              " 
            >
              <input 
                class="form-control bg-dominant border-0" 
                type="text" 
                name="<?=esc($key)?>"
                placeholder="<?=esc($key)?>"
                autocomplete="off" 
                value="<?=isset($_GET[$key]) ? $_GET[$key] : '' ;?>"
                x-model="query"
                @input="getItems(options)"
              >
              <ul x-show="items.length > 0">
                <template x-for="item in items">
                  <li
                    @click="
                      query = item.name;
                      if (item.username !== undefined) query = item.username; 
                      items = []; 
                    "
                    @click.outside="items = []"
                  >
                    <template x-if="item.last && item.username">
                      <span x-html="`<strong>${item.name} ${item.last}</strong> - <small>${item.username}</small>`"></span>
                    </template>
                    <template x-if="!item.last && !item.username">
                      <span class="text-capitalize" x-html="`<strong>${item.name}</strong>`"></span>
                    </template>
                  </li>
                </template>
              </ul>
              <label class="text-capitalize" for="<?=esc($key)?>"><?=esc($key)?></label>
            </div>
          <?php endforeach; ?>
        </div>
       <div class="px-3 pb-3">
          <button type="submit" class="btn btn-rounded btn-primary btn-block w-100 py-3" data-bs-dismiss="modal">Search</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function disableEmptyInputs(el) {
    const inputs = Array.from(el.elements);
    inputs.forEach((element) => {
      if (element.value === '') element.disabled = true;
    });
  }
</script>
