<?php if (session()->getFlashdata('message') !== null) : ?>
  <div 
    x-data="toast" 
    x-init='
      message = <?=json_encode(session()->getFlashdata("message"))?>;
      bootstrapIcon = <?=json_encode(session()->getFlashdata("icon"))?>;
      bootstrapColor = <?=json_encode(session()->getFlashdata("color"))?>;
      showToast();
    '
  >
  </div>
<?php endif; ?>