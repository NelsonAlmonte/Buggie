<div class="container-fluid">
  <h2>Reports</h2>
  <?php if(session()->getFlashdata('message') !== null): ?>
  <div class="alert alert-<?= session()->getFlashdata('color') ?> d-flex align-items-center my-4" role="alert">
    <i class="bi bi-check-circle flex-shrink-0 me-2"></i>
    <div>
      <?= session()->getFlashdata('message') ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="row mt-4" x-data="reportChart">
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
      <canvas x-init="initChart($el)" x-ref="chart"></canvas>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
