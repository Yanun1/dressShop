$(function () {
	$(".slider-range").each(function (index) {
		let slider = $(this);
		let minInput = slider.parent().find(".min_price");
		let maxInput = slider.parent().find(".max_price");

		// Генерация уникальных ключей для каждого слайдера
		let localStorageKeyMin = "savedMinValue" + index;
		let localStorageKeyMax = "savedMaxValue" + index;

		// Получение сохраненных значений из localStorage
		let savedMinValue = localStorage.getItem(localStorageKeyMin) || 0;
		let savedMaxValue = localStorage.getItem(localStorageKeyMax) || 100000;

		slider.slider({
			range: true,
			orientation: "horizontal",
			min: 0,
			max: 100000,
			values: [savedMinValue, savedMaxValue],

			slide: function (event, ui) {
				if (ui.values[0] === ui.values[1]) {
					return false;
				}

				minInput.val(ui.values[0]);
				maxInput.val(ui.values[1]);

				// Сохранение значений в localStorage при изменении
				localStorage.setItem(localStorageKeyMin, ui.values[0]);
				localStorage.setItem(localStorageKeyMax, ui.values[1]);
			}
		});

		// Обработчик событий при изменении .min_price
		minInput.on("input", function () {
			let minValue = parseInt($(this).val());
			let maxValue = parseInt(maxInput.val());

			if (minValue > maxValue) {
				minValue = maxValue;
				minInput.val(minValue);
			}

			slider.slider("values", [minValue, maxValue]);

			// Сохранение значений в localStorage при изменении
			localStorage.setItem(localStorageKeyMin, minValue);
			localStorage.setItem(localStorageKeyMax, maxValue);
		});

		// Обработчик событий при изменении .max_price
		maxInput.on("input", function () {
			let minValue = parseInt(minInput.val());
			let maxValue = parseInt($(this).val());

			if (maxValue < minValue) {
				maxValue = minValue;
				maxInput.val(maxValue);
			}

			slider.slider("values", [minValue, maxValue]);

			// Сохранение значений в localStorage при изменении
			localStorage.setItem(localStorageKeyMin, minValue);
			localStorage.setItem(localStorageKeyMax, maxValue);
		});

		minInput.val(savedMinValue);
		maxInput.val(savedMaxValue);
	});

	$(".min_price, .max_price").on("paste keyup", function () {
		$('#price-range-submit').show();

		let min_price_range = parseInt($(this).parent().find(".min_price").val());
		let max_price_range = parseInt($(this).parent().find(".max_price").val());

		if (min_price_range == max_price_range) {
			max_price_range = min_price_range + 100;

			$(this).parent().find(".min_price").val(min_price_range);
			$(this).parent().find(".max_price").val(max_price_range);
		}

		$(this).parent().find(".slider-range").slider("values", [min_price_range, max_price_range]);
	});
});