$(document).ready(function() {

	var $sidebar = $("#sidebar"), $window = $(window), offset = $sidebar.offset(), topPadding = 150;

    $window.scroll(function() {
        if ($window.scrollTop() > offset.top) {
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - offset.top + topPadding
            });
        } else {
            $sidebar.stop().animate({
                marginTop: 50
            });
        }
    });
	
	var fullWidth = {'width' : '1100px'}
	var minWidth = {'width' : '900px'}

	// Collapsible Sidebar //
    if ($.cookie("sidebar") == "collapsed") {
        $('#toggle').html("Expand Sidebar");
		$('#toggle').css('left', '-20px');
        $("#content").css(fullWidth);
        $("#sidebar").hide();
    }

    $('#toggle').click(function(event) {
        event.preventDefault();

        if ($('#sidebar').is(':visible')) {

            $('#toggle').html("Expand Sidebar");

            $("#sidebar").stop(true, true).fadeOut("slow", function() {
                $("#content").stop(true, true).animate(fullWidth, 500);
				$('#toggle').css('left', '-20px');
                $.cookie("sidebar", "collapsed", { expires: 365, path: '/' });
            });
        }
        else {
			
            $('#toggle').html("Collapse Sidebar");

            $("#content").stop(true, true).animate(minWidth, 500, function() {
				$("#sidebar").stop(true, true).fadeIn("slow");
				$('#toggle').css('left', '180px');
                $.cookie("sidebar", "expanded", { expires: 365, path: '/' });
            });
        }
    });

	function accordion() {
		
		$('ul.menu ul').hide();
		
		$.each($('ul.menu'), function(){
			
			var cookie = $.cookie(this.id);
			
			if(cookie === null || String(cookie).length < 1) {
				$('#' + this.id + '.expandfirst ul:first').show();
			} else {
				$('#' + this.id + ' .' + cookie).next().show();
			}
		});
	
	$('ul.menu li a').click(function() {
	
		var checkElement = $(this).next();
		var parent = this.parentNode.parentNode.id;
		
		if($('#' + parent).hasClass('accordion')) {
			if((String(parent).length > 0) && (String(this.className).length > 0)) {
				if($(this).next().is(':visible')) {
					$.cookie(parent, null);
				} else {
					$.cookie(parent, this.className);
				}
				$(this).next().slideToggle('fast');
			}
		}
		
		if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			if($('#' + parent).hasClass('collapsible')) {
				$('#' + parent + ' ul:visible').slideUp('fast');
			}
			return false;
		}
	
		if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$('#' + parent + ' ul:visible').slideUp('fast');
			if((String(parent).length > 0) && (String(this.className).length > 0)) {
				$.cookie(parent, this.className);
			}
			checkElement.slideDown('fast');
			return false;
			}
		});
	}

	accordion();

	// if there are buttons in the format "a button" without ID attributes, copy them into the masthead
	// or buttons in the format button.head_button_clone with an ID attribute.
	var $buttons = $("#content a[id=] button[id=], #content button.head_button_clone[id!=]"); 
	if($buttons.size() > 0) {
		var $head = $("<div id='head_button'></div>").appendTo("#content .right").show();
		$buttons.each(function() {
			var $t = $(this);
			var $a = $t.parent('a'); 
			if($a.size()) { 
				$button = $t.parent('a').clone();
				$head.append($button);
			} else if($t.is('.head_button_clone')) {
				$button = $t.clone();
				$button.attr('data-from_id', $t.attr('id')).attr('id', $t.attr('id') + '_copy');
				$a = $("<a></a>").attr('href', '#');
				$button.click(function() {
					$("#" + $(this).attr('data-from_id')).click().parents('form').submit();
					return false;
				});
				$head.append($a.append($button));	
			}
		}); 
	}

	// make buttons with <a> tags click to the href of the <a>
	$("a > button").click(function() {
		window.location = $(this).parent("a").attr('href'); 
	});

	// we don't even want to go there
	if($.browser.msie && $.browser.version < 8) {
		$("#content .container").html("<h2>ProcessWire does not support IE7 and below at this time. Please try again with a newer browser.</h2>").show();
	}

	// add focus to the first text input, where applicable
	jQuery('#content input[type=text]:visible:enabled:first').each(function() {
		var $t = $(this); 
		if(!$t.val() && !$t.is(".no_focus")) $t.focus();	
	});

	/// for FOUC fix
	jQuery('#content').removeClass('fouc_fix'); 

	$('#top a').click(function () {
		$('body,html').animate({
			scrollTop: 0
			}, 200);
		return false;
	});
	
	$(".tip").tipTip({delay:200,fadeIn:100,fadeOut:100});
	
	if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0)
	{
		var _interval = window.setInterval(function ()
		{
			var autofills = $('input:-webkit-autofill');
			if (autofills.length > 0)
			{
				window.clearInterval(_interval); // stop polling
				autofills.each(function()
				{
					var clone = $(this).clone(true, true);
					$(this).after(clone).remove();
				});
			}
		}, 200);
	}

});