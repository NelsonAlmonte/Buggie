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
		files: [],
		renderFiles(event) {
			const input = event.target;
			const filesFromInput = Array.from(input.files);
			const fileReaders = [];
			if (filesFromInput.length <= 0) return;
			filesFromInput.forEach((fileFromInput, index) => {
				const fileReader = new FileReader();
				fileReaders.push(fileReader);
				fileReader.onload = (reader) => {
					const result = reader.target.result;
					const file = {
						id: index,
						result: result,
						name: fileFromInput.name,
						type: fileFromInput.type,
					};
					this.files.push(file);
				};
				fileReader.readAsDataURL(fileFromInput);
			});
			console.log(input.files);
			// this.addToInput(0, input)
		},
		removeFile(fileIndex, input, selectedFile) {
			// const dataTransfer = new DataTransfer();
			// const filesFromInput = input.files;
			// for (let i = 0; i < filesFromInput.length; i++) {
			// 	const file = filesFromInput[i];
			// 	if (fileIndex !== i) {
			// 		dataTransfer.items.add(file);
			// 	}
			// }
			// input.files = dataTransfer.files;
			this.files = this.files.filter((file) => file.id !== selectedFile.id);
			this.addToInput(fileIndex, input);
		},
		addToInput(fileIndex, input) {
			console.log(input.files);
			const dataTransfer = new DataTransfer();
			const filesFromInput = input.files;
			for (let i = 0; i < filesFromInput.length; i++) {
				const file = filesFromInput[i];
				if (fileIndex !== i)
					dataTransfer.items.add(file);
			}
			input.files = dataTransfer.files;
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
