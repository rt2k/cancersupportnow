<?php
session_start();
ini_set('session.gc-maxlifetime', 1800);

if (isset($_POST['logout'])) {
    session_unset();
} else if (isset($_SESSION['username'])){

    include('checkSession.inc');
}

require_once('captchaSecret.inc');

date_default_timezone_set('America/Denver');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

	if(isset($_GET['gt'])) {
		$goto = $_GET['gt'];
	}
	else {
		$goto = 'home';
	}

$adminList = array('Patricia_Torn', 'ruwang', 'germanyboy1950');
if (isset($_SESSION['username']) && in_array($_SESSION['username'], $adminList)) {
    $isAdmin = true;
} else {
    $isAdmin = false;
}

$hhhTicketCost = 65;
$hhhTableCost = 575;
?>	

<html>
<link rel="stylesheet" type="text/css" href="smart_menu/src/css/sm-core-css.css" />
<link rel="stylesheet" type="text/css" href="smart_menu/src/css/sm-blue/sm-blue.css" />
<link rel="stylesheet" type="text/css" href="style.css?<?php echo filemtime('style.css'); ?>" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="smart_menu/src/libs/jquery/jquery.js"></script>
<script type="text/javascript" src="scripts/jquery-ui.js"></script>
<script type="text/javascript" src="smart_menu/src/jquery.smartmenus.js"></script>
<!--script type="text/javascript" src="smart_menu/src/libs/jquery-loader.js"></script-->

<body>
<!-- code snippet from google for the translation -->
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#mainMenu').smartmenus({
			subMenusSubOffsetX: 1,
			subMenusSubOffsetY: -8
		});
	});
function toggleLogo(n){
	if(n){
		$('<div id="logoTmp"><img src="images/new_logo.jpg" width="100%"/></div>')
			.appendTo('body')
			.dialog({
				position: {my: 'left top', at: 'left top', of: '#mainContent'},
				open: function(event, ui){
				//	$('.ui_dialog_titlebar').hide();
				},
				close: function(event, ui){
				//	$('.ui_dialog_titlebar').show();
					$(this).remove();
				}
			});
	}
	else{
		$('#logoTmp').dialog('close');
	}
}

</script>
<div id='page'>
<div id='pageTop'>
	<img id='siteLogo' src='images/new_logo.jpg' width='13%' onmouseover='toggleLogo(1);' onmouseout='toggleLogo(0);'/>
<div>
<p id='companyName'><i>Cancer Support Now, Inc</i></p>
<span id='language_icons'>
<img id='trans_english' data-lang='English' title='translate to English' alt='translate to English' width='30%' src='images/English.gif' />
<img id='trans_spanish' data-lang='Spanish' title='translate to Spanish' alt='translate to Spanish' width='30%' src='images/Spanish.gif' />
<img id='trans_chinese' data-lang='Chinese' title='translate to Chinese' alt='translate to Chinese' width='30%' src='images/Chinese.gif' />
</span>
<!--img id='guestbookIcon' src='images/guestbook.png' title='sign our guestbook' width='5%' onclick='window.open("guestbook/index.php");'/-->
<img id='facebookIcon' src='images/facebook-icon.png' width='5%' onclick='window.open("https://www.facebook.com/cancersupportnow");' />

<p id='helpClaim'><i>
For support and information call our Helpline at 505-255-0405 or 855-955-3500.
All CSN support services are free.
</i></p>
</div>
</div>

<div id='mainContent'>
<div id='mainMenuDiv'>
<ul id='mainMenu' class='sm sm-blue'>
<li><a href='index.php'>Home</a></li>
<li><a href='index.php?gt=resource'>Resources</a></li>
<li><a href='index.php?gt=support'>Services</a></li>
<li><a href='index.php?gt=conference'>Conferences</a></li>
<li><a href='index.php?gt=reports'>Reports</a></li>
<li><a href='index.php?gt=membership'>Membership</a></li>
<li><a href='index.php?gt=board'>Board</a></li>	
<li><a href='index.php?gt=mixInfo'>MixInfo</a></li>
<li><a href='index.php?gt=donate'>Donate</a></li>
<li><a href='index.php?gt=contact'>Contact</a></li>
</ul>
</div>
<div id='content'>
<?php
	if(isset($_GET['gt'])) {
		switch($_GET['gt']) {
			case 'resource':
				include('resource.php');
				break;
			case 'conference':
				include('conference.php');
				break;
			case 'reports':
				include('reports.php');
				break;
			case 'membership':
				include('membership.php');
				break;
			case 'board':
				include('board.php');
				break;
			case 'contact':
				include('contact.php');
				break;
			case 'support':
				include('supportGroup.php');
				break;
			case 'missionStatement':
				include('missionStatement.php');
			case 'mixInfo':
				include('mixInfo.php');
				break;
			case 'donate':
				include('donate.php');
				break;
            case 'hhhsubmit':
                include('hhh_submission.php');
                break;
            case 'hhhshow':
                include('hhh_submission_show.php');
                break;
            case 'hhhreserve':
                include('hhh_reservation.php');
                break;
            case 'hhhreserveconf':
                include('hhh_reservation_confirmation.php');
                break;
            case 'hhhreserveshow':
                include('hhh_reservation_show.php');
                break;
            case 'login':
                include('login.php');
                break;
            case 'registration':
                include('registration.php');
                break;
            case 'policy':
                include('policy.php');
                break;
			default:
				include('home.php');
		}
    }
	else include('home.php');
/*
    // escape quotes (' ") and ( \ )
    function sanitizePostData(&$post) {
        foreach ($post as $key => $val) {
            // slash should be escaped first as escaping quotes adding slash
            $newVal = str_replace('\\', '\\\\', $val);
            $newVal = str_replace("'", "\'", $newVal);
            $newVal = str_replace('"', '\"', $newVal);
            $post[$key] = $newVal;
        }
    }
*/  
?>
<br/><hr/>
</div>
<div id='pageBottom'>
<?php 
if (isset($_SESSION['username'])) {
    print "<form action='index.php' method='post' style='display:inline-block'>";
    print "You are logged in as " . $_SESSION['username'] . '&nbsp;&nbsp;';
    print "<input type='submit' class='button' value='logout' id='logout' name='logout'/>";
    print "</form>";
} else {
    print "<a href='index.php?gt=login'><input type='button' value='Admin login' id='login_button'/></a>";
}
?>
    <div id='policy'> 
        <span><a href='index.php?gt=policy#pp'>Privacy Policy</a></span>
        <span><a href='index.php?gt=policy#tnc'>Terms and Conditions</a></span>
        <span><a href='index.php?gt=policy#rp'>Refund Policy</a></span>
    </div>
</div>
</div>
</div>

<script type='text/javascript'>

$('#language_icons img').click(function() {
  var lang = $(this).data('lang');
  var $frame = $('.goog-te-menu-frame:first');
  if (!$frame.size()) {
    alert("Error: Could not find Google translate frame.");
    return false;
  }
  $frame.contents().find('.goog-te-menu2-item span.text:contains('+lang+')').get(0).click();
  return false;
});
</script>

</body>
</html>
