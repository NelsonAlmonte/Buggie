<div class="container-fluid">
  <div class="d-flex flex-column align-items-start flex-md-row align-items-md-center justify-content-between">
    <div class="d-flex align-items-center justify-content-start">
      <?php if(count($projects) > 1): ?>
        <h2>Reports on</h2>
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
                    $dispatch("get-project", <?=json_encode($project['id'])?>)
                  '
                ><?=$project['name']?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php else: ?>
        <h2>
          Reports on 
          <span class="text-primary">
            <?=isset($projects[0]['name']) ? json_encode($projects[0]['name']) : '' ?>
          </span>
        </h2>
      <?php endif; ?>
    </div>
    <div class="d-flex align-items-center justify-content-start">
      <div 
        class="dropdown me-2"
        x-data='reportChart'
      >
        <button 
          class="btn btn-primary rounded-3 dropdown-toggle text-capitalize" 
          type="button" 
          data-bs-toggle="dropdown" 
          aria-expanded="false"
        >
          <i class="bi me-1" :class="selectedChartType.icon"></i>
          <span x-text="selectedChartType.name"></span>
        </button>
        <ul class="dropdown-menu p-2">
          <template x-for="chartType in chartTypes">
            <li>
              <a 
                class="dropdown-item text-white" 
                role="button"
                @click='
                  selectedChartType.name = chartType.name;
                  selectedChartType.icon = chartType.icon;
                  $dispatch("get-chart-type", { chartType: selectedChartType.name, icon: selectedChartType.icon });
                '
              >
                <i class="bi me-1" :class="chartType.icon"></i>
                <span x-text="chartType.name"></span>
              </a>
            </li>
          </template>
        </ul>
      </div>

      <div 
        class="dropdown"
        x-data="reportChart"
        @get-chart-options.window='
          selectedType = $event.detail.type;
          selectedChartType.name = $event.detail.chartType;
        '
      >
        <button 
          class="btn btn-primary rounded-3 dropdown-toggle" 
          type="button" 
          data-bs-toggle="dropdown" 
          aria-expanded="false"
        >
          <i class="bi bi-download me-1"></i>
          <span>Download</span>
        </button>
        <ul class="dropdown-menu p-2">
          <li>
            <a 
              class="dropdown-item text-white" 
              role="button"
              @click="downloadAsImage()"
            >
              <i class="bi bi-image me-1"></i>
              <span>Image</span>
            </a>
          </li>
          <li>
            <a 
              class="dropdown-item text-white" 
              role="button"
              @click="downloadAsPdf()"
            >
              <i class="bi bi-file-pdf me-1"></i>
              <span>PDF</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div 
    class="row mt-4" 
    x-data="reportChart" 
    x-init='
      project = <?=isset($projects[0]['id']) ? json_encode($projects[0]['id']) : json_encode('') ?>;
      $watch("project", value => getChart($refs.chart));
      $watch("selectedChartType.name", value => changeChart($refs.chart));
    '
    @get-project.window='project = $event.detail'
    @get-chart-type.window='
      selectedChartType.name = $event.detail.chartType
      selectedChartType.icon = $event.detail.icon
    '
  >
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12">
      <div class="list-group report-types rounded-3 me-5 list-group-flush">
        <template x-for="type in types">
          <button 
            type="button" 
            class="list-group-item list-group-item-action text-white rounded-3 border-0 mb-2 text-capitalize"
            x-text="type" 
            :class="selectedType == type && 'active'"
            @click='
              selectedType = type
              getChart($refs.chart)
              $dispatch("get-chart-options", { type: selectedType, chartType: selectedChartType.name })
            '
          ></button>  
        </template>
      </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
      <div class="text-center p-5 d-none" x-ref="empty">
        <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
        <h5 class="mt-5">There is nothing here...</h5>
      </div>
      <div x-ref="chartContainer">
        <canvas x-init="initChart($el)" x-ref="chart"></canvas>
      </div>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
