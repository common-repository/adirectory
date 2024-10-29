document.addEventListener('DOMContentLoaded', () => {
	const adqs_vanilla_map = (lat, lon) => {
		const adMap = document.getElementById('adqs_map');
		if (!adMap) {
			return;
		}
		const mapParent = document.querySelector('.qsd-map-field');
		const existingMap = mapParent.querySelector('#adqs_map');
		if (existingMap) {
			existingMap.remove();
		}

		const newMapDiv = document.createElement('div');
		newMapDiv.id = 'adqs_map';
		mapParent.appendChild(newMapDiv);

		const mapLat = document.getElementById('_map_lat');
		const mapLon = document.getElementById('_map_lon');
		const address = document.getElementById('_address');
		mapLat.value = lat;
		mapLon.value = lon;

		const truckIcon = L.icon({
			iconUrl:
				'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
			iconSize: [20, 30],
		});

		const opsMap = L.map('adqs_map').setView([lat, lon], 10);

		L.marker([lat, lon], {
			icon: truckIcon,
			draggable: true,
		})
			.addTo(opsMap)
			.on('dragend', function (e) {
				const marker = e.target;
				const markerPos = marker.getLatLng();
				mapLat.value = markerPos.lat;
				mapLon.value = markerPos.lng;

				fetch(
					`https://nominatim.openstreetmap.org/reverse?format=json&lon=${markerPos.lng}&lat=${markerPos.lat}`
				)
					.then((response) => response.json())
					.then((data) => {
						if (data.display_name && address) {
							address.value = data.display_name;
						}
					})
					.catch((error) =>
						console.error('Error fetching address:', error)
					);
			});

		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(
			opsMap
		);
	};

	if (document.getElementById('adqs_map')) {
		if (
			navigator.geolocation &&
			(!document.getElementById('_map_lon').value ||
				!document.getElementById('_map_lat').value)
		) {
			navigator.geolocation.getCurrentPosition(function (pos) {
				adqs_vanilla_map(pos.coords.latitude, pos.coords.longitude);
			});
		} else {
			const mapLatVal = document.getElementById('_map_lat').value
				? parseFloat(document.getElementById('_map_lat').value)
				: 23.822337;
			const mapLonVal = document.getElementById('_map_lon').value
				? parseFloat(document.getElementById('_map_lon').value)
				: 90.3654296;
			adqs_vanilla_map(mapLatVal, mapLonVal);
		}
	}

	if (document.getElementById('_address')) {
		document.getElementById('_address').addEventListener(
			'keyup',
			debounce(function () {
				const inputElement = this;
				const keyword = inputElement.value;

				if (keyword.length > 3) {
					const xhr = new XMLHttpRequest();
					const url =
						'https://nominatim.openstreetmap.org/search?format=json&q=' +
						encodeURIComponent(keyword);

					xhr.open('GET', url, true);

					xhr.onload = function () {
						if (xhr.status >= 200 && xhr.status < 400) {
							const data = JSON.parse(xhr.responseText);
							if (data.length > 0) {
								let resultsHTML = '';
								data.forEach(function (v) {
									resultsHTML += `<li data-lat="${v.lat}" data-lon="${v.lon}">${v.display_name}</li>`;
								});
								const resultsElement =
									inputElement.nextElementSibling;
								resultsElement.innerHTML = resultsHTML;
								resultsElement.style.display = 'block';

								resultsElement.addEventListener(
									'click',
									function (event) {
										event.preventDefault();
										if (
											event.target.tagName.toLowerCase() ===
											'li'
										) {
											const thatItem = event.target;
											const getLat =
												thatItem.getAttribute(
													'data-lat'
												);
											const getLon =
												thatItem.getAttribute(
													'data-lon'
												);
											const displayName =
												thatItem.textContent;

											if (
												document.getElementById(
													'adqs_map'
												)
											) {
												resultsElement.style.display =
													'none';
												inputElement.value =
													displayName;
												document.getElementById(
													'_map_lat'
												).value = getLat;
												document.getElementById(
													'_map_lon'
												).value = getLon;
												document
													.querySelector(
														'.qsd-btn-latlon-generate'
													)
													.click();
											}
										}
									}
								);
							}
						}
					};

					xhr.send();
				}
			}, 500)
		);
	}

	if (document.querySelector('.qsd-map-field')) {
		document
			.querySelector('.qsd-map-field')
			.addEventListener('click', function (e) {
				if (e.target.classList.contains('qsd-btn-latlon-generate')) {
					e.preventDefault();

					const mapParent = e.target.closest('.qsd-map-field');
					const mapLat = mapParent.querySelector('#_map_lat').value;
					const mapLon = mapParent.querySelector('#_map_lon').value;

					const existingMap = mapParent.querySelector('#adqs_map');
					if (existingMap) {
						existingMap.remove();
					}

					const newMapDiv = document.createElement('div');
					newMapDiv.id = 'adqs_map';
					mapParent.appendChild(newMapDiv);

					setTimeout(function () {
						adqs_vanilla_map(mapLat, mapLon);
					}, 300);
				}
			});
	}

	function debounce(func, wait) {
		let timeout;
		return function () {
			const context = this,
				args = arguments;
			clearTimeout(timeout);
			timeout = setTimeout(function () {
				func.apply(context, args);
			}, wait);
		};
	}

	const priceTypePrice = document.getElementById('adqs_price_type_price');
	const priceTypeRange = document.getElementById('adqs_price_type_range');
	const priceFieldsUnit = document.getElementById('adqs_pricefield_unit');
	const priceRangeSelect = document.getElementById('adqs_price_range');

	function toggleFields() {
		if (priceTypePrice.checked) {
			priceFieldsUnit.style.display = 'block';
			priceRangeSelect.style.display = 'none';
		} else if (priceTypeRange.checked) {
			priceFieldsUnit.style.display = 'none';
			priceRangeSelect.style.display = 'block';
		}
	}

	if (
		priceTypePrice &&
		priceTypePrice &&
		priceFieldsUnit &&
		priceRangeSelect
	) {
		priceTypePrice.addEventListener('change', toggleFields);
		priceTypeRange.addEventListener('change', toggleFields);

		toggleFields();
	}

	const day_switch_b = document.querySelectorAll('.day-switch-b');

	const open_all_time_rad = document.getElementById('adqs_b_open_24');
	const hide_b_hour = document.getElementById('adqs_b_hide');
	const open_specific_date = document.getElementById('adqs_b_open_spec');

	const time_slot_add_btns = document.querySelectorAll('.adqs-add-time-slot');

	const open_twent_four_trigger = document.querySelectorAll(
		'.open_twenty_four_trigger'
	);

	if (open_twent_four_trigger) {
		open_twent_four_trigger.forEach((trigger) => {
			trigger.addEventListener('change', (e) => {
				e.preventDefault();
				if (e.currentTarget.checked) {
					e.currentTarget.parentNode.parentNode.parentNode.querySelector(
						'.adqs-oen-close-time-wrapper'
					).style.display = 'none';
				} else {
					e.currentTarget.parentNode.parentNode.parentNode.querySelector(
						'.adqs-oen-close-time-wrapper'
					).style.display = 'block';
				}
			});
		});
	}

	function generateTimeOptions() {
		let options = '<option value="">Open</option>';
		for (let i = 0; i < 24; i++) {
			for (let j = 0; j < 60; j += 15) {
				let time = new Date(1970, 0, 1, i, j);
				let formattedTime = time.toLocaleTimeString('en-US', {
					hour: '2-digit',
					minute: '2-digit',
					hour12: true,
				});
				options += `<option value="${formattedTime}">${formattedTime}</option>`;
			}
		}
		return options;
	}

	function deleteSingleSlot(event) {
		if (event.target.closest('.adqs-time-slot-delete')) {
			event.preventDefault();
			const slot = event.target.closest('.single-slot-open-close');
			slot.remove();
		}
	}

	if (document.querySelectorAll('.adqs-business-hour-data')) {
		document.addEventListener('click', deleteSingleSlot);
	}

	if (time_slot_add_btns) {
		time_slot_add_btns.forEach((add_btn) => {
			add_btn.addEventListener('click', (e) => {
				e.preventDefault();
				const slotIndex = e.currentTarget.parentNode.querySelectorAll(
					'.all-slot-wrapper .single-slot-open-close'
				).length;
				const openOptions = generateTimeOptions();
				const closeOptions = generateTimeOptions();
				const day = e.currentTarget.dataset.day;

				const html_ = `
        <select name="bhc[${day}][${slotIndex}][open]">
            ${openOptions}
        </select>
        <select name="bhc[${day}][${slotIndex}][close]">
            ${closeOptions}
        </select>
        <button class="adqs-time-slot-delete">
            <i class="dashicons dashicons-trash"></i>
        </button>`;

				const myDiv = document.createElement('div');
				myDiv.setAttribute('class', 'single-slot-open-close');
				myDiv.innerHTML = html_;
				e.currentTarget.parentNode
					.querySelector('.all-slot-wrapper')
					.appendChild(myDiv);
			});
		});
	}

	function toggleBusinessStatus() {
		const business_data = document.querySelector(
			'.adqs-business-hour-data'
		);

		if (business_data) {
			if (open_all_time_rad.checked) {
				business_data.style.display = 'none';
			} else if (hide_b_hour.checked) {
				business_data.style.display = 'none';
			} else if (open_specific_date.checked) {
				business_data.style.display = 'block';
			}
		}
	}

	if (open_specific_date && hide_b_hour && open_all_time_rad) {
		open_specific_date.addEventListener('change', toggleBusinessStatus);
		hide_b_hour.addEventListener('change', toggleBusinessStatus);
		open_all_time_rad.addEventListener('change', toggleBusinessStatus);
	}

	toggleBusinessStatus();

	if (day_switch_b) {
		day_switch_b.forEach((inp) => {
			if (inp.checked) {
				inp.parentNode.parentNode.parentNode.parentNode.querySelector(
					'.adqs-oen-close-time-wrapper'
				).style.display = 'block';

				inp.parentNode.parentNode.parentNode.querySelector(
					'.twemnty_four_open_switch'
				).style.display = 'flex';
			} else {
				inp.parentNode.parentNode.parentNode.parentNode.querySelector(
					'.adqs-oen-close-time-wrapper'
				).style.display = 'none';

				inp.parentNode.parentNode.parentNode.querySelector(
					'.twemnty_four_open_switch'
				).style.display = 'none';
			}
		});

		day_switch_b.forEach((inp) => {
			inp.addEventListener('change', (e) => {
				if (e.currentTarget.checked) {
					e.currentTarget.parentNode.parentNode.parentNode.parentNode.querySelector(
						'.adqs-oen-close-time-wrapper'
					).style.display = 'block';

					e.currentTarget.parentNode.parentNode.parentNode.querySelector(
						'.twemnty_four_open_switch'
					).style.display = 'flex';
				} else {
					e.currentTarget.parentNode.parentNode.parentNode.parentNode.querySelector(
						'.adqs-oen-close-time-wrapper'
					).style.display = 'none';

					e.currentTarget.parentNode.parentNode.parentNode.querySelector(
						'.twemnty_four_open_switch'
					).style.display = 'none';
				}
			});
		});
	}

	//Media handler

	const feat_img = document.getElementById('adqs-front-feat-img');
	const feat_thumbnail_id = document.getElementById('feat_thumbnail_id');
	const feature_img_container = document.querySelector(
		'.feature-img-container'
	);

	// const slider_thumbnail_id = document.getElementById('slider_thumbnail_id');
	const slider_image_inp = document.getElementById('slider_image_inp');
	const slider_img_container = document.querySelector(
		'.slider-img-container'
	);

	if (slider_img_container) {
		document.addEventListener('click', (e) => {
			if (e.target.closest('.single-slide-wrapper')) {
				const target = e.target.closest('.single-slide-wrapper');

				if (target) {
					const attchId = target.dataset.attachId;
					removeImageAttachment(attchId);
					target.remove();
				}
			}
		});
	}

	if (feature_img_container) {
		document.addEventListener('click', (e) => {
			if (e.target.closest('.single-feat-wrapper')) {
				const target = e.target.closest('.single-feat-wrapper');
				if (target) {
					feat_thumbnail_id.value = '';
					target.remove();
				}
			}
		});
	}

	if (slider_image_inp) {
		slider_image_inp.addEventListener('change', (e) => {
			e.preventDefault();

			const gal_err = document.getElementById('gallery-image-err');
			const files = e.target.files;
			const maxFileSize = 3 * 1024 * 1024; // 3 MB
			let totalSize = 0;

			for (let i = 0; i < files.length; i++) {
				totalSize += files[i].size;
			}

			console.log(files.length);

			if (files.length > 5) {
				gal_err.innerHTML = '**Maximum file count exceeded';
				return;
			}
			if (totalSize > maxFileSize) {
				gal_err.innerHTML = '**Maximum tottal file size exceeded. ';
				return;
			}

			setSliderImages(e.target.files);
			gal_err.innerHTML = '';
		});
	}

	if (feat_img) {
		feat_img.addEventListener('change', (e) => {
			e.preventDefault();

			setFeatureImage(e.target.files[0]);
		});
	}

	function removeImageAttachment(attachmentId) {
		const hiddenInput = document.getElementById('slider_thumbnail_id');
		let existingIds = hiddenInput.value ? hiddenInput.value.split(',') : [];
		existingIds = existingIds.filter((id) => id != attachmentId);
		hiddenInput.value = existingIds.join(',');
	}

	// Function to render uploaded images
	function renderUploadedImages(images) {
		images.forEach((image) => {
			const imgWrapper = document.createElement('div');
			imgWrapper.classList.add('single-slide-wrapper');
			imgWrapper.setAttribute('data-attach-id', image.attachment_id);

			const img = document.createElement('img');
			img.src = image.attachment_url;
			imgWrapper.appendChild(img);

			const removeBtn = document.createElement('div');
			removeBtn.id = 'slide-remove';
			removeBtn.innerHTML = `<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.7874 9.25496C11.2109 9.67867 11.2109 10.3632 10.7874 10.7869C10.5762 10.9982 10.2988 11.1043 10.0213 11.1043C9.74402 11.1043 9.46671 10.9982 9.25545 10.7869L6.0001 7.53138L2.74474 10.7869C2.53348 10.9982 2.25617 11.1043 1.97886 11.1043C1.70134 11.1043 1.42403 10.9982 1.21277 10.7869C0.789265 10.3632 0.789265 9.67867 1.21277 9.25496L4.46833 5.99961L1.21277 2.74425C0.789265 2.32055 0.789265 1.63599 1.21277 1.21228C1.63648 0.788776 2.32103 0.788776 2.74474 1.21228L6.0001 4.46784L9.25545 1.21228C9.67916 0.788776 10.3637 0.788776 10.7874 1.21228C11.2109 1.63599 11.2109 2.32055 10.7874 2.74425L7.53186 5.99961L10.7874 9.25496Z" fill="#FAFAFA"/>
                </svg>`;

			imgWrapper.appendChild(removeBtn);

			slider_img_container.appendChild(imgWrapper);
		});
	}

	// Function to update hidden input with image IDs
	function updateHiddenInput(images) {
		const hiddenInput = document.getElementById('slider_thumbnail_id');
		const existingIds = hiddenInput.value
			? hiddenInput.value.split(',')
			: [];
		const newIds = images.map((image) => image.attachment_id);
		hiddenInput.value = existingIds.concat(newIds).join(',');
	}

	const setSliderImages = async (files) => {
		let e_img_html = '';

		const formdata = new FormData();
		formdata.append('action', 'adqs_add_slider_images');

		formdata.append('security', window.frontaddObj.front_dash_list_nonce);

		for (let i = 0; i < files.length; i++) {
			e_img_html += `<div class='adqs-feat-loader-container'><div class='lds-spinner'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>`;

			formdata.append('sliderimgaes[]', files[i]);
		}
		const slider_images_loader_html = `<div class='slider-loader-wrapper'>${e_img_html}</div>`;
		slider_img_container.innerHTML = slider_images_loader_html;

		const request = await fetch(window.frontaddObj.admin_ajax, {
			method: 'POST',
			body: formdata,
		});

		const response = await request.json();
		if (response.data.uploaded_images) {
			slider_img_container.innerHTML = '';
			renderUploadedImages(response.data.uploaded_images);
			updateHiddenInput(response.data.uploaded_images);
		}
	};

	const setFeatureImage = async (file) => {
		const faeture_image_loader_data = `<div class='adqs-feat-loader-container'><div class='lds-spinner'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>`;
		feature_img_container.innerHTML = faeture_image_loader_data;

		const formdata = new FormData();
		formdata.append('action', 'adqs_add_feature_image');

		formdata.append('security', window.frontaddObj.front_dash_list_nonce);

		formdata.append('files', file);

		const request = await fetch(window.frontaddObj.admin_ajax, {
			method: 'POST',
			body: formdata,
		});

		const response = await request.json();

		if (response.data.attachment_id && response.data.attachment_url) {
			if (feat_thumbnail_id) {
				feat_thumbnail_id.value = response.data.attachment_id;
			}
			let image_html = `<div class='single-feat-wrapper'><img src='${response.data.attachment_url}' /><div id="feat-remove"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M10.7874 9.25496C11.2109 9.67867 11.2109 10.3632 10.7874 10.7869C10.5762 10.9982 10.2988 11.1043 10.0213 11.1043C9.74402 11.1043 9.46671 10.9982 9.25545 10.7869L6.0001 7.53138L2.74474 10.7869C2.53348 10.9982 2.25617 11.1043 1.97886 11.1043C1.70134 11.1043 1.42403 10.9982 1.21277 10.7869C0.789265 10.3632 0.789265 9.67867 1.21277 9.25496L4.46833 5.99961L1.21277 2.74425C0.789265 2.32055 0.789265 1.63599 1.21277 1.21228C1.63648 0.788776 2.32103 0.788776 2.74474 1.21228L6.0001 4.46784L9.25545 1.21228C9.67916 0.788776 10.3637 0.788776 10.7874 1.21228C11.2109 1.63599 11.2109 2.32055 10.7874 2.74425L7.53186 5.99961L10.7874 9.25496Z" fill="#FAFAFA"/>
			</svg>
			</div></div>`;

			feature_img_container.innerHTML = image_html;
		}
	};
});
