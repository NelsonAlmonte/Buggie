<?php if (session()->getFlashdata('message') !== null) : ?>
  <div class="toast-container p-3 bottom-0 start-50 translate-middle-x"" id="toastPlacement">
    <div class="toast border-<?= session()->getFlashdata('color') ?> rounded-5 shadow-none py-1 px-2">
      <div class="toast-body">
        <div class="d-flex justify-content-start align-items-center">
          <i class="<?= session()->getFlashdata('icon') ?> text-<?= session()->getFlashdata('color') ?> flex-shrink-0 me-3" style="font-size: 2rem;"></i>
          <div>
            <strong>Heads up!</strong><br>
            <strong class="text-<?= session()->getFlashdata('color') ?>"><?= session()->getFlashdata('message') ?></strong>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
