<script type="text/javascript" src="{{ assets('js/jquery-1.7.1.min.js') }}"></script>
<script type="text/javascript" src="{{ assets('js/raphael-min.js') }}"></script>
<script type="text/javascript" src="{{ assets('js/jquery.easing.js') }}"></script>
<script type="text/javascript" src="{{ assets('js/iview.js') }}"></script>
<script>
		$(document).ready(function(){
			$('#iview').iView({
					pauseTime: 7000,
					pauseOnHover: false,
					directionNav: true,
					directionNavHide: false,
					controlNav: true,
					controlNavNextPrev: false,
					controlNavTooltip: false,
					nextLabel: "Next",
					previousLabel: "Prev",
					playLabel: "Play",
					pauseLabel: "Pause",
					timer: "360Bar",
					timerBg: "#EEE",
					timerColor: "#000",
					timerDiameter: 20,
					timerPadding: 2,
					timerStroke: 4,
					timerPosition: "bottom-right"
			});
		});
</script>
<script src="{{ assets('js/tabs.js') }}"></script>
<script type="text/javascript" src="{{ assets('js/plugin.minb6a4.js?ver=6.6.1') }}"></script>
<script type="text/javascript" src="{{ assets('js/panel-style-selector.minb6a4.js?ver=6.6.1') }}"></script>
<script type="text/javascript" id="g5plus_framework_app-js-extra">
    var g5plus_framework_constant = {"product_compare":"Compare","product_wishList":"WishList"};
    var g5plus_framework_ajax_url = "";
    var g5plus_framework_theme_url = "";
    var g5plus_framework_site_url = "";
</script>
<!-- ETO ZAO -->
<script type="text/javascript" src="{{ assets('js/app.minb6a4.js?ver=6.6.1') }}" id="g5plus_framework_app-js"></script>
<script type="text/javascript" src="{{ assets('js/app.min0e03.js?ver=1.0.0.0') }}" id="xmenu-menu-js-js"></script>

<script src="{{ assets('plugins/light-gallery/js/lightgallery-all.js') }}"></script>
<script>
    $(function () { 
        $('#aniimated-thumbnials').lightGallery({
            thumbnail: true,
            selector: 'a'
        });
    });
</script>
<script src="{{ assets('js/main.js') }}"></script>
<script>
	var element = $('.floating-chat');
var myStorage = localStorage;

if (!myStorage.getItem('chatID')) {
    myStorage.setItem('chatID', createUUID());
}

setTimeout(function() {
    element.addClass('enter');
}, 1000);

element.click(openElement);

function openElement() {
    var messages = element.find('.messages');
    var textInput = element.find('.text-box');
    element.find('>i').hide();
    element.addClass('expand');
    element.find('.chat').addClass('enter');
    var strLength = textInput.val().length * 2;
    textInput.keydown(onMetaAndEnter).prop("disabled", false).focus();
    element.off('click', openElement);
    element.find('.user-bar div').click(closeElement);
    element.find('#sendMessage').click(sendNewMessage);
    messages.scrollTop(messages.prop("scrollHeight"));
}

function closeElement() {
    element.find('.chat').removeClass('enter').hide();
    setTimeout(function() {
        element.find('.chat').removeClass('enter').show();
        $('.floating-chat').removeClass('expand');
    }, 50);
    element.click(openElement);
}

function createUUID() {
    // http://www.ietf.org/rfc/rfc4122.txt
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    return uuid;
}

function sendNewMessage() {
    var userInput = $('.text-box');
    var newMessage = userInput.html().replace(/\<div\>|\<br.*?\>/ig, '\n').replace(/\<\/div\>/g, '').trim().replace(/\n/g, '<br>');

    if (!newMessage) return;

    var messagesContainer = $('.messages');

    messagesContainer.append([
        '<li class="self">',
        newMessage,
        '</li>'
    ].join(''));

    // clean out old message
    userInput.html('');
    // focus on input
    userInput.focus();

    messagesContainer.finish().animate({
        scrollTop: messagesContainer.prop("scrollHeight")
    }, 250);
}

