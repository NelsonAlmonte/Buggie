<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buggie</title>
  <link href="/assets/third-party/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/third-party/froala/froala_editor.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link href="/assets/css/template.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
  <header id="header" class="header fixed-top d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <a href="<?=site_url('home')?>" class="logo d-flex align-items-center">
          <i class="bi bi-bug me-2"></i>
          <span class="d-none d-lg-block">Buggie</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
      </div>

      <?php $uri = service('uri')?>
      <nav class="header-nav ms-3 <?=empty($uri->getSegment(2)) ? 'd-none' : 'd-none d-sm-block' ;?>">
        <ul class="d-flex align-items-center">
          <li class="nav-item me-4">
            <a class="nav-link" href="<?=site_url('project/' . $uri->getSegment(2) . '/dashboard')?>">
              Dashboard
            </a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="<?=site_url('issue/' . $uri->getSegment(2))?>">
              Issues
            </a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="<?=site_url('report/' . $uri->getSegment(2))?>">
              Reports
            </a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="<?=site_url('document/' . $uri->getSegment(2))?>">
              Files
            </a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="<?=site_url('collaborator/' . $uri->getSegment(2))?>">
              Collaborators
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <nav class="header-nav">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown d-none d-md-block pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="dropdown-toggle ps-2">Logged user</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-2">
            <li class="dropdown-header">
              <h6>Kevin Anderson</h6>
              <span>Web Designer</span>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center text-white" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item dropdown pe-3 <?=empty($uri->getSegment(2)) ? 'd-none' : 'd-block d-md-none' ;?>">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="ps-2"><i class="bi bi-briefcase"></i></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-2">
            <li class="dropdown-header">
              <h6>Projec 1 name</h6>
              <span>Project menu</span>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('project/' . $uri->getSegment(2) . '/dashboard')?>">
                <i class="bi bi-columns-gap"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('issue/' . $uri->getSegment(2))?>">
                <i class="bi bi-bug"></i>
                <span>Issues</span>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('report/' . $uri->getSegment(2))?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Reports</span>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('document/' . $uri->getSegment(2))?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Files</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center text-white" href="<?=site_url('collaborator/' . $uri->getSegment(2))?>">
                <i class="bi bi-people"></i>
                <span>Collaborators</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item dropdown d-block d-md-none pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="ps-2"><i class="bi bi-person-circle"></i></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-2">
            <li class="dropdown-header">
              <h6>Kevin Anderson</h6>
              <span>Web Designer</span>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item d-flex align-items-center text-white" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center text-white" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('home')?>">
          <i class="bi bi-house"></i>
          <span>Home</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('project')?>">
          <i class="bi bi-briefcase"></i>
          <span>Projects</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-calendar3"></i>
          <span>Calendar</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-file-earmark-bar-graph"></i>
          <span>Reports</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('collaborator')?>">
          <i class="bi bi-people"></i>
          <span>Collaborators</span>
        </a>
      </li>

      <li class="nav-heading mt-4">Your projects</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-briefcase"></i>
          <span>Project 1</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-briefcase"></i>
          <span>Project 2</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-briefcase"></i>
          <span>Lorem Ipsum</span>
        </a>
      </li>
    </ul>
  </aside>

  <main id="main" class="main">
