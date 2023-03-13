(function () {
	'use strict';

  const toggleSidebarButton = document.querySelector('.toggle-sidebar-btn');
  toggleSidebarButton.addEventListener('click', () => {
    document.querySelector('body').classList.toggle('toggle-sidebar');
  });

  const lightbox = GLightbox({
    width: 1200,
    height: 900,
  });
})();
