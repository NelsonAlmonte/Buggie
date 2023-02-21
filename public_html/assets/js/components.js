document.addEventListener('alpine:init', () => {
	Alpine.data('searchSelect', () => ({
		query: '',
		items: [],
		selectedItems: [],
		async getItems(controller, method) {
			const payload = {
				url: `/${controller}/${method}`,
				query: this.query,
				unwanted: this.selectedItems,
			};

			const [response, error] = await useFetch(payload);

			this.items = response.data;
		},
		selectItem(selectedItem) {
			if (this.selectedItems.includes(selectedItem)) return;
			this.selectedItems.push(selectedItem);
			this.items = [];
			this.query = '';
		},
		removeItem(selectedItem) {
			this.selectedItems = this.selectedItems.filter(
				(project) => project.id !== selectedItem.id
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
			reader.onload = () => (this.imageUrl = reader.result);
			reader.readAsDataURL(image);
		},
	}));

	Alpine.data('filesPreview', () => ({
		documents: [],
		renderFiles(event) {
			const target = event.target;
			const files = Array.from(target.files);
			const fileReaders = [];
			if (files.length <= 0) return;
			files.forEach(file => {
				const fileReader = new FileReader();
				fileReaders.push(fileReader);
				fileReader.onload = (reader) => {
					const result = reader.target.result;
					const document = {
						result: result,
						name: file.name,
						type: file.type,
					};
					this.documents.push(document);
					console.log(file);
				}
				fileReader.readAsDataURL(file);
			});
		},
	}));

	async function useFetch(payload) {
		try {
			const csrfSelector = document.querySelector('.csrf');
			const csrfName = csrfSelector.attributes.name.value;
			const csrfHash = csrfSelector.value;
			payload[csrfName] = csrfHash;

			const source = await fetch(payload.url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
				body: JSON.stringify(payload),
			});
			const response = await source.json();
			csrfSelector.value = response.token;
			return [response, null];
		} catch (error) {
			console.log(error);
			return [null, error];
		}
	}
});
