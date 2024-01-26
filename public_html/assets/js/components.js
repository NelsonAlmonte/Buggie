document.addEventListener('alpine:init', () => {
	Alpine.data('searchSelect', () => ({
		query: '',
		items: [],
		selectedItems: [],
		options: {},
		destroy() {
			this.options = {};
		},
		async getItems() {
			const payload = {
				url: `${this.options.url}`,
				method: 'POST',
				data: {
					query: this.query,
					unwanted: this.selectedItems,
				},
			};

			if (Object.keys(this.options).length > 1)
				payload.data[Object.keys(this.options).pop()] = Object.values(
					this.options
				).pop();

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
		removeItem(itemToBeRemoved) {
			this.selectedItems = this.selectedItems.filter(
				(selectedItem) => selectedItem.id !== itemToBeRemoved.id
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
				imageUploadURL: '/v1/issue/uploadIssueImage',
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
						showAlert({
							isToast: true,
							text: 'Unexpected error ocurred',
							sweetAlertIcon: 'error',
							bootstrapClassColor: 'danger',
							bootstrapHexColor: '#dc3545',
						});
					},
					'image.removed': async (image) => {
						if (image[0].classList.contains('fr-uploading')) return;

						const payload = {
							url: `/v1/issue/deleteIssueImage`,
							method: 'DELETE',
							data: {
								image: image[0].src,
							},
						};

						const [response, error] = await useFetch(payload);

						getCsrf(response.token);

						if (response.status === 0) {
							editor.opts.imageUploadParams[csrfName] = response.token;
						} else {
							showAlert({
								isToast: true,
								text: 'Unexpected error ocurred',
								sweetAlertIcon: 'error',
								bootstrapClassColor: 'danger',
								bootstrapHexColor: '#dc3545',
							});
						}
						console.log(response);
						console.log(error);
					},
				},
			});
		},
	}));

	Alpine.data('deleteItem', () => ({
		url: '',
		options: {},
		destroy() {
			this.options = {};
		},
		async deleteItem() {
			const payload = {
				url: this.url,
				method: 'DELETE',
				data: this.options.payload,
			};

			const confirmationOptions = {
				type: 'alert',
				title: this.options.alert.title,
				text: this.options.alert.text,
				icon: 'warning',
				confirmButtonText: this.options.alert.confirmButtonText,
			};

			const confirmationStatus = await showAlert(confirmationOptions);
			if (!confirmationStatus.isConfirmed) return;

			const [response, error] = await useFetch(payload);
			console.log(response);

			if (response.status === 0) {
				this.options.element.remove();
				showAlert({
					isToast: true,
					text: this.options.toast.text,
					sweetAlertIcon: 'success',
					bootstrapClassColor: 'success',
					bootstrapHexColor: '#198754',
				});
			} else {
				showAlert({
					isToast: true,
					text: 'Unexpected error ocurred',
					sweetAlertIcon: 'error',
					bootstrapClassColor: 'danger',
					bootstrapHexColor: '#dc3545',
				});
			}
		},
	}));

	Alpine.data('reportChart', () => ({
		url: '/v1/report/getReport',
		selectedType: 'assignee',
		types: ['assignee', 'reporter', 'status', 'classification', 'severity'],
		project: '',
		selectedProject: '',
		chartTypes: [
			{
				name: 'pie',
				icon: 'bi-pie-chart',
			},
			{
				name: 'doughnut',
				icon: 'bi-pie-chart-fill',
			},
			{
				name: 'bar',
				icon: 'bi-bar-chart',
			},
		],
		selectedChartType: {
			name: 'pie',
			icon: 'bi-pie-chart',
		},
		async initChart(el) {
			const payload = {
				url: `${this.url}?type=${this.selectedType}&project=${this.project}`,
				method: 'GET',
				data: null,
			};

			const [response, error] = await useFetch(payload);

			if (!response.data.labels.length) {
				this.$refs.chartContainer.classList.add('d-none');
				this.$refs.empty.classList.remove('d-none');
			} else {
				this.$refs.chartContainer.classList.remove('d-none');
				this.$refs.empty.classList.add('d-none');
			}

			const colors = this.generateHexColor(response.data.data.length);

			new Chart(el, {
				type: this.selectedChartType.name,
				data: {
					labels: response.data.labels,
					datasets: [
						{
							label: 'Issues',
							data: response.data.data,
							backgroundColor: colors,
							borderWidth: 1,
						},
					],
				},
				options: {
					color: '#fff',
				},
			});
		},
		async getChart(el) {
			const chart = Chart.getChart(el);

			const payload = {
				url: `${this.url}?type=${this.selectedType}&project=${this.project}`,
				method: 'GET',
				data: null,
			};

			const [response, error] = await useFetch(payload);

			if (!response.data.labels.length) {
				this.$refs.chartContainer.classList.add('d-none');
				this.$refs.empty.classList.remove('d-none');
			} else {
				this.$refs.chartContainer.classList.remove('d-none');
				this.$refs.empty.classList.add('d-none');
			}

			const colors = this.generateHexColor(response.data.data.length);
			chart.data.labels = response.data.labels;
			chart.data.datasets[0].data = response.data.data;
			chart.data.datasets[0].backgroundColor = colors;
			chart.update();
		},
		async changeChart(el) {
			const chart = Chart.getChart(el);
			chart.destroy();
			this.initChart(el);
		},
		downloadAsImage() {
			const imageAnchor = document.createElement('a');
			const chart = document.querySelector('canvas');
			imageAnchor.download = `${this.generateFileName()}.jpg`;
			imageAnchor.href = chart.toDataURL('image/jpeg', 1);
			imageAnchor.click();
		},
		downloadAsPdf() {
			window.jsPDF = window.jspdf.jsPDF;
			const chart = document.querySelector('canvas');
			const img = chart.toDataURL('image/png', chart.width, chart.height);
			const heightRatio = chart.height / chart.width;
			const pdf = new jsPDF('p', 'pt', 'a4');
			const width = pdf.internal.pageSize.width;
			const height = width * heightRatio;
			pdf.addImage(img, 'PNG', 10, 10, width, height);
			pdf.save(`${this.generateFileName()}.pdf`);
		},
		generateFileName() {
			const date = new Date();
			const currentDate = `${date.getFullYear()}-${date.getMonth()}-${date.getDay()}`;
			const currentTime = `${date.getHours()}${date.getMinutes()}${date.getSeconds()}`;
			return `${this.selectedChartType.name}_${this.selectedType}_${currentDate}-${currentTime}`;
		},
		generateHexColor(size) {
			const colors = [];
			for (let index = 0; index < size; index++) {
				let code = (Math.random() * 0xfffff * 1000000).toString(16);
				let color = `#${code.slice(0, 6)}`;
				colors.push(color);
			}
			return colors;
		},
	}));

	Alpine.data('calendar', () => ({
		url: '/v1/calendar/getIssues',
		projectId: '',
		projectSlug: '',
		async initCalendar(el) {
			const response = await this.getIssues();
			const events = response.events;
			const initialDate =
				events.length > 0 ? events[events.length - 1].start : new Date();
			const calendar = new FullCalendar.Calendar(el, {
				initialDate: initialDate,
				initialView: 'dayGridMonth',
				themeSystem: 'bootstrap5',
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,listWeek',
				},
				height: 'auto',
				navLinks: true,
				selectable: true,
				selectMirror: true,
				nowIndicator: true,
				events: events,
				eventClick: function (info) {
					info.jsEvent.preventDefault();
					window.open(info.event.url);
				},
			});
			calendar.render();
		},
		async getIssues() {
			const payload = {
				url: `${this.url}?projectId=${this.projectId}&projectSlug=${this.projectSlug}`,
				method: 'GET',
				data: null,
			};

			const [response, error] = await useFetch(payload);
			console.log(response, error);

			return response;
		},
	}));

	Alpine.data('alert', () => ({
		text: "You shouldn't be seeing this",
		sweetAlertIcon: 'warning',
		bootstrapClassColor: 'warning',
		showToast() {
			const options = {
				isToast: true,
				text: this.text,
				sweetAlertIcon: this.sweetAlertIcon,
				bootstrapClassColor: this.bootstrapClassColor,
				bootstrapHexColor: this.bootstrapHexColor,
			};
			showAlert(options);
		},
	}));

	Alpine.data('saveItem', () => ({
		item: '',
		url: '',
		isLoading: false,
		status: '',
		async saveItem() {
			const payload = {
				url: this.url,
				method: 'POST',
				data: this.item,
			};

			const oldStatusText = this.status;

			this.isLoading = true;
			this.status = 'Loading...';

			const [response, error] = await useFetch(payload);
			console.log(response);

			if (response.status === 0) {
				this.isLoading = false;
				this.status = oldStatusText;
				showAlert({
					isToast: true,
					text: 'Action done successfully',
					sweetAlertIcon: 'success',
					bootstrapClassColor: 'success',
					bootstrapHexColor: '#198754',
				});
			} else {
				console.log(error);
				this.isLoading = false;
				this.status = 'Try again';
				showAlert({
					isToast: true,
					text: 'Unexpected error ocurred',
					sweetAlertIcon: 'error',
					bootstrapClassColor: 'danger',
					bootstrapHexColor: '#dc3545',
				});
			}
		},
	}));

	async function useFetch(payload) {
		try {
			if (payload.method !== 'GET') {
				const { csrfName, csrfHash } = getCsrf();
				payload.data[csrfName] = csrfHash;
			}

			const source = await fetch(buildRequest(payload));
			const response = await source.json();
			getCsrf(response.token);
			return [response, null];
		} catch (error) {
			console.log(error);
			return [null, error];
		}
	}

	function buildRequest(params) {
		return new Request(params.url, {
			method: params.method,
			headers: buildHeaders(params.method),
			body: params.data ? JSON.stringify(params.data) : null,
		});
	}

	function buildHeaders(method) {
		const headers = new Headers();
		headers.append('Content-Type', 'application/json');
		if (method !== 'GET') headers.append('X-Requested-With', 'XMLHttpRequest');
		return headers;
	}

	function getCsrf(csrfValue) {
		const csrfSelector = document.querySelector('.csrf');
		if (csrfValue) csrfSelector.value = csrfValue;
		const csrfName = csrfSelector.attributes.name.value;
		const csrfHash = csrfSelector.value;
		return { csrfName: csrfName, csrfHash: csrfHash };
	}

	function showAlert(options) {
		const toast = Swal.mixin({
			toast: true,
			title: 'Heads up!',
			text: options.text,
			icon: options.sweetAlertIcon,
			iconColor: options.bootstrapHexColor,
			showConfirmButton: false,
			position: 'bottom',
			timer: 3000,
			customClass: {
				popup: `sweet-alert-toast-popup border rounded-5 border-${options.bootstrapClassColor}`,
				title: 'sweet-alert-toast-title',
				htmlContainer: `sweet-alert-toast-html-container text-${options.bootstrapClassColor}`,
			},
		});

		const confirmation = Swal.mixin({
			title: options.title,
			text: options.text,
			icon: options.icon,
			showCancelButton: true,
			confirmButtonText: options.confirmButtonText,
			confirmButtonColor: '#0d6efd',
			customClass: {
				popup: 'sweet-alert-toast-popup rounded-5',
				title: 'text-white',
				htmlContainer: 'text-white',
			},
		});

		const type = options.isToast ? toast : confirmation;

		return type.fire();
	}
});
