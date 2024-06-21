<?php if (session()->getFlashdata('message') !== null) : ?>
  <div 
    x-data="alert" 
    x-init='
      text = <?=json_encode(session()->getFlashdata("message"))?>;
      sweetAlertIcon = <?=json_encode(session()->getFlashdata("icon"))?>;
      bootstrapClassColor = <?=json_encode(session()->getFlashdata("color"))?>;
      bootstrapHexColor = <?=json_encode(session()->getFlashdata("hexColor"))?>;
      showToast();
    '
  >
  </div>
<?php endif; ?>