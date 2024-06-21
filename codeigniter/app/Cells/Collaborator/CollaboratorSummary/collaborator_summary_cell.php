<div class="d-flex justify-content-between">
  <div class="text-center" title="Projects assigned">
    <i class="bi bi-briefcase text-white fs-4"></i>
    <small class="d-block">Projects</small>
    <h5 class="fw-bold mb-0"><?=esc(count($projects))?></h5>
  </div>
  <div class="text-center" title="Issues assigned">
    <i class="bi bi-bug text-white fs-4"></i>
    <small class="d-block">Issues</small>
    <h5 class="fw-bold mb-0"><?=esc(count($issues))?></h5>
  </div>
  <div class="text-center" title="Projects assigned and closed">
    <i class="bi bi-bug-fill text-white fs-4"></i>
    <small class="d-block">Closed</small>
    <h5 class="fw-bold mb-0"><?=esc(count($closedIssues))?></h5>
  </div>
</div>