  <div 
    x-data="toast" 
    x-init='
      message = "Action done successfully";
      bootstrapIcon = <?=json_encode(session()->getFlashdata("icon"))?>;
      bootstrapColor = "success";
      showToast();
    '
  >
  </div>