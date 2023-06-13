<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
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
        <h2>Reports on <span class="text-primary"><?=$projects[0]['name']?></span></h2>
      <?php endif; ?>
    </div>
    <div class="d-flex align-items-center justify-content-start">
      <div 
        class="dropdown me-2"
        x-data='{ chartType: "pie", icon: "bi-pie-chart" }'
      >
        <button 
          class="btn btn-primary rounded-3 dropdown-toggle text-capitalize" 
          type="button" 
          data-bs-toggle="dropdown" 
          aria-expanded="false"
        >
          <i class="bi me-1" :class="icon"></i>
          <span x-text="chartType"></span>
        </button>
        <ul class="dropdown-menu p-2">
          <li>
            <a 
              class="dropdown-item text-white" 
              role="button"
              @click='
                chartType = "pie";
                icon = "bi-pie-chart";
                $dispatch("get-chart-type", { chartType: chartType, icon: icon })
              '
            >
              <i class="bi bi-pie-chart me-1"></i>
              <span>Pie</span>
            </a>
          </li>
          <li>
            <a 
              class="dropdown-item text-white" 
              role="button"
              @click='
                chartType = "doughnut";
                icon = "bi-pie-chart-fill";
                $dispatch("get-chart-type", { chartType: chartType, icon: icon })
              '
            >
              <i class="bi bi-pie-chart-fill me-1"></i>
              <span>Doughnut</span>
            </a>
          </li>
          <li>
            <a 
              class="dropdown-item text-white" 
              role="button"
              @click='
                chartType = "bar";
                icon = "bi-bar-chart";
                $dispatch("get-chart-type", { chartType: chartType, icon: icon })
              '
            >
              <i class="bi bi-bar-chart me-1"></i>
              <span>Bar</span>
            </a>
          </li>
        </ul>
      </div>

      <div 
        class="dropdown"
        x-data
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
            >
              <i class="bi bi-image me-1"></i>
              <span>Image</span>
            </a>
          </li>
          <li>
            <a 
              class="dropdown-item text-white" 
              role="button"
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
      project = <?=json_encode($projects[0]['id'])?>;
      $watch("project", value => getChart($refs.chart));
      $watch("chartType", value => changeChart($refs.chart));
    '
    @get-project.window='project = $event.detail'
    @get-chart-type.window='
      chartType = $event.detail.chartType; 
      icon = $event.detail.icon
    '
  >
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12">
      <div class="list-group report-types rounded-3 me-5 list-group-flush">
        <button 
          type="button" 
          class="list-group-item list-group-item-action text-white rounded-3 border-0 mb-2" 
          :class="type == 'assignee' && 'active'"
          @click='
            type = "assignee"
            getChart($refs.chart)
          '
        >Assignee</button>
        <button 
          type="button" 
          class="list-group-item list-group-item-action text-white rounded-3 border-0 mb-2" 
          :class="type == 'reporter' && 'active'"
          @click='
            type = "reporter"
            getChart($refs.chart)
          '
        >Reporter</button>
        <button 
          type="button" 
          class="list-group-item list-group-item-action text-white rounded-3 border-0 mb-2" 
          :class="type == 'status' && 'active'"
          @click='
            type = "status"
            getChart($refs.chart)
          '
        >Status</button>
        <button 
          type="button" 
          class="list-group-item list-group-item-action text-white rounded-3 border-0 mb-2" 
          :class="type == 'classification' && 'active'"
          @click='
            type = "classification"
            getChart($refs.chart)
          '
        >Classification</button>
        <button 
          type="button" 
          class="list-group-item list-group-item-action text-white rounded-3 border-0 mb-2" 
          :class="type == 'severity' && 'active'"
          @click='
            type = "severity"
            getChart($refs.chart)
          '
        >Severity</button>
      </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
      <div class="text-center p-5 d-none" x-ref="empty">
        <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
        <h5 class="mt-5">There is nothing here...</h5>
      </div>
      <canvas x-init="initChart($el)" x-ref="chart"></canvas>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
