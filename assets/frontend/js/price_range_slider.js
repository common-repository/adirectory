(function ($) {
	const priceslider = function () {
		if ($('#slider-tooltips').length > 0) {
			const tooltipSlider = document.getElementById('slider-tooltips');
			const urlParams = new URLSearchParams(window.location.search);

			const formatForSlider = {
				from(formattedValue) {
					return Number(formattedValue);
				},
				to(numericValue) {
					return Math.round(numericValue);
				},
			};
			let minPrice, maxPrice;

			if (urlParams.has('minPrice')) {
				minPrice = parseFloat(urlParams.get('minPrice'));
			}
			if (urlParams.has('maxPrice')) {
				maxPrice = parseFloat(urlParams.get('maxPrice'));
			}

			const startValues = [minPrice || 0, maxPrice || 0]; // Default values if not provided in query string
			const maxDataPrice = !isNaN(
				parseFloat(tooltipSlider.getAttribute('data-max'))
			)
				? parseFloat(tooltipSlider.getAttribute('data-max'))
				: 10000;
			noUiSlider.create(tooltipSlider, {
				start: startValues,
				connect: true,
				step: 1,
				format: formatForSlider,
				range: {
					min: 0,
					max: maxDataPrice,
				},
			});

			const formatValues = [
				document.getElementById('slider-margin-value-min'),
				document.getElementById('slider-margin-value-max'),
				document.getElementById('min-price-field'),
				document.getElementById('max-price-field'),
			];
			tooltipSlider.noUiSlider.on(
				'update',
				function (values, handle, unencoded) {
					formatValues[0].innerHTML = 'Price: ' + '$' + values[0];
					formatValues[1].innerHTML = '$' + values[1];
					formatValues[2].value = values[0];
					formatValues[3].value = values[1];
				}
			);
		}
	};

	priceslider();
})(jQuery);
