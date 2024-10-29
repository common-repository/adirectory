document.addEventListener('DOMContentLoaded', () => {
	if (document.getElementById('markers_data')) {
		const markers = JSON.parse(
			document.getElementById('markers_data').textContent
		);

		const defultLat = markers[0].lat;
		const defultLon = markers[0].lon;
		const mymap = L.map('markers_map').setView([defultLat, defultLon], 13);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
		}).addTo(mymap);


		const truckIcon = L.divIcon({
			className: 'dashicons dashicons-location qsd-custom-icon',
			iconSize: [50, 50],
		});

		const markerClusters = L.markerClusterGroup();

		markers.forEach(function (marker) {
			const customMarker = L.marker([marker.lat, marker.lon], {
				icon: truckIcon,
			})
				.addTo(mymap)
				.bindPopup(marker.title);

			markerClusters.addLayer(customMarker);
		});

		mymap.addLayer(markerClusters);
	}

	function reviewTags(hiddenfield, checkboxes, query = '') {
		if (hiddenfield && checkboxes) {
			checkboxes.forEach((checkbox) => {
				checkbox.addEventListener('change', (e) => {
					e.preventDefault();
					if (e.target.checked) {
						if (hiddenfield.value === '') {
							hiddenfield.value += e.target.value;
						} else {
							hiddenfield.value += ',' + e.target.value;
						}
					} else {
						const valueToRemove = e.target.value;
						let values = hiddenfield.value.split(',');
						values = values.filter((val) => val !== valueToRemove);
						hiddenfield.value = values.join(',');
					}
				});
			});
		}
	}

	if (document.querySelectorAll('.tags-inner-check')) {
		const tags = document.getElementById('tags_field');
		const tagsCheckbox = document.querySelectorAll('.tags-inner-check');

		reviewTags(tags, tagsCheckbox, 'tags');
	}
	if (document.querySelectorAll('.qsd-prodcut-grid-reviews-inner')) {
		const tagsWrapper = document.querySelectorAll('.qsd-tags-wrapper');

		const tagsSeemore = document.querySelector('.seemore-tag');

		if (tagsWrapper.length < 5 && tagsSeemore) {
			tagsSeemore.style.visibility = 'hidden';
		}

		if (tagsSeemore) {
			tagsSeemore.addEventListener('click', (e) => {
				e.preventDefault();
				tagsWrapper.forEach((tag) => {
					if (tag.classList.contains('tags-hidden')) {
						tag.classList.remove('tags-hidden');
					}
				});
			});
		}
	}

	if (
		document.getElementById('adqs_advtf_btn') &&
		document.getElementById('adqs_advtFilter_more')
	) {
		document
			.getElementById('adqs_advtf_btn')
			.addEventListener('click', (e) => {
				e.preventDefault();
				document
					.getElementById('adqs_advtFilter_more')
					.classList.toggle('hidden');
			});
	}

	async function add_remove_fav($listingid) {
		const formdata = new FormData();
		formdata.append('action', 'adqs_add_rmv_fav_listing');
		formdata.append('security', window.adqsGridPage.security);
		formdata.append('postid', Number($listingid));
		const request = await fetch(window.adqsGridPage.ajaxurl, {
			method: 'POST',
			body: formdata,
		});

		const response = await request.json();

		let countBtn = document.querySelector('.adqs-favlist-widget-wrapper .abs-count');
		if(countBtn && response.data){
			let count = 0;
			if(Array.isArray(response?.data)){
				count = response?.data?.length;
			}else{
				count = Object.values(response?.data)?.length;
			}
			countBtn.innerHTML = count || 0;
		}
	}

	if (document.querySelectorAll('.adqs-add-fav-btn')) {
		const fav_btns = document.querySelectorAll('.adqs-add-fav-btn');

		fav_btns.forEach((fav) => {

			fav.addEventListener('click', (e) => {
				if(document.querySelectorAll(`.adqs-msg-tooltip`).length > 0){
					document.querySelectorAll(`.adqs-msg-tooltip`).forEach((tt)=>{
						tt.remove();
					});
				}

				if(!document.body.classList.contains('logged-in')){
					e.currentTarget.innerHTML += `<span class="adqs-msg-tooltip">${adqsGridPage.login_msg}</span>`;
					return;
				}
				if (e.currentTarget.classList.contains('adqs-active-fav')) {
					e.currentTarget.classList.remove('adqs-active-fav');

					add_remove_fav(e.currentTarget.dataset.favId);
				} else {
					e.currentTarget.classList.add('adqs-active-fav');

					add_remove_fav(e.currentTarget.dataset.favId);
				}
			});
		});
	}
});
