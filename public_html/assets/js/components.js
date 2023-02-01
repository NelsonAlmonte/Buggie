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
			const selectedProject = project;
			this.selectedProjects.push(selectedProject);
		},
		removeProject(selectedProject) {
			const projectToBeRemoved = selectedProject;
			this.selectedProjects = this.selectedProjects.filter(
				selectedProject => selectedProject.id !== projectToBeRemoved.id
			);
		},
	}));
});
