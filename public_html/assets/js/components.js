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

			const confirmationOptions = {
				title: 'Delete this issue?',
				text: 'Are you sure you want to delete this issue and all of its files?',
				icon: 'warning',
				confirmButtonText: 'Yes, delete it',
			};

			const confirmationStatus = await showConfirmation(confirmationOptions);
			if (!confirmationStatus.isConfirmed) return;

			const [response, error] = await useFetch(payload);
			console.log(response);
			if (response.status === 0) {
				element.remove();
				showToast({
					message: 'Issue deleted successfully',
					bootstrapIcon: 'bi-check-circle',
					bootstrapColor: 'success',
				});
			} else {
				showToast({
					message: 'Unexpected error ocurred',
					bootstrapIcon: 'bi-x-circle',
					bootstrapColor: 'danger',
				});
			}
		},
	}));

	Alpine.data('reportChart', () => ({
		url: '/report/getReport',
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
				url: this.url,
				type: this.selectedType,
				project: this.project,
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
				url: this.url,
				type: this.selectedType,
				project: this.project,
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
		url: '/calendar/getIssues',
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
				navLinks: true, // can click day/week names to navigate views
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
				url: this.url,
				projectId: this.projectId,
				projectSlug: this.projectSlug,
			};

			const [response, error] = await useFetch(payload);
			//TODO: Mostrar error si no hay issues
			console.log(response, error);

			return response;
		},
	}));

	Alpine.data('toast', () => ({
		message: '',
		bootstrapIcon: 'bi-check-circle',
		bootstrapColor: 'success',
		showToast() {
			const options = {
				message: this.message,
				bootstrapIcon: this.bootstrapIcon,
				bootstrapColor: this.bootstrapColor,
			};
			showToast(options);
		},
	}));

	// TODO: Usar el fetch que hice en snap-issue
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

	function showConfirmation(options) {
		return Swal.fire({
			title: options.title,
			text: options.text,
			icon: options.icon,
			showCancelButton: true,
			confirmButtonText: options.confirmButtonText,
		});
	}

	function showToast(options) {
		const toastContainer = document.createElement('div');
		toastContainer.classList.add(
			'toast-container',
			'p-3',
			'bottom-0',
			'start-50',
			'translate-middle-x'
		);

		const toast = document.createElement('div');
		toast.classList.add(
			'toast',
			'rounded-5',
			'shadow-none',
			'py-1',
			'px-2',
			`border-${options.bootstrapColor}`
		);
		toastContainer.appendChild(toast);

		const toastBody = document.createElement('div');
		toastBody.classList.add('toast-body');
		toast.appendChild(toastBody);

		const flexLayout = document.createElement('div');
		flexLayout.classList.add(
			'd-flex',
			'justify-content-start',
			'align-items-center'
		);
		toastBody.appendChild(flexLayout);

		const icon = document.createElement('i');
		icon.classList.add(
			'bi',
			`${options.bootstrapIcon}`,
			`text-${options.bootstrapColor}`,
			'flex-shrink-0',
			'me-3'
		);
		icon.style.fontSize = '2rem';
		flexLayout.appendChild(icon);

		const messageContainer = document.createElement('div');
		flexLayout.appendChild(messageContainer);

		const title = document.createElement('strong');
		title.textContent = 'Heads up!';
		messageContainer.appendChild(title);

		const br = document.createElement('br');
		messageContainer.appendChild(br);

		const message = document.createElement('strong');
		message.classList.add(`text-${options.bootstrapColor}`);
		message.textContent = options.message;
		messageContainer.appendChild(message);
		document.body.appendChild(toastContainer);

		const createdToast = new bootstrap.Toast(toast, {});
		createdToast.show();
	}
});