function onMetaAndEnter(event) {
    if ((event.metaKey || event.ctrlKey) && event.keyCode == 13) {
        sendNewMessage();
    }
}
</script>

<script>

    //<![CDATA[
var show_msg = '1';

if (show_msg !== '0') {
  var options = {view_src: "View Source is disabled!", inspect_elem: "Inspect Element is disabled!", right_click: "Right click is disabled!", copy_cut_paste_content: "Cut/Copy/Paste is disabled!", image_drop: "Image Drag-n-Drop is disabled!" }
} else {
  var options = '';
}

    function nocontextmenu(e) { return false; }
    document.oncontextmenu = nocontextmenu;
    document.ondragstart = function() { return false;}

document.onmousedown = function (event) {
  event = (event || window.event);
  if (event.keyCode === 123) {
    if (show_msg !== '0') {show_toast('inspect_elem');}
    return false;
  }
}
document.onkeydown = function (event) {
  event = (event || window.event);
  //alert(event.keyCode);   return false;
  if (event.keyCode === 123 ||
      event.ctrlKey && event.shiftKey && event.keyCode === 73 ||
      event.ctrlKey && event.shiftKey && event.keyCode === 75) {
    if (show_msg !== '0') {show_toast('inspect_elem');}
    return false;
  }
  if (event.ctrlKey && event.keyCode === 85) {
    if (show_msg !== '0') {show_toast('view_src');}
    return false;
  }
}
function addMultiEventListener(element, eventNames, listener) {
  var events = eventNames.split(' ');
  for (var i = 0, iLen = events.length; i < iLen; i++) {
    element.addEventListener(events[i], function (e) {
      e.preventDefault();
      if (show_msg !== '0') {
        show_toast(listener);
      }
    });
  }
}
addMultiEventListener(document, 'contextmenu', 'right_click');
addMultiEventListener(document, 'cut copy paste print', 'copy_cut_paste_content');
addMultiEventListener(document, 'drag drop', 'image_drop');
function show_toast(text) {
  var x = document.getElementById("amm_drcfw_toast_msg");
  x.innerHTML = eval('options.' + text);
  x.className = "show";
  setTimeout(function () {
    x.className = x.className.replace("show", "")
  }, 3000);
}
//]]>
</script>
<style type="text/css">
body * :not(input):not(textarea){
    user-select:none !important; 
    -webkit-touch-callout: none !important;  
    -webkit-user-select: none !important; 
    -moz-user-select:none !important; 
    -khtml-user-select:none !important; 
    -ms-user-select: none !important;
    }
    #amm_drcfw_toast_msg{
        visibility:hidden;
        min-width:250px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        margin-left:-125px;
        background-color:#333;
        color:#fff;
        text-align:center;
        border-radius:25px;
        padding:4px;
        position:fixed;
        z-index:999;
        left:50%;
        bottom:30px;
        font-size:17px
    }
    #amm_drcfw_toast_msg.show{
        visibility:visible;
        -webkit-animation:fadein .5s,fadeout .5s 2.5s;
        animation:fadein .5s,fadeout .5s 2.5s
    }
    @-webkit-keyframes fadein{
        from{
            bottom:0;
            opacity:0
        }
        to{
            bottom:30px;
            opacity:1
        }
    }
    @keyframes fadein{
        from{
            bottom:0;
            opacity:0
        }
        to{
            bottom:30px;
            opacity:1
        }
    }
    @-webkit-keyframes fadeout{
        from{
            bottom:30px;
            opacity:1
        }
        to{
            bottom:0;
            opacity:0
        }
    }
    @keyframes fadeout{
        from{
            bottom:30px;
            opacity:1
        }
        to{
            bottom:0;
            opacity:0
        }
    }
</style>




<div id="amm_drcfw_toast_msg"></div>
    