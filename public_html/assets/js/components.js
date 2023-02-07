document.addEventListener('alpine:init', () => {
	Alpine.data('assignProjects', () => ({
		url: '/project/searchProjects',
		query: '',
		projects: [],
		selectedProjects: [],
		async getProjects() {
			const csrfSelector = document.querySelector('.csrf');
			const csrfName = csrfSelector.attributes.name.value;
			const csrfHash = csrfSelector.value;

			const data = {
				[csrfName]: csrfHash,
				csrfSelector: csrfSelector,
				query: this.query,
				unwanted: this.selectedProjects,
			};

			const source = await fetch(this.url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
				body: JSON.stringify(data),
			});

			const response = await source.json();
			this.projects = response.data;
			csrfSelector.value = response.token;
		},
		selectProject(selectedProject) {
			if (this.selectedProjects.includes(selectedProject)) return;
			this.selectedProjects.push(selectedProject);
			this.projects = [];
			this.query = '';
		},
		removeProject(selectedProject) {
			this.selectedProjects = this.selectedProjects.filter(
				project => project.id !== selectedProject.id
			);
		},
	}));

	Alpine.data('imagePreview', () => ({
		imageUrl: '',
		renderImage(event) {
			const target = event.target;
			if (target.files.length <= 0) return;
			const image = target.files[0];
			const reader = new FileReader();
			reader.onload = () => this.imageUrl = reader.result;
			reader.readAsDataURL(image);
		}
	}));
});
