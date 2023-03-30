<nav class="mt-4" aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item <?=$previousPage == 0 ? 'disabled' : '' ;?>">
      <a class="page-link" href="<?=$url['previousPage']?>">
        <i class="bi bi-arrow-left me-1"></i>
        <span>Prev</span>
      </a>
    </li>
    <li class="page-item <?=$nextPage > $totalPages ? 'disabled' : '' ;?>">
      <a class="page-link" href="<?=$url['nextPage']?>">
        <span>Next</span>
        <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </li>
  </ul>
</nav>