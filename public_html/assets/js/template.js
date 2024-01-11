(function () {
	'use strict';

	const toggleSidebarButton = document.querySelector('.toggle-sidebar-btn');
	toggleSidebarButton.addEventListener('click', () => {
		document.querySelector('body').classList.toggle('toggle-sidebar');
	});

	GLightbox({
		width: 1200,
		height: 900,
	});

	const toastElList = document.querySelectorAll('.toast');
	const toastList = [...toastElList].map((toastEl) => {
		const toast = new bootstrap.Toast(toastEl, {});
		toast.show();
	});
})();
