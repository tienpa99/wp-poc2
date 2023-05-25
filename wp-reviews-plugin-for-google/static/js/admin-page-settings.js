if(typeof Trustindex_JS_loaded == 'undefined')
{
	var Trustindex_JS_loaded = {};
}

Trustindex_JS_loaded.unique = true;

jQuery(document).ready(function($) {

	$(".btn-connect-public").click(function(event) {
		event.preventDefault();

		let button = $(this);
		let token = $('#ti-noreg-connect-token').val();

		button.addClass('btn-loading').blur();

		let dont_remove_loading = false;

		// get url params
		let params = new URLSearchParams({
			type: 'Google',
			referrer: 'public',
			webhook_url: $('#ti-noreg-webhook-url').val(),
			email: $('#ti-noreg-email').val(),
			token: token,
			version: $('#ti-noreg-version').val()
		});

		let ti_window = window.open('https://admin.trustindex.io/source/edit2?' + params.toString(), 'trustindex', 'width=1000,height=1000,menubar=0');

		window.addEventListener('message', function(event) {
			if(event.origin.startsWith('https://admin.trustindex.io/'.replace(/\/$/,'')) && event.data.id)
			{
				dont_remove_loading = true;

				ti_window.close();
				$('#ti-connect-info').hide();

				$("#ti-noreg-page_details").val(JSON.stringify(event.data));
				$('#ti-noreg-review-download').val(token);

				button.closest("form").submit();
			}
		});

		$('#ti-connect-info').fadeIn();
		let timer = setInterval(function() {
			if(ti_window.closed)
			{
				$('#ti-connect-info').hide();

				if(!dont_remove_loading)
				{
					button.removeClass('btn-loading');
				}

				clearInterval(timer);
			}
		}, 1000);
	});

});