function onSubmit(token) {
	window.location.href = "/reservations";
}

$(document).scroll(function() {
	let y = $(this).scrollTop();
	if (y > 600) {
		$('#scroll-to-top-wrapper').show();
	} else {
		$('#scroll-to-top-wrapper').hide();
	}
});

$(document).ready(function() {
	$('#scroll-to-top-wrapper').click(function () {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});
})