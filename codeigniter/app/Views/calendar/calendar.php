<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-start">
    <?php if(count($projects) > 1): ?>
      <h2>Calendar on</h2>
      <div 
        class="dropdown ms-2"
        x-data='{ selectedProject: "" }'
        x-init='selectedProject = <?=json_encode($projects[0]['name'])?>'
      >
        <button 
          class="btn btn-primary rounded-3 dropdown-toggle" 
          type="button" 
          data-bs-toggle="dropdown" 
          aria-expanded="false"
          x-text="selectedProject"
        >
        </button>
        <ul class="dropdown-menu p-2">
          <?php foreach($projects as $project): ?>
            <li>
              <a 
                class="dropdown-item text-white" 
                role="button"
                @click='
                  selectedProject = <?=json_encode($project['name'])?>;
                  $dispatch("get-project", { id: <?=json_encode($project['id'])?>, slug: <?=json_encode($project['slug'])?> })
                '
              ><?=$project['name']?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php else: ?>
      <h2>Reports on <span class="text-primary"><?=$projects[0]['name']?></span></h2>
    <?php endif; ?>
  </div>
  <div
    x-data="calendar" 
    x-init='
      projectId = <?=json_encode($projects[0]['id'])?>;
      projectSlug = <?=json_encode($projects[0]['slug'])?>;
      $watch("projectId", value => initCalendar($refs.calendar));
      initCalendar($el);
    '
    @get-project.window='
      projectId = $event.detail.id
      projectSlug = $event.detail.slug
    '
    x-ref="calendar"
  >
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

