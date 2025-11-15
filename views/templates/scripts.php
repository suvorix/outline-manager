<div class="notification_wrapper" style="display:none"></div>
<div class="loadding_wrapper">
    <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
        <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
    </svg>
</div>

<script>
    var notification = function(settings){
        var default_settings = {
            type: 'info',
            html: '',
            delay: 2000
        }
        if(typeof settings['type'] === 'undefined') { settings['type'] = default_settings['type']; }
        if(typeof settings['html'] === 'undefined') { settings['html'] = default_settings['html']; }
        if(typeof settings['delay'] === 'undefined') { settings['delay'] = default_settings['delay']; }
        $('.notification_wrapper').show();
        var el = $('<div onclick="removeNotify(this)" class="notification notification_' + settings['type'] + '">' + settings['html'] + '</div>').appendTo('.notification_wrapper');
        setTimeout(() => {
            removeNotify(el);
        }, settings['delay']);
    }
    var removeNotify = function(el){
        $(el).remove();
        if($('.notification_wrapper').find('.notification').length == 0) {
            $('.notification_wrapper').hide();
        }
    }
    var loader = function(show) {
        if(show){
            $('.loadding_wrapper').show().animate({
                'opacity': 1
            }, 200);
        }
        else {
            $('.loadding_wrapper').animate({
                'opacity': 0
            }, 200, function() {
                $(this).hide();
            });
        }
    }
    $(document).ready(function(){
        loader(false);
    });
</script>
