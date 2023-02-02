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
		selectProject(project) {
			if (!this.selectedProjects.includes(project))
				this.selectedProjects.push(project);
		},
		removeProject(selectedProject) {
			this.selectedProjects = this.selectedProjects.filter(
				project => project.id !== selectedProject.id
			);
		},
	}));
});
