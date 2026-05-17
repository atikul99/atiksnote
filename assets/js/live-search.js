jQuery(function ($) {
	const $input = $('#search-input');
	const $box   = $('#search-suggestions');

	$input.on('keyup', function () {
		const keyword = $(this).val().trim();

		if (keyword.length > 2) {
			$.post(LiveSearch.ajaxurl, {
				action: 'live_search',
				keyword: keyword,
				_ajax_nonce: LiveSearch.nonce
			}, function (res) {
				$box.html(res).fadeIn(150);
			});
		}else {
			$box.fadeOut(100);
		}
	});

	$(document).on('click', function (e) {
		if (!$(e.target).closest('.search-form').length) {
			$box.fadeOut(100);
		}
	});
});