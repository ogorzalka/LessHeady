(function ($) {
    $.fn.extend({
        tabify: function (options) {
            if (!this.length) {
                return this;
            }
            this.each(function () {
                var $this = $(this),
                    idList = [];
                $this.find('a').each(function (i, item) {
                    var $link = $(this),
                        tabID = '#' + $link.attr('href').split('#')[1];
                    $(tabID)[(i == 0) ? 'show' : 'hide']();
                    idList[i] = tabID;
                }).live('click', function () {
                    var $link = $(this),
                        tabID = '#' + $link.attr('href').split('#')[1];
                    $.map(idList, function (item) {
                        $(item)[(item == tabID) ? 'show' : 'hide']();
                    });
                    $this.find('li').filter('.active').removeClass('active').end().filter(':has([href$=' + tabID + '])').addClass('active');
                    return false;
                }).end().find('li:first').addClass('active');
            });
            return this;
        },
    });
})(jQuery);


$(document).ready(function(){	
	$('#articles > section').each(function(i) {
	    var $mainsection = $(this),
	        htmlNavSec = '<nav class="sectionnav"><ul class="nav_section">';
	    // retrieve the main titles
	    $mainsection.find('h3').each(function(l) {
            var $this = $(this),
                idSection = 'section-'+i+'-'+l;
            $this.parent('header').parent('section').attr('id', idSection);
	        htmlNavSec += '<li><a href="#'+idSection+'">'+$this.text()+'</a>';
	    });
	    
	    htmlNavSec += '</ul></nav>';
	    $mainsection.find('header:first').after(htmlNavSec);
	    
	    $(this).find('section').each(function(j) {
            var htmlNavArt = '<nav class="articlenav"><ul class="nav_articles">';
            $(this).find('h4').each(function(k) {
                var $this = $(this),
                    idArticle = 'article-'+i+'-'+j+'-'+k;
                $(this).parent('article').attr('id', idArticle);
                htmlNavArt += '<li><a href="#'+idArticle+'">'+$this.text()+'</a>';
            });
            htmlNavArt += '</ul></nav>';
            $(htmlNavArt).appendTo($(this).find('header'));
        });
	});
	/*
	$('.nav_articles, footer').delegate('a, a.up', 'click', function(e){						  
		// If a link has been clicked, scroll the page to the link's hash target:
		$.scrollTo( this.hash || 0, 1500);
		e.preventDefault();
	});
	*/
	$('nav').tabify();
});
