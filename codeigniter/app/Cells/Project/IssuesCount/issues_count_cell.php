<div>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a class="text-decoration-none" href="<?=site_url('issue/' . $slug)?>">
      <div class="bg-dominant rounded-pill text-white px-3 py-2">
        <?=esc($issuesCount['totalIssues'])?> total issues
      </div>
    </a>
    <a class="text-decoration-none text-warning" href="<?=site_url('issue/' . $slug)?>"><?=esc($issuesCount['openIssues'])?> open issues</a>
  </div>
  <div>
    <small class="text-white">Progress</small>
    <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="<?=esc($progress)?>" aria-valuemin="0"
      aria-valuemax="100">
      <div class="progress-bar" style="width: <?=esc($progress)?>%"></div>
    </div>
    <small class="float-end text-white"><?=esc($progress)?>%</small>
  </div>
</div>