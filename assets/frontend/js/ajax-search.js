(function ($) {

	function adqs_ajax_search() {

		const insertSearchVal = function(){
			$('.adqs-ajax-search-item').on('click',function(){
				let that = $(this),
				thatDataVal = that.data('title'),
				parentForm = that.closest('form');
				parentForm.find('input[name="ls"]').val(thatDataVal);
				parentForm.find('.adqs_ajax_search_results').html('');
			});
		};


		$('.adqs-ajax-search input[name="ls"]').on(
			'keyup',
			$.debounce(200, function () {
				const that = $(this),
				searchVal = that.val(),
				parentForm = that.closest('form'),
				categoryVal = parentForm.find('select[name="category"] option').length ? parentForm.find('select[name="category"] option:selected').val() : '',
				locationVal = parentForm.find('select[name="location"] option').length ? parentForm.find('select[name="location"] option:selected').val() : '',
				searchParent = parentForm.find('.adqs-ajax-search'),
				searchResults = parentForm.find('.adqs_ajax_search_results');


				if (searchParent.hasClass('adqsp-ajax-loading')) {
					return;
				}

				if(!searchVal || (searchVal.length < 3)){
					searchParent.removeClass('adqsp-ajax-loading');
					searchResults.html('');
					return;
				}else{
					searchParent.addClass('adqsp-ajax-loading');
				}
				//console.log(searchVal);
				// ajax request
				$.ajax({
					type: 'GET',
					url: searchResults.data('ajax-url'),
					data: {
						searchVal:searchVal,
						categoryVal:categoryVal,
						locationVal:locationVal,
						directoryType:searchResults.data('directory-type') ? searchResults.data('directory-type') : '',
						author_id:searchResults.data('author-id'),
						security:searchResults.data('ajax-security'),
						action: 'adqs_ajax_search',
					},
					success(response) {
						const data = response.data ? response.data : [],
						results_html = data.results_html ? data.results_html : '';

						if(results_html){
							searchResults.html(results_html);
							insertSearchVal();
						}else{
							searchResults.html('');
						}

					},
					complete() {
						searchParent.removeClass('adqsp-ajax-loading');
					},
					/* error(error) {
						console.log(error);
					}, */
				});
				return false;
			})
		);


	}





	/* Load all function after document ready */
	$(function () {
		// misk action
		if (typeof adqs_ajax_search === 'function') {
			adqs_ajax_search();
		}

	});
})(jQuery);
