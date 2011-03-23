jQuery(function($) {
	/*
	// the new wp e commerce form stuff automatically does this
	$('#side form.pform label').each(function() {
		$(this).addClass('jq');
		if($(this).next().is('select')) {
			$(this).hide().next().children('option[value=""]').text($(this).html());
		} else {
			$(this).next().bind({
				focus: function() {
					$(this).prev().hide();
				},
				blur: function() {
					if($(this).val()=='') {
						$(this).prev().show();
					}
				}
			});
		}
	});
	*/
	Cufon.replace('#main h2, #slogan', { fontFamily: 'TitilliumText' });
	Cufon.replace('#arrow span', { fontFamily: 'TitilliumText', textShadow: '-1px -1px rgba(0, 0, 0, 0.65)' });
	Cufon.replace('#secondary h3', { fontFamily: 'TitilliumText', fontWeight: '800' });
	Cufon.replace('h1.page-title', { fontFamily: 'TitilliumText', color: '-linear-gradient(#f69629, #c15400' });
	Cufon.now();

});