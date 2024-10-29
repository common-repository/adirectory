(function ($) {
	// check element exists
	$.fn.exists = function () {
		return this.length > 0;
	};
	const { __ } = wp.i18n;
	function adqs_external_lib_install() {
		const repeaterItemsSelector = $('.qsd-repeater-items');

		if (repeaterItemsSelector.exists()) {
			repeaterItemsSelector.each(function () {
				$(this).repeater({
					initEmpty: false,
					show() {
						$(this).slideDown();
					},
					hide(deleteElement) {
						if (
							confirm(
								__(
									'Are you sure you want to delete this element?',
									'adirectory'
								)
							)
						) {
							$(this).slideUp(deleteElement);
						}
					},
					isFirstItemUndeletable: true,
				});
			});
		}
		/* $('.qsd-timeicker').each(function () {
            $(this).timepicker({
                time: '12:00:00.000'
            });
        }); */
	}

	// Change mis action
	function adqs_misc_action_init() {
		$('.adqs-never-expire input:checkbox').on('change', function () {
			let thatCheckbox = $(this),
				targetItem = thatCheckbox
					.closest('.misc-pub-adpqs-expiration-time')
					.find('#adqs-timestamp-wrap');
			if (thatCheckbox.is(':checked')) {
				targetItem.addClass('hidden');
			} else {
				targetItem.removeClass('hidden');
			}
		});
	}
	// Change mis action
	function adqs_openeach_init() {
		$('.qsd-open24Each-checkbox input:checkbox').on('change', function () {
			let thatCheckbox = $(this),
				targetItem = thatCheckbox
					.closest('.qsd-b-hour-group-day-single')
					.find('.qsd-b-hour-item-choice-area');
			if (thatCheckbox.is(':checked')) {
				targetItem.addClass('hidden');
			} else {
				targetItem.removeClass('hidden');
			}
		});
	}
	// Change Directory Type
	function adqs_change_directory_type_init() {
		let populerTerms, $pop_adqs_category, $pop_adqs_location;

		populerTerms = function (taxonomy) {
			const getPupTerms = [];
			$('#' + taxonomy + '-pop li input:checkbox').each(function (i) {
				getPupTerms[i] = $(this).val();
			});
			return getPupTerms;
		};
		$pop_adqs_category = populerTerms('adqs_category');
		$pop_adqs_location = populerTerms('adqs_location');

		const directoryTypeSelector = $('#adqs_directory_type');
		if (directoryTypeSelector.exists()) {
			directoryTypeSelector.on('change', function () {
				const that = $(this),
					$directory_type = that.find('option:selected').val(),
					$post_id = that.data('post-id');

				// ajax call
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {
						action: 'adqs_change_directory_type',
						security: qsAdminMetaBox.security,
						directory_type: $directory_type,
						post_id: $post_id,
						pop_adqs_category: $pop_adqs_category,
						pop_adqs_location: $pop_adqs_location,
					},
					success(response) {
						// qsd category html
						const getData = response.data ? response.data : [];
						$('#adqs_categorychecklist').html(
							getData.adqs_category
						);
						$('#adqs_categorychecklist-pop').html(
							getData.pop_adqs_category
						);

						// qsd location html
						$('#adqs_locationchecklist').html(
							getData.adqs_location
						);
						$('#adqs_locationchecklist-pop').html(
							getData.pop_adqs_location
						);

						//
						$('#adqs_daynamicFields_area').html(
							getData.adqs_dynamic_fields
						);

						// all filed list item display
						$(
							'#adqs_categorychecklist, #adqs_categorychecklist-pop, #adqs_locationchecklist, #adqs_locationchecklist-pop'
						).css('display', 'inherit');

						// all loader
						adqs_directory_type_metabox_loader();
					},
					error(error) {
						console.log('error', error);
					},
				});
			});

			$('#adqs_directory_type').trigger('change');
		}
	} // end

	// Metabox Fields Tabs
	function adqs_metabox_fields_tabs() {
		// Variables

		let $tabLink = $('#adTabs-section .adTab-link'),
			$tabBody = $('#adTabs-section .adTab-body'),
			timerOpacity;

		// Menu Click
		$tabLink.off('click').on('click', function (e) {
			const that = $(this),
				targetItem = that.attr('href');
			// Prevent Default
			e.preventDefault();
			e.stopPropagation();

			// Clear Timers
			window.clearTimeout(timerOpacity);

			// Toggle Class Logic
			// Remove Active Classes
			$tabLink.removeClass('active ');
			$tabBody.removeClass('active ');
			$tabBody.removeClass('qsd-active-content');

			// Add Active Classes
			that.addClass('active');
			$(targetItem).addClass('active');

			// Opacity Transition Class
			timerOpacity = setTimeout(() => {
				$(targetItem).addClass('qsd-active-content');

				if (
					typeof adqs_map_init === 'function' &&
					$(targetItem).find('.qsd-form-field').exists()
				) {
					adqs_map_init();
				}
			}, 50);
		});

		// Check if the required field is empty
		$('input#publish').on('click', function () {
			$('[required]').each(function () {
				const that = $(this);
				let checkSelect =
					that.find('option').exists() &&
					!that.find('option:selected').exists()
						? true
						: false;
				if (that.val() === '' || checkSelect) {
					const thatParent = $(this).closest('.adTab-body'),
						thatParentTarget = $(
							'a[href="#' + thatParent.attr('id') + '"]'
						);

					that.addClass('required-highlight');

					if (!thatParent.hasClass('need-validation')) {
						thatParent.addClass('need-validation');
					}
					if (!thatParentTarget.hasClass('need-validation')) {
						thatParentTarget.addClass('need-validation');
					}
				}
			});
		});
	} // end

	// Metabox Sliders Fields
	function adqs_metabox_sliders_images(selectror) {
		let sliders_media_upload, add_sliders;
		add_sliders = selectror.find('.qsd-add-sliders');

		if (add_sliders.exists()) {
			let that;
			add_sliders.click(function (e) {
				(that = $(this)),
					(sliderInputName = that
						.closest('.qsd-slider-images')
						.data('name'));
				// If the uploader object has already been created, reopen the dialog
				if (sliders_media_upload) {
					sliders_media_upload.open();
					return;
				}

				// Extend the wp.media object
				sliders_media_upload = wp.media.frames.file_frame = wp.media({
					//title: button_text.title,
					//button: { text: button_text.button },
					library: { type: 'image' },
					multiple: true, //allowing for multiple image selection
				});

				/**
				 *When multiple images are selected, get the multiple attachment objects
				 *and convert them into a usable array of attachments
				 */
				sliders_media_upload.on('select', function () {
					const attachments = sliders_media_upload
						.state()
						.get('selection')
						.map(function (attachment) {
							attachment.toJSON();
							return attachment;
						});

					//loop through the array and do things with each attachment
					let i;
					for (i = 0; i < attachments.length; ++i) {
						//sample function 1: add image preview
						if (attachments[i].id) {
							that.after(
								'<div class="qsd-slider-image-preview" style="background-image:url(' +
									attachments[i].attributes.url +
									')"><input type="hidden" name="' +
									sliderInputName +
									'[]" value="' +
									attachments[i].id +
									'"><i class="dashicons dashicons-remove"></i></div>'
							);
						}
					}
				});

				sliders_media_upload.open();
			});
			// remove slider all items
			selectror.on('click', '.qsd-remove-sliders', function () {
				$(this)
					.closest('.qsd-slider-images')
					.find('.qsd-slider-image-preview')
					.remove();
			});
			selectror
				.find('.qsd-slider-images')
				.on('click', 'i.dashicons-remove', function () {
					$(this).closest('.qsd-slider-image-preview').remove();
				});
		}
	} // end

	// Metabox Files Upload
	function adqs_metabox_files_upload() {
		const $fileInput = $('.qsd-file-input'),
			$droparea = $('.qsd-file-drop-area');

		// highlight drag area
		$fileInput.on('dragenter focus click', function () {
			$droparea.addClass('is-active');
		});

		// change inner text
		$fileInput.on('change', function () {
			const that = $(this),
				thatFileArea = that.closest('.qsd-file-drop-area'),
				filesCount = that[0].files.length
					? parseInt(that[0].files.length)
					: 0,
				$textContainer = that.prev();

			if (filesCount === 1) {
				// if single file is selected, show file name
				const fileName = that.val().split('\\').pop();
				$textContainer.text(fileName);
			} else {
				// otherwise show number of files
				$textContainer.text(
					'(' + filesCount + ') ' + __('files selected', 'adirectory')
				);
			}
			if (
				filesCount > 0 &&
				!thatFileArea.find('.qsd-delete-btn').exists()
			) {
				thatFileArea
					.addClass('is-active')
					.append(
						'<div class="qsd-delete-btn"><i class="dashicons dashicons-trash"></i></div>'
					);
			} else {
				thatFileArea
					.removeClass('is-active')
					.find('.qsd-delete-btn')
					.remove();
				$textContainer.text(
					__('or drag and drop files here', 'adirectory')
				);
			}
		});

		$droparea.on('click', '.qsd-delete-btn', function () {
			$(this)
				.closest('.qsd-file-drop-area')
				.find('.qsd-file-input')
				.val(null)
				.trigger('change');
		});
	} // end

	// map int
	function adqs_map_init() {
		// Open Streetmap
		const leaflet_map = function (lat, lon) {
			if (!$('#adqs_map').exists()) {
				return;
			}
			const mapParent = $('.qsd-map-field');
			mapParent.find('#adqs_map').remove();
			mapParent.append('<div id="adqs_map"></div>');

			const _map_lat = $('#_map_lat'),
				_map_lon = $('#_map_lon'),
				_address = $('#_address');
			_map_lat.val(lat);
			_map_lon.val(lon);
			const truckIcon = L.divIcon({
				className: 'dashicons dashicons-location qsd-custom-icon',
				iconSize: [50, 50],
			});
			const opsMap = L.map('adqs_map').setView([lat, lon], 12);
			L.marker([lat, lon], {
				icon: truckIcon,
				draggable: true,
			})
				.addTo(opsMap)
				.on('dragend', function (e) {
					const marker = e.target,
						markerPos = marker.getLatLng();
					_map_lat.val(markerPos.lat);
					_map_lon.val(markerPos.lng);
					$.ajax({
						url: 'https://nominatim.openstreetmap.org/reverse?format=json&lon='
							.concat(markerPos.lng, '&lat=')
							.concat(markerPos.lat),
						type: 'GET',
						success: function success(data) {
							if (data.display_name && _address.exists()) {
								_address.val(data.display_name);
							}
						},
					});
				});
			L.tileLayer(
				'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
			).addTo(opsMap);
		};
		if ($('#adqs_map').exists()) {
			if (
				navigator.geolocation &&
				($('#_map_lon').val() == '' || $('#_map_lon').val() == '')
			) {
				navigator.geolocation.getCurrentPosition(function (pos) {
					leaflet_map(pos.coords.latitude, pos.coords.longitude);
				});
			} else {
				const _map_lat_val = $('#_map_lat').val()
						? parseFloat($('#_map_lat').val())
						: 23.822337,
					_map_lon_val = $('#_map_lon').val()
						? parseFloat($('#_map_lon').val())
						: 90.3654296;
				leaflet_map(_map_lat_val, _map_lon_val);
			}
		}

		// map generate by click button
		$('.qsd-map-field').on(
			'click',
			'.qsd-btn-latlon-generate',
			function (e) {
				e.preventDefault();

				const mapParent = $(this).closest('.qsd-map-field'),
					map_lat = mapParent.find('#_map_lat').val(),
					map_lon = mapParent.find('#_map_lon').val();
				mapParent.find('#adqs_map').remove();
				mapParent.append('<div id="adqs_map"></div>');
				setTimeout(function () {
					leaflet_map(map_lat, map_lon);
				}, 300);
			}
		);

		$('#_address').on(
			'keyup',
			$.debounce(200, function () {
				const that = $(this),
					keyword = that.val();
				if (keyword.length > 3) {
					$.ajax({
						url:
							'https://nominatim.openstreetmap.org/search?format=json&q=' +
							encodeURIComponent(keyword),
						type: 'GET',
						success: function success(data) {
							if (data.length > 0) {
								let resultsHTML = '';
								$.each(data, function (i, v) {
									resultsHTML += `<li data-lat="${v.lat}" data-lon="${v.lon}">${v.display_name}</li>`;
								});
								that.next('#adqs_address_result')
									.html(resultsHTML)
									.show();
							}
							$('#adqs_address_result').on(
								'click',
								'li',
								function () {
									const thatItem = $(this),
										getLan = thatItem.data('lat'),
										getLon = thatItem.data('lon'),
										displayName = thatItem.text();
									if ($('#adqs_map').exists()) {
										thatItem
											.closest('#adqs_address_result')
											.hide();
										thatItem
											.closest('.qsd-form-field')
											.find('#_address')
											.val(displayName);
										$('#_map_lat').val(getLan);
										$('#_map_lon').val(getLon);
										$('.qsd-btn-latlon-generate').trigger(
											'click'
										);
									}
								}
							);
						},
					});
				}
			})
		);
	} // end

	// load also when change directory ajax
	function adqs_directory_type_metabox_loader() {
		if (typeof adqs_external_lib_install === 'function') {
			adqs_external_lib_install();
		}
		if (typeof adqs_metabox_fields_tabs === 'function') {
			adqs_metabox_fields_tabs();
		}
		if (typeof adqs_metabox_sliders_images === 'function') {
			adqs_metabox_sliders_images($('.qsd-images-field'));
		}
		if (typeof adqs_metabox_files_upload === 'function') {
			adqs_metabox_files_upload();
		}
		if (typeof adqs_map_init === 'function') {
			adqs_map_init();
		}
		if (typeof adqs_openeach_init === 'function') {
			adqs_openeach_init();
		}
	}

	/* Load all function after document ready */
	$(function () {
		// misk action
		if (typeof adqs_misc_action_init === 'function') {
			adqs_misc_action_init();
		}
		// ajax change directory
		if (typeof adqs_change_directory_type_init === 'function') {
			adqs_change_directory_type_init();
		}
		if (typeof adqs_metabox_sliders_images === 'function') {
			adqs_metabox_sliders_images($('.qsd-slider-metabox'));
		}
		// load also when change directory
		if (typeof adqs_directory_type_metabox_loader === 'function') {
			adqs_directory_type_metabox_loader();
		}
	});
})(jQuery);
