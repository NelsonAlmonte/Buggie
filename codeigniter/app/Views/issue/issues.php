<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Issues <span class="text-primary">on <?=esc($project['name'])?></span>
    </h2>
    <a class="btn btn-rounded btn-primary px-3" type="button" href="<?=site_url('issue/' . $slug . '/add')?>">
      Add issue
    </a>
  </div>
  <div class="card bg-complementary border border-0 rounded-4 mt-4">
    <div class="card-header d-flex justify-content-start align-items-center bg-complementary rounded-top-4 px-4 py-3">
      <ul class="nav">
        <li class="nav-item me-2">
          <a class="nav-link issue-filter d-flex justify-content-center align-items-center ps-0 <?=isset($_GET['status']) && $_GET['status'] == 'open' ? 'active' : '' ;?>" href="?status=open">
            <span class="me-1">Open</span> 
            <span class="badge rounded-pill text-bg-secondary"><?=count($openIssues)?></span>
          </a>
        </li>
        <li class="nav-item me-2">
          <a class="nav-link issue-filter d-flex justify-content-center align-items-center ps-0 <?=isset($_GET['status']) && $_GET['status'] == 'closed' ? 'active' : '' ;?>" href="?status=closed">
            <span class="me-1 ">Closed</span> 
            <span class="badge rounded-pill text-bg-secondary"><?=count($closedIssues)?></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link issue-filter d-flex justify-content-center align-items-center ps-0 <?=!isset($_GET['status']) ? 'active' : '' ;?>" href="<?=site_url('issue/' . $slug)?>">
            <span class="me-1 ">All</span> 
            <span class="badge rounded-pill text-bg-secondary"><?=count($projectIssues)?></span>
          </a>
        </li>
      </ul>
      <form class="flex-grow-1" action="<?=site_url('issue/' . $slug)?>" method="get">
        <div class="input-group">
          <input type="text" class="form-control bg-dominant border-0" placeholder="Search by issue title..." name="title" value="<?=isset($_GET['title']) ? $_GET['title'] : '' ;?>" aria-label="Search" aria-describedby="search" autocomplete="off">
          <a class="btn bg-dominant <?=count(array_keys($_GET)) > 0 ? '' : 'd-none' ;?>" href="<?=site_url('issue/' . $slug)?>" type="button"><i class="bi bi-x-lg text-white"></i></a>
          <button class="btn bg-dominant" type="submit"><i class="bi bi-search text-white"></i></button>
          <button class="btn bg-dominant" type="button" data-bs-toggle="modal"
          data-bs-target="#issues-filter-modal"><i class="bi bi-funnel text-white"></i></button>
        </div>
      </form>
    </div>
    <div class="card-body p-0">
      <?php if(!empty($issues)): ?>
        <?php foreach($issues as $key => $issue): ?>
        <div 
          class="issue-item d-flex justify-content-between align-items-center py-2 px-4 <?=$key != 0 ? 'border-top' : ''?>"
          x-data
          x-ref="issue"
        >
          <div>
            <a class="text-accent text-decoration-none fw-bold d-block" href="<?=site_url('issue/' . $slug . '/issue/' . $issue['id'])?>"><?=esc($issue['title'])?></a>
            <small>
              <a class="text-accent text-decoration-none fw-bold" href="<?=site_url('issue/' . $slug . '/issue/' . $issue['id'])?>"> #<?=esc($issue['id'])?></a>
              opened on <span class="text-accent text-decoration-none fw-bold"><?=date_format(date_create($issue['start_date']), 'M j, Y')?></span> by
              <?php if($issue['reporter_is_active'] != '0'): ?> 
                <a class="text-accent text-decoration-none fw-bold" href="<?=site_url('collaborator/view/' . $issue['reporter_id'])?>">
                  <?=esc($issue['reporter_name'])?>
                </a>
              <?php else: ?>
                <span class="fw-bold">
                  <?=esc($issue['reporter_name'])?>
                </span>
              <?php endif; ?>
              <?php if(!empty($issue['assignee'])): ?>
                assigned to 
                <?php if($issue['assignee_is_active'] != '0'): ?>
                  <a class="text-accent text-decoration-none fw-bold" href="<?=site_url('collaborator/view/' . $issue['assignee_id'])?>">
                    <?=esc($issue['assignee_name'])?>
                  </a>
                <?php else: ?>
                  <span class="fw-bold">
                    <?=esc($issue['assignee_name'])?>
                  </span>
                <?php endif;?>
              <?php endif; ?>
            </small>
          </div>
          <div>
            <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
              style="color: #<?=esc($issue['classification_color'])?>; background-color: #<?=esc($issue['classification_color'])?>1a; border: 1px solid #<?=esc($issue['classification_color'])?>;">
              <?=esc($issue['classification_name'])?>
            </div>
            <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
              style="color: #<?=esc($issue['severity_color'])?>; background-color: #<?=esc($issue['severity_color'])?>1a; border: 1px solid #<?=esc($issue['severity_color'])?>;">
              <?=esc($issue['severity_name'])?>
            </div>
            <div class="status-badge d-inline-block text-capitalize mx-1 mb-2 mb-xl-0"
              style="color: #<?=esc($issue['status_color'])?>; background-color: #<?=esc($issue['status_color'])?>1a; border: 1px solid #<?=esc($issue['status_color'])?>;">
              <?=esc($issue['status_name'])?>
            </div>
            <?php if (session()->get('id') == $issue['reporter_id'] || in_array('issue', session()->get('auth')['permissions'])): ?>
            <a role="button" class="btn btn-rounded" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots"></i>
            </a>
            <ul class="dropdown-menu p-2">
              <li>
                <a class="dropdown-item text-white"
                  href="<?=site_url('issue/' . $slug . '/edit/' . $issue['id'])?>">
                  <div class="d-inline-block">
                    <i class="bi bi-pencil"></i>
                  </div>
                  <span>Edit</span>
                </a>
              </li>
              <?php if(empty($issue['assignee'])): ?>
              <li>
                <button 
                  class="dropdown-item text-white" 
                  type="button" 
                  data-bs-toggle="modal" 
                  data-bs-target="#assign-issue-modal"
                  @click="$dispatch('get-issue-id', { issue: <?=$issue['id']?> })"
                >
                  <div class="d-inline-block">
                    <i class="bi bi-person-plus"></i>
                  </div>
                  <span>Assign</span>
                </button>
              </li>
              <?php endif; ?>
              <li>
                <button 
                  class="dropdown-item text-white" 
                  x-data="deleteItem"
                  x-init='
                    options = {
                      payload: {
                        issue: <?=json_encode($issue)?>
                      },
                      alert: {
                        title: "Delete this issue?",
                        text: "Are you sure you want to delete this issue?",
                        confirmButtonText: "Yes, delete it"
                      },
                      toast: {
                        text: "Issue deleted successfully"
                      },
                      element: $refs.issue
                    },
                    url = "/issue/deleteIssue"
                  ' 
                  @click="deleteItem()"
                >
                  <div class="d-inline-block">
                    <i class="bi bi-trash"></i>
                  </div>
                  <span>Delete</span>
                </button>
              </li>
            </ul>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
      <div class="text-center m-5 p-4">
        <img class="card-empty-icon" src="<?=PATH_TO_VIEW_ASSETS_IMAGE . EMPTY_IMAGE?>" alt="empty">
        <h5 class="mt-5">There is nothing here...</h5>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<input class="csrf" type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<?= view_cell('App\Cells\Issue\IssueFilterModal\IssueFilterModal::render', ['slug' => $slug, 'project' => $project]); ?>
<?= view_cell('App\Cells\Shared\Pagination\Pagination::render', ['currentRecords' => count($issues)]); ?>
<?= view_cell('App\Cells\Issue\AssignIssue\AssignIssue::render', ['project' => $project]); ?>
