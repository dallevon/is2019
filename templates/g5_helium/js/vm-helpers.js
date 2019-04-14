'use strict';

if (typeof Virtuemart === 'undefined') {
	const Virtuemart = {};
}

// @ts-ignore
jQuery(function($) {
	Virtuemart.checkQuantityInCart = function(obj, step, available, wrongAmountMessage, overAvailableMessage) {
		const quantity = obj.value;
		if (isNaN(quantity) || quantity <= 0) {
			obj.value = step;
			return false;
		} else if (quantity > available) {
			obj.value = available;
			alert(overAvailableMessage);
			return false;
		}

		const remainder = quantity % step;

		if (remainder !== 0) {
			alert(wrongAmountMessage);
			if (quantity !== remainder && quantity > remainder) {
				obj.value = Math.abs(quantity - remainder);
			} else {
				obj.value = step;
			}
			return false;
		}
		return true;
	};
	Virtuemart.restoreChildProductScrollPosition = function() {
		var childProducts = document.querySelector('.is-product-field-type-A .controls input'),
			container = document.querySelector('.is-product-field-type-A .controls'),
			containerScrollLeft = 0;

		$(document).on('change', childProducts, function() {
			if (container) {
				containerScrollLeft = container.scrollLeft;
			}
		});
		$('body').on('updateVirtueMartProductDetail', function() {
			container = document.querySelector('.is-product-field-type-A .controls');
			if (container) {
				containerScrollLeft && (container.scrollLeft = containerScrollLeft);
			}
		});
	};
	Virtuemart.restoreChildProductScrollPosition();
});
