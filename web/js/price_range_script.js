$(function () {
	$(".slider-range").each(function (index) {
		var slider = $(this);
		var minInput = slider.parent().find(".min_price");
		var maxInput = slider.parent().find(".max_price");

		// Генерация уникальных ключей для каждого слайдера
		var localStorageKeyMin = "savedMinValue" + index;
		var localStorageKeyMax = "savedMaxValue" + index;

		// Получение сохраненных значений из localStorage
		var savedMinValue = localStorage.getItem(localStorageKeyMin) || 0;
		var savedMaxValue = localStorage.getItem(localStorageKeyMax) || 100000;

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
			var minValue = parseInt($(this).val());
			var maxValue = parseInt(maxInput.val());

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
			var minValue = parseInt(minInput.val());
			var maxValue = parseInt($(this).val());

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

		var min_price_range = parseInt($(this).parent().find(".min_price").val());
		var max_price_range = parseInt($(this).parent().find(".max_price").val());

		if (min_price_range == max_price_range) {
			max_price_range = min_price_range + 100;

			$(this).parent().find(".min_price").val(min_price_range);
			$(this).parent().find(".max_price").val(max_price_range);
		}

		$(this).parent().find(".slider-range").slider("values", [min_price_range, max_price_range]);
	});
});