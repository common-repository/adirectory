(function ($) {
	/*
	 * Let's begin with validation functions
	 */
	$.extend($.fn, {
		// check element exists
		exists() {
			return this.length > 0;
		},
		// check if field value lenth more than 3 symbols ( for name and comment )
		validate() {
			const that = $(this);
			if (that.val().length < 3) {
				that.addClass('error');
				return false;
			}
			that.removeClass('error');
			return true;
		},
		//check if email is correct
		validateEmail() {
			const that = $(this),
				emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			emailToValidate = that.val();
			if (!emailReg.test(emailToValidate) || emailToValidate == '') {
				that.addClass('error');
				return false;
			}
			that.removeClass('error');
			return true;
		},
	});

	const { __ } = wp.i18n;

	/*
	 * map int
	 */
	function adqs_map_init() {
		const qsdMap = $('#qsdMap');
		if (qsdMap.length === 0) {
			return;
		}
		const mapLat = qsdMap.data('lat'),
			mapLon = qsdMap.data('lon');
		const truckIcon = L.divIcon({
			className: 'dashicons dashicons-location qsd-custom-icon',
			iconSize: [50, 50],
		});
		const opsMap = L.map('qsdMap').setView([mapLat, mapLon], 12);
		L.marker([mapLat, mapLon], {
			icon: truckIcon,
			draggable: true,
		}).addTo(opsMap);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(
			opsMap
		);
	}

	/*
	 * Simple Lightbox
	 */
	function adqs_simple_light_box() {
		// As A jQuery Plugin -->
		$('.adqs-gallary a').simpleLightbox({
			overlayOpacity: 0.9,
		});
	} // end

	/*
	 * video popup
	 */
	function adqs_video_popup() {
		// video popup
		$('.my-video-links').on('click', function (e) {
			e.preventDefault();
			const videoIframe = $('#video-iframe');
			videoIframe.attr('src', videoIframe.attr('src') + '&autoplay=1'); // adding autoplay to the URL
			$('body').addClass('lightbox-open');
		});

		$('.lightbox-close, #video-bg').on('click', function () {
			const videoIframe = $('#video-iframe'),
				cleaned = videoIframe.attr('src').replace('&autoplay=1', '');
			videoIframe.attr('src', cleaned); // adding autoplay to the URL
			$('body').removeClass('lightbox-open');

			const closeTarget = $(this).closest('.listing-grid-vedio');
			$('html, body')
				.stop()
				.animate(
					{
						scrollTop: closeTarget.offset().top - 50,
					},
					300,
					'swing'
				);
		});
	} // end

	/*
	 * Listing Post Review & Comment
	 */
	function adqs_ajax_listing_review_comments() {
		/*
		 * On comment form submit
		 */
		$('#commentform').submit(function (e) {
			// define some vars
			const button = $('#adqs_reviewSubmit'), // submit button
				respond = $('#respond'), // comment form container
				commentlist = $('.adqs_comments_items_wrap'), // comment list container
				review_rating = $('input[name=adqs_review_rating]'),
				author = $('#author'),
				email = $('#email'),
				comment = $('#comment'),
				comments_section = $('#adqs_comments_section'),
				buttonDefaultText = button.text(),
				errorFields = [];

			respond.prev('.errorFields').remove();
			// if user is logged in, do not validate author and email fields
			if (author.exists() && !author.validate()) {
				errorFields.push('Name');
			}

			if (email.exists() && !email.validateEmail()) {
				errorFields.push('Email');
			}

			// validate comment in any case
			if (!comment.validate()) {
				errorFields.push('Comment');
			}

			if (!review_rating.is(':checked')) {
				errorFields.push('Review Rating');
			}

			if (errorFields.length > 0) {
				respond.before(
					'<p class="errorFields">' +
						__('Please fill up all required ', 'adirectory') +
						errorFields.join(', ') +
						__(' fields', 'adirectory') +
						'</p>'
				);
			}

			// if comment form isn't in process, submit it

			if (
				!button.hasClass('loadingform') &&
				!author.hasClass('error') &&
				!email.hasClass('error') &&
				!comment.hasClass('error') &&
				!(errorFields.length > 0)
			) {
				// ajax request
				$.ajax({
					type: 'POST',
					url: qsSingleData.ajaxurl, // admin-ajax.php URL
					// send form data + action parameter
					data: `${$(this).serialize()}&security=${qsSingleData.security}&action=adqs_ajaxlistingreview`,

					beforeSend(xhr) {
						// what to do just after the form has been submitted
						button
							.addClass('loadingform')
							.text(__('Loading...', 'adirectory'));
					},
					error(error) {},
					success(response) {
						// if this post already has comments
						const data = response.data ? response.data : [];
						let comment_html = data.comment_html
							? data.comment_html
							: false;
						let avgRatings_html = data.avgRatings_html
							? data.avgRatings_html
							: false;
						if (data.errorMessage) {
							respond.before(data.errorMessage);
							return;
						}
						if (data.alreadyHasComment) {
							respond.before(
								'<p class="errorFields">' +
									__(
										'You have already write a review',
										'adirectory'
									) +
									'</p>'
							);
						} else if (comment_html) {
							if (commentlist.exists()) {
								commentlist.prepend(comment_html);
							} else {
								// if no comments yet
								comment_html =
									'<div class="adqs_comments_items_wrap">' +
									comment_html +
									'</div>';
								comments_section
									.find('.listing-grid-review')
									.append(comment_html);
							}
							// Remove adqs_writeReview
							review_rating
								.filter(':checked')
								.prop('checked', false);
							if (author.exists()) {
								author.val('');
							}
							if (email.exists()) {
								email.val('');
							} else {
								$('#adqs_writeReview').remove();
								comments_section
									.find('.review-top-btn')
									.remove();
							}

							comment.val('');

							// avg ratings update
							const avgOverviewTititle = $(
								'.qsd-comments-area .listing-grid-review-title'
							);
							if (avgRatings_html) {
								if (avgOverviewTititle.exists()) {
									avgOverviewTititle.html(avgRatings_html);
								} else {
									// if no avg rating yet
									avgRatings_html =
										'<h2 class="listing-grid-review-title">' +
										avgRatings_html +
										'</h2>';
									comments_section
										.find('.listing-grid-review-top')
										.prepend(avgRatings_html);
								}
							}

							$('html, body')
								.stop()
								.animate(
									{
										scrollTop:
											$('.qsd-comments-area').offset()
												.top - 50,
									},
									300,
									'swing'
								);
						}
					},
					complete() {
						// what to do after a comment has been added
						button
							.removeClass('loadingform')
							.text(buttonDefaultText);
					},
				});
			}
			return false;
		});

		// scroll to write review
		$('.qsd-comments-area').on('click', '.review-top-btn', function (e) {
			// Prevent default anchor click behavior
			e.preventDefault();

			// Store the hash
			const hash = this.hash;

			// Animate the scroll to the target ID
			$('html, body').animate(
				{
					scrollTop: $(hash).offset().top,
				},
				300
			);
		});

		// load more button review comment
		$('.review-btn-main').on('click', '.qsd-review-more', function () {
			const button = $(this),
				buttonDefaultText = button.text(),
				buttonMain = button.closest('.review-btn-main'),
				commentlist = buttonMain.prev('.adqs_comments_items_wrap'),
				post_id = buttonMain.data('post-id')
					? buttonMain.data('post-id')
					: 0,
				per_page = buttonMain.data('per-page')
					? buttonMain.data('per-page')
					: 2,
				current_page = buttonMain.attr('data-current-page')
					? buttonMain.attr('data-current-page')
					: 1;

			if (button.hasClass('loading-more')) {
				return;
			}
			// ajax request
			$.ajax({
				type: 'POST',
				url: qsSingleData.ajaxurl, // admin-ajax.php URL
				// send form data + action parameter
				data: {
					post_id,
					per_page,
					current_page,
					security: qsSingleData.security,
					action: 'adqs_ajaxlistingreview_more',
				},

				beforeSend() {
					// what to do just after the form has been submitted
					button
						.addClass('loading-more')
						.text(__('Loading...', 'adirectory'));
				},
				error(error) {},
				success(response) {
					const data = response.data ? response.data : [],
						comment_html = data.comment_html
							? data.comment_html
							: false,
						get_current_page = data.get_current_page,
						has_next = data.has_next;
					if (comment_html && commentlist.exists()) {
						commentlist.append(comment_html);
					}
					if (buttonMain.exists()) {
						buttonMain.attr('data-current-page', get_current_page);
						if (!has_next) {
							buttonMain.remove();
						}
					}
				},
				complete() {
					// what to do after a comment has been added
					if (button.exists()) {
						button
							.removeClass('loading-more')
							.text(buttonDefaultText);
					}
				},
			});
			return false;
		});
	} // end

	/*
	 * Contact agent
	 */
	function adqs_ajax_connect_agents() {
		$('#adqs_connectAgents form').submit(function (e) {
			// define some vars
			const thatForm = $(this),
				button = thatForm.find('.connect-agents-btn'), // submit button
				name = thatForm.find('input[name="adqs_ca_name"]'),
				email = thatForm.find('input[name="adqs_ca_email"]'),
				phone = thatForm.find('input[name="adqs_ca_phone"]'),
				message = thatForm.find('textarea[name="adqs_ca_msg"]'),
				buttonDefaultText = button.text();

			// ajax request
			$.ajax({
				type: 'POST',
				url: qsSingleData.ajaxurl, // admin-ajax.php URL
				// send form data + action parameter
				data: `${$(this).serialize()}&security=${qsSingleData.security}&action=adqs_ajaxlisting_contact_owner`,

				beforeSend(xhr) {
					// what to do just after the form has been submitted
					button
						.addClass('loadingform')
						.text(__('Sending...', 'adirectory'));
				},
				error(error) {
					console.log(error);
				},
				success(response) {
					// if this post already has comments
					const data = response.data ? response.data : [],
						send_mail = data.send_mail ? data.send_mail : false;
					if (send_mail) {
						button.before(
							'<p class="qsd-send-success">' +
								__(
									'Message send successfully done!',
									'adirectory'
								) +
								'</p>'
						);
					}
					name.val('');
					email.val('');
					phone.val('');
					message.val('');
				},
				complete() {
					// what to do after a comment has been added
					button.removeClass('loadingform').text(buttonDefaultText);
				},
			});
			return false;
		});
	} // end


	/*
	 * Realted Title
	 */
	function adqs_related_title() {
		const relatedList = $('.adqs-relatedListings_area');
		if(relatedList.exists() && !relatedList.find('.qsd-content-none').exists()){
			relatedList.addClass('adqs-active');
		}

	} // end

	/*
	 * load all function when dom ready functions
	 */
	$(function () {
		// Load Map
		if (typeof adqs_map_init === 'function') {
			adqs_map_init();
		}

		// Simple Ligthbox
		if (typeof adqs_simple_light_box === 'function') {
			adqs_simple_light_box();
		}
		// load video popup
		if (typeof adqs_video_popup === 'function') {
			adqs_video_popup();
		}
		// ajax review comment
		if (typeof adqs_ajax_listing_review_comments === 'function') {
			adqs_ajax_listing_review_comments();
		}

		// ajax connect agents
		if (typeof adqs_ajax_connect_agents === 'function') {
			adqs_ajax_connect_agents();
		}
		// adqs related title
		if (typeof adqs_related_title === 'function') {
			adqs_related_title();
		}
	});
})(jQuery);
