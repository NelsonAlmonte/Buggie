<div x-data="searchSelect">
  <a class="issue-card-filter dropdown-toggle text-capitalize" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" @click="getItems('<?=$controller?>', '<?=$method?>'); $refs.query.focus()">
    <?=esc($name)?>
  </a>
  <ul class="dropdown-menu p-3">
    <small class="text-white fw-bold">Filter by <?=esc($name)?></small>
    <input class="form-control bg-complementary border-0 my-3" type="text" placeholder="Search" aria-label="search" x-ref="query" x-model="query" @input="getItems('<?=$controller?>', '<?=$method?>')">
    <template x-for="selectedItem in selectedItems">
      <li>
        <a 
          class="dropdown-item text-white" 
          role="button"
          @click="removeItem(selectedItem)"
        >
          <div class="d-inline-block">
            <i class="bi bi-check-circle"></i>
          </div>
          <span class="text-capitalize" x-text="selectedItem.name"></span>
        </a>
      </li>
    </template>
    <template x-for="item in items">
      <li>
        <a 
          class="dropdown-item text-white" 
          role="button" 
          @click="selectItem(item)"
        >
          <div class="d-inline-block">
            <i class="bi bi-circle"></i>
          </div>
          <span class="text-capitalize" x-text="item.name"></span>
        </a>
      </li>
    </template>
  </ul>
</div>