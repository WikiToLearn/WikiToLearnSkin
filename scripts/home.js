$(document).ready(function() {
	$(".howto__point").click(function() {
		$(".howto__point").addClass("howto__point--faded")
		$(this).removeClass("howto__point--faded")

		$(".howto__right").css({
			"background": "url(/skins/WikiToLearnSkin/images/angle.svg) no-repeat left -5px, url(/skins/WikiToLearnSkin/images/" + $(this).data("image-name") + ".png) no-repeat right center",
			"background-size": "auto 110%, cover"
		})
	})
});