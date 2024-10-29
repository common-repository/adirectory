(function ($) {
	function adqs_export_import() {
		var isAdvancedUpload = (function () {
			var div = document.createElement('div');
			return (
				('draggable' in div ||
					('ondragstart' in div && 'ondrop' in div)) &&
				'FormData' in window &&
				'FileReader' in window
			);
		})();

		let $draggableFileArea = $('.drag-file-area');
		let $uploadIcon = $('.upload-icon');
		let $dragDropText = $('.dynamic-message');
		let $fileInput = $('.default-file-input');
		let $cannotUploadMessage = $('.cannot-upload-message');
		let $cancelAlertButton = $('.cancel-alert-button');
		let $uploadedFile = $('.file-block');
		let $fileName = $('.file-name');
		let $fileSize = $('.file-size');
		let $removeFileButton = $('.remove-file-icon');
		let $import_data = $('#csv-upload-form');
		let $export_data = $('#export_data');

		$fileInput.on('click', function () {
			$fileInput.val('');
		});

		$fileInput.on('change', function () {
			updateFileDetails($fileInput[0].files[0]);
		});

		$import_data.on('submit', function (e) {
			e.preventDefault();
			$('#status').remove();
			let file_data = $fileInput.prop('files')[0];
			let fileContainer = $('.upload-files-container');
			if (fileContainer.hasClass('loading')) {
				return;
			}
			if (!file_data) {
				$cannotUploadMessage.show();
				return;
			}
			fileContainer.addClass('loading loading-import');

			let form_data = new FormData();
			form_data.append('import_file', file_data);
			form_data.append('nonce', adqsExIm.nonce);
			form_data.append('action', 'adqs_import_data');

			$.ajax({
				url: adqsExIm.ajax_url,
				type: 'POST',
				contentType: false,
				processData: false,
				data: form_data,
				success(response) {
					const getData = response.data ? response.data : {};
					if (getData?.status === 'success') {
						window.location.replace(getData?.redirect_url);
					} else {
						fileContainer.removeClass('loading loading-import');
						$('#status').html(
							'<span style="color: red;">Something is wrong</span>'
						);
					}
				},

				error: function (jqXHR, textStatus, errorThrown) {
					$('#status').html(
						'<span style="color: red;">Error: ' +
							textStatus +
							'</span>'
					);
				},
				complete: function () {
					fileContainer.removeClass('loading loading-import');
				},
			});
		});

		$export_data.on('click', function (e) {
			e.preventDefault();
			$('#status').remove();
			let fileContainer = $('.upload-files-container');
			if (fileContainer.hasClass('loading')) {
				return;
			}
			fileContainer.addClass('loading loading-export');
			$.ajax({
				type: 'POST',
				url: adqsExIm.ajax_url,
				data: {
					action: 'adqs_export_data',
					nonce: adqsExIm.nonce,
				},
				xhrFields: {
					responseType: 'blob',
				},
				success: function (response) {
					const url = window.URL.createObjectURL(response);
					const a = document.createElement('a');
					a.style.display = 'none';
					a.href = url;

					// Get current date
					const date = new Date();
					const year = date.getFullYear();
					const month = ('0' + (date.getMonth() + 1)).slice(-2); // Pad month with leading zero
					const day = ('0' + date.getDate()).slice(-2); // Pad day with leading zero

					// Create filename with date
					const filename = `adqs-backup-${year}-${month}-${day}.zip`;

					a.download = filename;
					document.body.appendChild(a);
					a.click();
					window.URL.revokeObjectURL(url);
				},
				error: function (xhr, status, error) {
					$('#status').html(
						'<span style="color: red;">Error: ' + status + '</span>'
					);
				},
				complete: function () {
					fileContainer.removeClass('loading loading-export');
				},
			});

			return false;
		});

		$cancelAlertButton.on('click', function () {
			$cannotUploadMessage.hide();
		});

		if (isAdvancedUpload) {
			[
				'drag',
				'dragstart',
				'dragend',
				'dragover',
				'dragenter',
				'dragleave',
				'drop',
			].forEach((evt) =>
				$draggableFileArea.on(evt, function (e) {
					e.preventDefault();
					e.stopPropagation();
				})
			);

			$draggableFileArea.on('drop', function (e) {
				let files = e.originalEvent.dataTransfer.files;
				$fileInput[0].files = files;
				updateFileDetails(files[0]);
			});
		}

		$removeFileButton.on('click', function () {
			resetFileUpload();
		});

		function updateFileDetails(file) {
			$uploadIcon.html(getUploadIcon());
			$dragDropText.html('File Dropped Successfully!');
			$cannotUploadMessage.hide();
			$('.label').html(getLabelHtml());
			$fileName.html(file.name);
			$fileSize.html((file.size / 1024).toFixed(1) + ' KB');
			$uploadedFile.show();
			//$progressBar.css('width', 0);
			fileFlag = 0;
		}

		function resetFileUpload() {
			location.reload();
		}

		function getUploadIcon() {
			return `
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.12" cx="40" cy="40" r="40" fill="#27AE60"/>
                    <path d="M59.0601 26.4213C57.807 25.1665 55.7722 25.1673 54.5174 26.4213L34.0718 46.8676L24.9846 37.7805C23.7299 36.5257 21.6959 36.5257 20.4411 37.7805C19.1863 39.0353 19.1863 41.0693 20.4411 42.3241L31.7996 53.6825C32.4266 54.3095 33.2487 54.6238 34.0709 54.6238C34.8932 54.6238 35.7161 54.3103 36.3431 53.6825L59.0601 30.9647C60.3149 29.7108 60.3149 27.676 59.0601 26.4213Z" fill="#27AE60"/>
                </svg>`;
		}

		function getLabelHtml() {
			return `<span class="browse-files">
                <span class="or">drag & drop or </span>
                <input type="file"  name="import_file" class="default-file-input" />
                <span class="browse-files-text">Browse File</span>
                <span>from device</span>
            </span>`;
		}
	}

	$(function () {
		if (typeof adqs_export_import === 'function') {
			adqs_export_import();
		}
	});
})(jQuery);
