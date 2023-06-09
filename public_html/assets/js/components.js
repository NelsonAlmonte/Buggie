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

			if (this.query.length > 0) {
				const [response, error] = await useFetch(payload);
				this.items = response.data;
			}
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
		filesPreview: [],
		inputFiles: [],
		renderFiles(event) {
			const input = event.target;
			const filesFromInput = Array.from(input.files);
			if (filesFromInput.length <= 0) this.addToInput(input);
			filesFromInput.forEach((fileFromInput, index) => {
				const fileReader = new FileReader();
				fileReader.onload = (reader) => {
					const file = {
						uuid: `${index}-${fileFromInput.name}`,
						result: reader.target.result,
						name: this.formatFileName(fileFromInput.name),
						type: fileFromInput.type.split('/')[1],
					};
					this.filesPreview.push(file);
					this.inputFiles.push(fileFromInput);
					this.addToInput(input);
				};
				fileReader.readAsDataURL(fileFromInput);
			});
		},
		removeFile(fileIndex, input, selectedFile) {
			this.filesPreview = this.filesPreview.filter(
				(file) => file.uuid !== selectedFile.uuid
			);
			this.inputFiles = this.inputFiles.filter(
				(file, index) => fileIndex !== index
			);
			this.addToInput(input);
		},
		addToInput(input) {
			const dataTransfer = new DataTransfer();
			this.inputFiles.forEach((file) => dataTransfer.items.add(file));
			input.files = dataTransfer.files;
		},
		formatFileName(name) {
			const split = name.split('.');
			let fileName = split[0];
			const extension = split[1];
			if (fileName.length > 15) fileName = fileName.substring(0, 15);
			return `${fileName}.${extension}`;
		},
	}));

	Alpine.data('froalaEditor', () => ({
		initFroala(el) {
			let { csrfName, csrfHash } = getCsrf();
			const editor = new FroalaEditor(el, {
				toolbarButtons: {
					moreText: {
						buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'quote'],
						buttonsVisible: 5,
					},
					moreParagraph: {
						buttons: ['formatOLSimple', 'formatUL'],
					},
					moreRich: {
						buttons: ['insertLink', 'insertImage', 'insertHR'],
						buttonsVisible: 4,
					},
				},
				quickInsertEnabled: false,
				imageUploadURL: '/issue/uploadIssueImage',
				imageUploadParams: {
					[csrfName]: csrfHash,
				},
				imageUploadMethod: 'POST',
				events: {
					'image.uploaded': (response) => {
						const parsedResponse = JSON.parse(response);
						getCsrf(parsedResponse.token);
						editor.opts.imageUploadParams[csrfName] = parsedResponse.token;
					},
					'image.error': (error, response) => {
						console.log(error);
						console.log(response);
					},
					'image.removed': async (image) => {
						if (image[0].classList.contains('fr-uploading')) return;

						const payload = {
							url: `/issue/deleteIssueImage`,
							image: image[0].src,
						};
			
						const [response, error] = await useFetch(payload);
						
						getCsrf(response.token);
						editor.opts.imageUploadParams[csrfName] = response.token;
						console.log(response);
						console.log(error);
					},
				},
			});
		},
	}));

	Alpine.data('deleteItem', () => ({
		item: '',
		url: '',
		key: '',
		async deleteItem(element) {
			const payload = {
				url: this.url,
				[this.key]: this.item,
			};

			const [response, error] = await useFetch(payload);
			console.log(response);
			if (response.status === 0) element.remove();
		},
	}));

	Alpine.data('reportChart', () => ({
		initChart(el) {
			new Chart(el, {
				type: 'doughnut',
				data: {
					labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
					datasets: [{
						label: '# of Votes',
						data: [12, 19, 3, 5, 2, 3],
						borderWidth: 1
					}]
				},
				// options: {
				// 	scales: {
				// 		y: {
				// 			beginAtZero: true
				// 		}
				// 	}
				// }
			});
		},
		getChart(el) {
			const chart = Chart.getChart(el);
			const response = {
				labels: ['Moquito', 'Sheira', 'Dengue', 'Batea'],
				data: [1, 4, 9, 3]
			};
			chart.data.labels = response.labels;
			chart.data.datasets[0].data = response.data;
			chart.update();
		}
	}));

	async function useFetch(payload) {
		try {
			const { csrfName, csrfHash } = getCsrf();
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
			getCsrf(response.token);
			return [response, null];
		} catch (error) {
			console.log(error);
			return [null, error];
		}
	}

	function getCsrf(csrfValue) {
		const csrfSelector = document.querySelector('.csrf');
		if (csrfValue) csrfSelector.value = csrfValue;
		const csrfName = csrfSelector.attributes.name.value;
		const csrfHash = csrfSelector.value;
		return { csrfName: csrfName, csrfHash: csrfHash };
	}
});
