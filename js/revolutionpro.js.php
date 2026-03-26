<?php
if (!defined('NOREQUIRESOC'))    define('NOREQUIRESOC', 1);
if (!defined('NOCSRFCHECK'))     define('NOCSRFCHECK', 1);
if (!defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL', 1);
if (!defined('NOLOGIN'))         define('NOLOGIN', 1); // File must be accessed by logon page so without login
if (!defined('NOREQUIREHTML'))   define('NOREQUIREHTML', 1);
if (!defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX', 1);
define('ISLOADEDBYSTEELSHEET', '1');
session_cache_limiter('public');

$res=0;
if (! $res && file_exists("../../main.inc.php")) $res=@include("../../main.inc.php");       // For root directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include("../../../main.inc.php"); // For "custom" 

require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";

global $langs,$db,$mysoc;

// Define css type
top_httphead('text/javascript; charset=UTF-8');

?>
// =====================================================================
// KILL ANIMSITION — prevent the full-screen loading overlay
// Must run BEFORE Site.js calls $BODY.animsition()
// =====================================================================
if (typeof jQuery !== 'undefined') {
	jQuery.fn.animsition = function() {
		this.css('opacity', '1');
		return this;
	};
}
document.addEventListener('DOMContentLoaded', function() {
	var overlays = document.querySelectorAll('.loader-overlay, .animsition-loading');
	for (var i = 0; i < overlays.length; i++) {
		overlays[i].parentNode.removeChild(overlays[i]);
	}
	document.body.style.opacity = '1';

	/* Fix liste_titre section headers — white text on theme bg */
	var s = document.createElement('style');
	s.textContent = 'tr.liste_titre th, tr.liste_titre td, tr.liste_titre_sel th, tr.liste_titre_sel td, .tagtr.liste_titre .tagtd, div.liste_titre_bydiv { background-color: transparent !important; background: transparent !important; color: #ffffff !important; border-bottom: none !important; } tr.liste_titre a, tr.liste_titre .badge, tr.liste_titre th a { color: #ffffff !important; }';
	document.head.appendChild(s);
});

$(document).ready(function(){
	if($('.info-box-module-external').length >0){
		var arrmodl=['ajaxlivesearch','bluetheme','extranetdolibarr','moderntheme','monitoring','orangetheme','payrollmod','personalizdoli','pointagemod','reservsalle','revolutionpro','taskgantt'];
		var url = '<?php echo dol_escape_js(dol_buildpath("",1)); ?>';
		$.each(arrmodl, function(index, value) {
			var img = $('.info-box-module-external .info-box-module .info-box-icon img[src*="/'+value+'/"]');
			if(!img.hasClass('ds_imgmodl')){
				img.wrap('<a class="ds_image_module_logo" target="_blank" href="https://www.novadx.pt"></a>');
				img.addClass('ds_imgmodl');
			}
		});
	}
});

// =====================================================================
// ICON MANAGER — Icons handled via pure CSS ::before overrides.
// =====================================================================

// =====================================================================
// PAGE TRANSITION SYSTEM — Creates a smooth SPA-like feel
// Progress bar + content fade-out on navigate, fade-in on load
// =====================================================================
document.addEventListener('DOMContentLoaded', function() {
	// Skip on login page and iframes
	if (document.body.classList.contains('bodylogin') || window.self !== window.top) return;

	// Create progress bar element
	var bar = document.createElement('div');
	bar.id = 'ndx-progress-bar';
	document.body.appendChild(bar);

	// Intercept link clicks for smooth navigation
	document.addEventListener('click', function(e) {
		var link = e.target.closest('a');
		if (!link) return;

		var href = link.getAttribute('href');
		// Skip: no href, hash-only, javascript:, new tab, download, mailto
		if (!href || href === '#' || href.charAt(0) === '#'
			|| href.indexOf('javascript:') === 0
			|| link.target === '_blank'
			|| link.hasAttribute('download')
			|| href.indexOf('mailto:') === 0
			|| e.ctrlKey || e.metaKey || e.shiftKey) return;

		// Skip AJAX-style links (onclick handlers, mmenu navigation)
		if (link.getAttribute('onclick') || link.classList.contains('mm-next') || link.classList.contains('mm-prev')) return;

		// Only same-origin links
		var finalUrl;
		try {
			var url = new URL(href, window.location.origin);
			if (url.origin !== window.location.origin) return;
			finalUrl = url.href;
		} catch(ex) { return; }

		// Prevent default navigation — we handle it after the animation
		e.preventDefault();
		e.stopPropagation();

		// Trigger progress bar and fade-out
		bar.className = 'ndx-loading';
		document.body.classList.add('ndx-navigating');

		// Navigate after fade-out completes (150ms)
		setTimeout(function() {
			window.location.href = finalUrl;
		}, 150);
	}, true);

	// Also trigger on form submissions
	document.addEventListener('submit', function() {
		bar.className = 'ndx-loading';
		document.body.classList.add('ndx-navigating');
	}, true);

	// On page fully loaded, finish the bar
	window.addEventListener('load', function() {
		bar.className = 'ndx-done';
		setTimeout(function() { bar.className = ''; }, 500);
	});
});

// =====================================================================
// WHITE-LABEL — Replace "Dolibarr" text and URLs with brand name
// Reads --revpro-brand-name CSS variable set by revolutionpro.css.php
// =====================================================================
document.addEventListener('DOMContentLoaded', function() {
	if (document.body && document.body.classList.contains('bodylogin')) {
		// On login page, run immediately with slight delay for DOM readiness
		setTimeout(rpWhiteLabel, 200);
	} else {
		setTimeout(rpWhiteLabel, 400);
	}
});

function rpWhiteLabel() {
	var rootStyles = getComputedStyle(document.documentElement);
	var brandName = rootStyles.getPropertyValue('--revpro-brand-name').trim();
	var brandUrl = rootStyles.getPropertyValue('--revpro-brand-url').trim();
	var companyName = rootStyles.getPropertyValue('--revpro-company-name').trim();

	// Remove wrapping quotes from CSS variables
	if (brandName) brandName = brandName.replace(/^['"]|['"]$/g, '');
	if (brandUrl) brandUrl = brandUrl.replace(/^['"]|['"]$/g, '');
	if (companyName) companyName = companyName.replace(/^['"]|['"]$/g, '');

	if (!brandName || brandName === 'none') return;
	if (!brandUrl) brandUrl = '';
	if (!companyName) companyName = brandName; // fallback

	// Helper: replace Dolibarr in a string, using company name for version strings
	function rpReplace(str) {
		// "Dolibarr 22.0.4" → "Dolisys 22.0.4" (company name + keep version)
		str = str.replace(/Dolibarr\s+(\d+\.\d+[\.\d]*)/g, companyName + ' $1');
		// "Dolibarr ERP & CRM" → brand name
		str = str.replace(/Dolibarr\s*ERP\s*(&|&amp;)?\s*CRM/gi, brandName);
		// Remaining standalone "Dolibarr"
		str = str.replace(/Dolibarr/g, brandName);
		return str;
	}

	// 1. Replace text content in DOM using TreeWalker (efficient, no re-rendering)
	// Skip the sidebar brand area — company name should stay
	var brandTextEl = document.querySelector('.navbar-brand-text');
	var walker = document.createTreeWalker(
		document.body,
		NodeFilter.SHOW_TEXT,
		null,
		false
	);
	var node;
	while (node = walker.nextNode()) {
		// Skip text inside the sidebar brand area
		if (brandTextEl && brandTextEl.contains(node)) continue;
		if (node.nodeValue && node.nodeValue.indexOf('Dolibarr') !== -1) {
			node.nodeValue = rpReplace(node.nodeValue);
		}
	}

	// 2. Replace page title (uses company name for version)
	if (document.title && document.title.indexOf('Dolibarr') !== -1) {
		document.title = rpReplace(document.title);
	}

	// 3. Sidebar brand text: keep the company name as-is
	// (The company name is set in Dolibarr company settings, not here)

	// 4. Replace ALL URLs pointing to any dolibarr.org subdomain
	//    (www.dolibarr.org, wiki.dolibarr.org, etc.)
	if (brandUrl) {
		var links = document.querySelectorAll('a[href*="dolibarr.org"]');
		links.forEach(function(a) {
			a.href = brandUrl;
		});
	}

	// 5. Replace title/alt attributes containing "Dolibarr"
	var titledEls = document.querySelectorAll('[title*="Dolibarr"]');
	titledEls.forEach(function(el) {
		el.title = rpReplace(el.title);
	});
	var altEls = document.querySelectorAll('[alt*="Dolibarr"]');
	altEls.forEach(function(el) {
		el.alt = rpReplace(el.alt);
	});

	// 6. Replace in meta tags (description, author, generator)
	document.querySelectorAll('meta[content*="Dolibarr"], meta[content*="dolibarr"]').forEach(function(meta) {
		meta.content = meta.content.replace(/Dolibarr\s*Development\s*Team/gi, brandName);
		meta.content = meta.content.replace(/Dolibarr/gi, brandName);
	});
}
<?php

// // Session FOR Menu
// $mainmenu9 = $_SESSION["mainmenu"];
// .site-menu>.site-menu-item.


global $conf,$langs;

// // Session FOR Menu
// if(!isset($_SESSION["dol_login"])){
// 	unset($_SESSION['revolutionproleftmenu']);
// }


?>
$(window).on('load',function(){
	setTimeout( function() { 
		if(!$('.site-menubar .mm-panel.mm-highest.mm-opened').is(':visible')){
			$('.site-menu-item.has-sub.active a').click(); 
		}
	}, 1600);
	$('#loader-overlay').hide();
});

/* ═══════════════════════════════════════════════════════════════════
   Chart Typography & Grid — safe string-only defaults.
   Colors come from flavor theme PHP ($theme_datacolor).
   NOTE: Home dashboard bar widgets have a pre-existing _scriptable
         crash in Chart.js v3.7.1 that is unrelated to this code.
   ═══════════════════════════════════════════════════════════════════ */
(function() {
	var wa = 0;
	var wi = setInterval(function() {
		wa++;
		if (typeof Chart === 'undefined') { if (wa >= 80) clearInterval(wi); return; }
		clearInterval(wi);

		/* Only set STRING values — never booleans/numbers to avoid _scriptable crash */
		Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
		Chart.defaults.font.weight = '500';
		Chart.defaults.color = '#64748B';
		if (Chart.defaults.scale && Chart.defaults.scale.grid) {
			Chart.defaults.scale.grid.color = 'rgba(148, 163, 184, 0.12)';
		}
		if (Chart.defaults.plugins && Chart.defaults.plugins.tooltip) {
			Chart.defaults.plugins.tooltip.backgroundColor = '#1E1B4B';
		}
	}, 150);
})();



<?php


if(isset($_SESSION["dol_login"])) die;

$ardolv = DOL_VERSION;
$ardolv = explode(".", $ardolv);
$dolvs = $ardolv[0];

if ( $dolvs > 9 ) die;


dol_include_once('/revolutionpro/class/revolutionpro.class.php');
$revolutionpro = new revolutionpro($db);
$urllogo = $revolutionpro->getLogoCompanyUrl();

// $pagecnx = $langs->trans('Hello')."!";
$pagecnx = " ";
$main_home = '';
if (! empty($conf->global->MAIN_HOME))
{
    $substitutionarray=getCommonSubstitutionArray($langs);
    complete_substitutions_array($substitutionarray, $langs);
    $texttoshow = make_substitutions($conf->global->MAIN_HOME, $substitutionarray, $langs);

	$main_home=dol_htmlcleanlastbr($texttoshow);
}
if($main_home)
	$pagecnx = $main_home;

$pagecnx = dol_escape_htmltag($pagecnx);

global $mysoc;
$societename = 'NovaDX';
if(!empty($mysoc->name))
	$societename = $mysoc->name;
elseif(!empty($conf->global->MAIN_INFO_SOCIETE_NOM))
	$societename = dol_escape_htmltag($conf->global->MAIN_INFO_SOCIETE_NOM);

$htmllogin = '<div class="page" id="pagelogindivcontent" style="display:none;" data-animsition-in="fade-in" data-animsition-out="fade-out"><div class="page-content"><div class="page-brand-info"><div class="brand"><img class="brand-img" style="height:26px;" src="'.dol_escape_js($urllogo).'" alt="..."><h2 class="brand-text font-size-40">'.dol_escape_js($societename).'</h2></div><p class="font-size-20">'.dol_escape_js($pagecnx).'</p></div><div class="page-login-main form-material" id="logindivcontent"></div></div></div>';



?>
$(window).on('load',function(){
	<?php
	$val1 = $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE1 ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE1 : 'light';
	$val3 = $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3 ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3 : 'teal';
	if($val1 == 'dark'){
		?>
		$('body #tmenu_tooltip > .site-menubar').addClass('site-menubar-dark');
		<?php
	}
	if($val3 != 'primary'){
		$num = 600;
		if($val3 == 'yellow') $num = 800;

		$bg = 'bg-'.$val3.'-'.$num;
		
		?>
		
		$('body.site-navbar-small .site-navbar').addClass('<?php echo dol_escape_js($bg); ?>');
		<?php
	}
	?>

});
<?php
?>
$(document).ready(function(){
	
	var foldorunfold = 'unfold';
	var ds_rp_menu = window.localStorage.getItem("ds_rp_menu");
	if(ds_rp_menu){
		foldorunfold = ds_rp_menu;
	}
	$('body').addClass('animsition site-navbar-small dashboard site-menubar-'+foldorunfold);
	$('html').addClass('css-menubar');
	if($('body').hasClass('bodylogin')){
		var txtlogin = $('.bodylogin div.login_center').html();
		if(txtlogin !== ''){
			$('.bodylogin div.login_center').hide();
			$('.bodylogin').prepend('<?php echo dol_escape_js($htmllogin); ?>');
			$('.bodylogin #pagelogindivcontent #logindivcontent').html(txtlogin);
			$('.bodylogin').addClass('page-login-v2 page-dark layout-full flavor-pro-login');
			$('.bodylogin #pagelogindivcontent').show();
			$('.bodylogin div.login_center').remove();
			// Flavor Pro: add Welcome heading
			$('.bodylogin #logindivcontent .login_table').before('<h2 class="flavor-welcome">Welcome back</h2>');

			// Flavor Pro: force layout via inline styles (overrides all CSS)
			var brandPanel = document.querySelector('.page-brand-info');
			var loginMain = document.querySelector('.page-login-main');
			if (brandPanel) {
				brandPanel.style.cssText = 'position:fixed!important;left:0!important;top:0!important;bottom:0!important;right:470px!important;width:auto!important;margin:0!important;padding:0!important;background:linear-gradient(160deg,#312E81 0%,#3730A3 25%,#4338CA 50%,#4F46E5 75%,#6366F1 100%)!important;display:flex!important;flex-direction:column!important;align-items:center!important;justify-content:center!important;z-index:1!important;overflow:hidden!important;';
			}
			if (loginMain) {
				loginMain.style.cssText = 'position:fixed!important;right:0!important;top:0!important;bottom:0!important;width:470px!important;background-image:none!important;background-color:#FFFFFF!important;padding:0 50px!important;display:flex!important;flex-direction:column!important;justify-content:center!important;z-index:2!important;overflow-y:auto!important;';
			}
			// Remove ::before and ::after overlays via injected style
			$('<style>').html('body.bodylogin.page-login-v2::before,body.bodylogin.page-login-v2:before{display:none!important;content:none!important;background:none!important}body.bodylogin.page-login-v2::after,body.bodylogin.page-login-v2:after,.page-login-v2.page-dark.layout-full:after{display:none!important;content:none!important;background:none!important;opacity:0!important}').appendTo('head');
		}
	}
	$('body .site-menu>.site-menu-item>a ').on('click', function(e) {
	     $(this).parent('li').find('a.mm-next').click();
	});

	// =====================================================================
	// HIDE PASSWORD EYE TOGGLE — Dolibarr core has a bug where the inline
	// script attaches the click handler twice, causing double-toggle that
	// cancels itself. Hiding the broken element until core is fixed.
	// =====================================================================
	var eyeToggle = document.getElementById('togglepassword');
	if (eyeToggle) eyeToggle.style.display = 'none';
});