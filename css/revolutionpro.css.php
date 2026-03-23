<?php
if (!defined('NOREQUIRESOC'))    define('NOREQUIRESOC', '1');
if (!defined('NOCSRFCHECK'))     define('NOCSRFCHECK', 1);
if (!defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL', 1);
if (!defined('NOLOGIN'))         define('NOLOGIN', 1); // File must be accessed by logon page so without login
if (!defined('NOREQUIREHTML'))   define('NOREQUIREHTML', 1);
if (!defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX', '1');
define('ISLOADEDBYSTEELSHEET', '1');
session_cache_limiter('public');

$res=0;
if (! $res && file_exists("../../main.inc.php")) $res=@include("../../main.inc.php");       // For root directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include("../../../main.inc.php"); // For "custom" 

require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

global $langs;
// Define css type
top_httphead('text/css');

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

$ardolv = DOL_VERSION;
$ardolv = explode(".", $ardolv);
$dolvs = $ardolv[0];

// ── NovaDX Pro: Google Fonts ──
?>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
<?php

// Add here more div for other menu entries. moduletomainmenu=array('module name'=>'name of class for div')

if (GETPOST('optioncss', 'aZ09') == 'print') { ?>
.site-menubar-unfold .page, .site-menubar-fold .page {
    margin: 0 !important;
}
<?php }

$moduletomainmenu = array(
    'user'=>'', 'syslog'=>'', 'societe'=>'companies', 'projet'=>'project', 'propale'=>'commercial', 'commande'=>'commercial',
    'produit'=>'products', 'service'=>'products', 'stock'=>'products',
    'don'=>'accountancy', 'tax'=>'accountancy', 'banque'=>'accountancy', 'facture'=>'accountancy', 'compta'=>'accountancy', 'accounting'=>'accountancy', 'adherent'=>'members', 'import'=>'tools', 'export'=>'tools', 'mailing'=>'tools',
    'contrat'=>'commercial', 'ficheinter'=>'commercial', 'ticket'=>'ticket', 'deplacement'=>'commercial',
    'fournisseur'=>'companies',
    'barcode'=>'', 'fckeditor'=>'', 'categorie'=>'',
);
$mainmenuused = 'home';
foreach ($conf->modules as $val)
{
    $mainmenuused .= ','.(isset($moduletomainmenu[$val]) ? $moduletomainmenu[$val] : $val);
}
$mainmenuusedarray = array_unique(explode(',', $mainmenuused));


if(isset($_GET['actif']) && $_GET['actif'] == 1){
    dol_include_once('/revolutionpro/core/modules/modRevolutionpro.class.php');
    require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
    dolibarr_set_const($db,'REVOLUTIONPRO_MODULES_ID', 0, 'chaine',0,'',0);
    ActivateModule("modrevolutionpro");
    print 'body .modrevolutionpro .activationmod span {'."\n";
    print '  opacity:1;'."\n";
    print '}'."\n"."\n";
    print '/*--- Actif ---*/'."\n"."\n";
}

$generic = 1;
// Put here list of menu entries when the div.mainmenu.menuentry was previously defined
$divalreadydefined = array('home', 'companies', 'products', 'mrp', 'commercial', 'externalsite', 'accountancy', 'project', 'tools', 'members', 'agenda', 'ftp', 'holiday', 'hrm', 'bookmark', 'cashdesk', 'takepos', 'ecm', 'geoipmaxmind', 'gravatar', 'clicktodial', 'paypal', 'stripe', 'webservices', 'website');
// Put here list of menu entries we are sure we don't want
$divnotrequired = array('multicurrency', 'salaries', 'ticket', 'margin', 'opensurvey', 'paybox', 'expensereport', 'incoterm', 'prelevement', 'propal', 'workflow', 'notification', 'supplier_proposal', 'cron', 'product', 'productbatch', 'expedition');
foreach ($mainmenuusedarray as $val)
{
    if (empty($val) || in_array($val, $divalreadydefined)) continue;
    if (in_array($val, $divnotrequired)) continue;
    //print "XXX".$val;

    // Search img file in module dir
    $found = 0; $url = '';
    foreach ($conf->file->dol_document_root as $dirroot)
    {
        if (file_exists($dirroot."/".$val."/img/object_".$val."_over.png"))
        {
            $url = dol_buildpath('/'.$val.'/img/object_'.$val.'_over.png', 1);
            $found = 1;
            break;
        }
		elseif (file_exists($dirroot."/".$val."/img/object_".$val.".png"))    // Retro compatibilité
		{
			$url = dol_buildpath('/'.$val.'/img/object_'.$val.'.png', 1);
			$found = 1;
			break;
		}
        elseif (file_exists($dirroot."/".$val."/img/".$val.".png"))    // Retro compatibilité
        {
            $url = dol_buildpath('/'.$val.'/img/'.$val.'.png', 1);
            $found = 1;
            break;
        }
    }


    // Img file not found

    if (!$found)
    {
        if (!defined('DISABLE_FONT_AWSOME')) {
            print "/* A mainmenu entry was found but img file ".$val.".png not found (check /".$val."/img/".$val.".png), so we use a generic one */\n";
            print 'body .site-menu-icon.mainmenu.'.$val.':before {
                content: "\f249";
            }';
        }
        else
        {
            print "/* A mainmenu entry was found but img file ".$val.".png not found (check /".$val."/img/".$val.".png), so we use a generic one */\n";
            $url = dol_buildpath($path.'/theme/eldy/img/menus/generic'.(min($generic, 4)).".png", 1);
            print "body .site-menu-icon.mainmenu.".$val." {\n";
            print "	background-image: url(".$url.") !important;\n";
				print ' height: 15px;background-size: 15px;filter: gray; -webkit-filter: grayscale(1); filter: grayscale(1);';      
            print "}\n";

            print "body .site-menubar-dark .site-menu-icon.mainmenu.".$val." {\n";
            print 'filter: brightness(0) invert(1);'."\n";
            print 'opacity: 0.5;'."\n";
            print "}\n";

            print "body .site-menu-icon.mainmenu.".$val.":before{\n";
            print "display:none !important;";
            print "}\n";
        }
        $generic++;
    }
    else
    {
        print "body .site-menu-icon.mainmenu.".$val." {\n";
        print "	background-image: url(".$url.") !important;\n";
		print ' height: 15px;background-size: 15px;filter: gray; -webkit-filter: grayscale(1); filter: grayscale(1);';
        print "}\n";
        print "body .site-menubar-dark .site-menu-icon.mainmenu.".$val." {\n";
        print 'filter: brightness(0) invert(1);'."\n";
        print 'opacity: 0.5;'."\n";
        print "}\n";
        print "body .site-menu-icon.mainmenu.".$val.":before{\n";
        print "display:none !important;";
        print "}\n";
    }
}

global $conf;

$val3 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3 : 'teal';
$val6 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE6) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE6 : 'primary';

// ── NovaDX Pro: Flavor-aligned colour palette ──
$colorsarr['primary']  = '6366F1'; // Indigo 500 (Flavor primary)
$colorsarr['blue']     = '3B82F6';
$colorsarr['brown']    = '78716C';
$colorsarr['cyan']     = '06B6D4';
$colorsarr['green']    = '10B981';
$colorsarr['grey']     = '64748B';
$colorsarr['orange']   = 'F97316';
$colorsarr['pink']     = 'EC4899';
$colorsarr['purple']   = '8B5CF6';
$colorsarr['red']      = 'EF4444';
$colorsarr['teal']     = '14B8A6';
$colorsarr['yellow']   = 'EAB308';

// Darker shades for hover/active states
$colorsDark = array(
    'primary'=>'4338CA', 'blue'=>'2563EB', 'brown'=>'57534E', 'cyan'=>'0891B2',
    'green'=>'059669', 'grey'=>'475569', 'orange'=>'EA580C', 'pink'=>'DB2777',
    'purple'=>'7C3AED', 'red'=>'DC2626', 'teal'=>'0D9488', 'yellow'=>'CA8A04',
);

$globcol = $colorsarr['primary'];
if(isset($colorsarr[$val3])){
    $globcol = $colorsarr[$val3];
}
$globcolDark = isset($colorsDark[$val3]) ? $colorsDark[$val3] : '4338CA';

$butglobcol = $colorsarr['green'];
if(isset($colorsarr[$val6])){
    $butglobcol = $colorsarr[$val6];
}
$butglobcolDark = isset($colorsDark[$val6]) ? $colorsDark[$val6] : '059669';

// ── NovaDX Pro: FORCE Flavor Indigo (override DB-stored teal/green) ──
$globcol = '6366F1';        // Flavor Indigo 500
$globcolDark = '4F46E5';    // Flavor Indigo 600
$butglobcol = '6366F1';     // Buttons: Flavor Indigo 500
$butglobcolDark = '4F46E5'; // Buttons hover: Indigo 600



$val5 = $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE5 ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE5 : 'revolutionprologin2.jpg';
// $arr = explode(".", $val5);
// if(empty($val5) || count($arr) <= 1) $val5 = 'revolutionprologin1.jpg';

$urlf = dol_buildpath('revolutionpro/img/login/revolutionprologin1.jpg',1); $def = $urlf;
if($val5 == 'revolutionprologin1.jpg' || $val5 == 'revolutionprologin2.jpg'){
    $urlf = dol_buildpath('revolutionpro/img/login/'.$val5,1);
}else{
    $modulepart = 'mycompany';
    $documenturl = DOL_URL_ROOT.'/viewimage.php';
    if (isset($conf->global->DOL_URL_ROOT_DOCUMENT_PHP)) $documenturl = $conf->global->DOL_URL_ROOT_DOCUMENT_PHP; // To use another wrapper
    $relativepath = '/logos/login/1/'.$val5; // Cas general
    $urlf = $documenturl.'?modulepart='.$modulepart.'&file='.urlencode($relativepath);
    $origf = $conf->mycompany->dir_output.'/'.$relativepath;
    if (!dol_is_file($origf)){
        $urlf = $def;
    }
}

?>
.site-navbar{background-color:#<?php echo trim($globcol); ?>}


/* Flavor Pro: disabled background images for clean login design
.bodylogin.page-login-v2 .page-login-main{
    background-image: url(<?php echo dol_buildpath('/revolutionpro/img/login/revolutionprologindesign.png', 1); ?>) !important;
}
.bodylogin.page-login-v2:before {
    background-image: url(<?php echo $urlf; ?>) !important;
}
*/
body .liste_titre .badge:not(.nochangebackground) {
    background-color: #<?php echo trim($globcol); ?>;
}
.badge-secondary, .tabs .badge {
    background-color: #<?php echo trim($globcol); ?>d4;
}
body .tabactive, body a.tab#active{
    border-top: 2px solid #<?php echo trim($globcol); ?> !important;
}
body .ui-widget-header {
    border: 1px solid #<?php echo trim($globcol); ?>;
    background: #<?php echo trim($globcol); ?>;
}
@media (max-width: 767.98px){
    .site-navbar.navbar-inverse .navbar-container{
        background-color: #<?php echo trim($globcol); ?> !important;
    }   
}

body div.liste_titre_bydiv, 
body .mc-dropdown-menu > .mc-header,
body .updf-dropdown-menu > .updf-header,
body .liste_titre div.tagtr, 
body tr.liste_titre, 
body tr.liste_titre_sel, 
body .tagtr.liste_titre, 
body .tagtr.liste_titre_sel, 
body form.liste_titre, 
body form.liste_titre_sel, 
body table.dataTable thead tr
{
    background: #<?php echo trim($globcol); ?>d9 !important;
}
body .navbar-inverse .navbar-collapse,body .navbar-inverse .navbar-form {
    border-color: #<?php echo trim($globcol); ?>;
}
body .loader-overlay {
    background: #<?php echo trim($globcol); ?>;
}
body .liste_titre_filter{
    background:#<?php echo trim($globcol); ?>61 !important
}

body .thefourboxes .card:hover {
    background-color: #<?php echo trim($globcol); ?>b5;
}
.butAction, #mainbody input.button:not(.buttongen):not(.bordertransp) 
,body.bodylogin .login_table input[type="submit"] 
{
    background: #<?php echo trim($butglobcol); ?>  !important;
    background-color: #<?php echo trim($butglobcol); ?>  !important;
    border-color: #<?php echo trim($butglobcol); ?>  !important;
    margin-bottom: 15px !important;
}


<?php
if($dolvs >= 12){
?>
    /*body span.widthpictotitle.pictotitle{ background:#<?php echo trim($globcol); ?>b5 !important }*/
<?php
}

?>
body span.widthpictotitle.pictotitle {
    background:transparent !important;
    color: #bbb !important;
    /*margin-left: 20px;*/
}

<?php if (GETPOST('optioncss', 'aZ09') == 'print') {  ?>
#mainbody .page{
   margin-left: 0;
}
#mainbody .site-footer{
    display:none;
}
<?php } ?>

body .info-box-text-module .info-box-desc .ds_url_module_desc{
    /*opacity: 1 !important;
    color: #A9AFB5 !important;*/
}
body .info-box-text-module .info-box-title .ds_url_module_name 
{
    text-transform: uppercase;
    text-decoration: none !important;
    font-weight: normal;
    margin-bottom: 3px;
    color: #000;
    cursor: default;
}
body .info-box-module .info-box-icon a.ds_image_module_logo {
    display: inline-block;
    width: 100%;
    height: 100%;
    cursor: default;
}
body .info-box-module .info-box-icon .ds_image_module_logo img {
    max-width: 60%;
}
body .info-box-content .info-box-desc .ds_url_module_desc
{
    text-decoration: none !important;
    color: #A9AFB5;
    cursor: default;
}
body table[summary="list_of_modules"] .ds_url_module_desc
{
    text-decoration: none !important;
    color: #202020;
    cursor: default;
}
body table[summary="list_of_modules"] .ds_url_module_name
{
    text-decoration: none !important;
    color: #202020;
    cursor: default;
}


/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Design System Foundation (Phase 1)
   ══════════════════════════════════════════════════════════════════ */
:root {
    /* ── NovaDX Core Palette (dynamic from PHP) ── */
    --ndx-primary-500: #<?php echo trim($globcol); ?>;
    --ndx-primary-600: #<?php echo trim($globcolDark); ?>;
    --ndx-primary-alpha-08: #<?php echo trim($globcol); ?>14;
    --ndx-primary-alpha-15: #<?php echo trim($globcol); ?>26;
    --ndx-primary-alpha-30: #<?php echo trim($globcol); ?>4D;
    --ndx-accent-500: #<?php echo trim($butglobcol); ?>;
    --ndx-accent-600: #<?php echo trim($butglobcolDark); ?>;

    /* ── NovaDX Neutrals (Slate scale) ── */
    --ndx-slate-50:  #F8FAFC;
    --ndx-slate-100: #F1F5F9;
    --ndx-slate-200: #E2E8F0;
    --ndx-slate-300: #CBD5E1;
    --ndx-slate-400: #94A3B8;
    --ndx-slate-500: #64748B;
    --ndx-slate-600: #475569;
    --ndx-slate-700: #334155;
    --ndx-slate-800: #1E293B;
    --ndx-slate-900: #0F172A;
    --ndx-white:     #FFFFFF;

    /* ── Sidebar (Indigo dark) ── */
    --ndx-sidebar-bg: #312E81;      /* Indigo 900 */
    --ndx-sidebar-bg-dark: #272462; /* Deeper Indigo */

    /* ── Spacing & Borders ── */
    --ndx-radius-xs: 4px;
    --ndx-radius-sm: 6px;
    --ndx-radius-md: 12px;
    --ndx-radius-lg: 16px;
    --ndx-radius-xl: 24px;
    --ndx-radius-full: 9999px;

    /* ── Shadows ── */
    --ndx-shadow-xs: 0 1px 2px 0 rgba(0,0,0,0.05);
    --ndx-shadow-sm: 0 1px 3px 0 rgba(0,0,0,0.06), 0 1px 2px -1px rgba(0,0,0,0.06);
    --ndx-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.03);
    --ndx-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.02);
    --ndx-shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.06), 0 8px 10px -6px rgba(0,0,0,0.03);

    /* ── Transitions ── */
    --ndx-transition-fast: 150ms ease;
    --ndx-transition-normal: 250ms ease;
    --ndx-transition-slow: 350ms ease;

    /* ── Dolibarr native CSS vars (mapped to NovaDX) ── */
    --colorbackhmenu1: var(--ndx-primary-500);
    --colorbackvmenu1: var(--ndx-white);
    --colorbacktitle1: var(--ndx-slate-100);
    --colorbacktabcard1: var(--ndx-white);
    --colorbacktabactive: var(--ndx-slate-100);
    --colorbacklineimpair1: var(--ndx-white);
    --colorbacklineimpair2: var(--ndx-white);
    --colorbacklinepair1: var(--ndx-slate-50);
    --colorbacklinepair2: var(--ndx-slate-50);
    --colorbacklinepairhover: var(--ndx-primary-alpha-08);
    --colorbacklinepairchecked: var(--ndx-primary-alpha-15);
    --colorbacklinebreak: var(--ndx-slate-100);
    --colorbackbody: var(--ndx-slate-50);
    --colortexttitlenotab: var(--ndx-slate-700);
    --colortexttitlenotab2: var(--ndx-primary-600);
    --colortexttitle: var(--ndx-slate-800);
    --colortext: var(--ndx-slate-800);
    --colortextlink: var(--ndx-primary-500);
    --colortextbackhmenu: var(--ndx-white);
    --colortextbackvmenu: var(--ndx-slate-700);
    --listetotal: var(--ndx-slate-500);
    --inputbackgroundcolor: var(--ndx-white);
    --inputbordercolor: var(--ndx-slate-300);
    --tooltipbgcolor: rgba(255, 255, 255, 0.98);
    --tooltipfontcolor: var(--ndx-slate-700);
    --oddevencolor: var(--ndx-slate-800);
    --colorboxstatsborder: var(--ndx-slate-200);
    --dolgraphbg: rgba(255,255,255,0);
    --fieldrequiredcolor: var(--ndx-primary-600);
    --colortextbacktab: var(--ndx-slate-800);
    --colorboxiconbg: var(--ndx-slate-100);
    --refidnocolor: var(--ndx-slate-600);
    --tableforfieldcolor: var(--ndx-slate-500);
    --amountremaintopaycolor: #B91C1C;
    --amountpaymentcomplete: #059669;
    --amountremaintopaybackcolor: none;
}

/* ── NovaDX Pro: Global Typography ── */
body, h1, h2, h3, h4, h5, h6, p, a, span, div,
td, th, input, button, select, textarea, label,
.site-menu-title, .site-footer, .navbar-brand-text {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
}

/* ── NovaDX Pro: SaaS Background ── */
body:not(.bodylogin) {
    background-color: var(--ndx-slate-50) !important;
    color: var(--ndx-slate-800) !important;
}

/* ── NovaDX Pro: Smooth transitions on interactive elements ── */
a, button, input, select, .site-menu-item, .card, .btn,
.butAction, .butActionDelete, .badge, .nav-link {
    transition: all var(--ndx-transition-fast) !important;
}
/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Sidebar Modernization (Phase 2)
   ══════════════════════════════════════════════════════════════════ */

/* 1. Fundo Global do Menu Lateral — INDIGO */
.mm-menu, .site-menubar {
    background-color: var(--ndx-sidebar-bg) !important;
    color: #FFFFFF !important;
    border-right: 1px solid rgba(255,255,255,0.08) !important;
    box-shadow: 2px 0 10px rgba(0,0,0,0.15) !important;
}

/* 2. Links e Tipografia */
.mm-listview > li > a, .site-menu-item > a {
    font-family: 'Inter', sans-serif !important;
    font-size: 0.9rem !important;
    color: rgba(255, 255, 255, 0.85) !important;
    padding: 12px 20px !important;
    transition: all 0.2s ease !important;
    border-bottom: none !important;
}

/* 3. Ícones do Menu */
.site-menu-icon, .mm-listview > li > a i {
    color: rgba(255, 255, 255, 0.9) !important;
    font-size: 1.1rem !important;
    margin-right: 12px !important;
}

/* 4. Efeito Hover (Premium) */
.mm-listview > li > a:hover, .site-menu-item > a:hover {
    color: #FFFFFF !important;
    background-color: rgba(255, 255, 255, 0.05) !important;
}

/* 5. Item Ativo (O "Toque" NovaDX) */
.mm-listview > li.mm-selected > a, .site-menu-item.active > a {
    background-color: var(--ndx-primary-alpha-08) !important;
    color: #FFFFFF !important;
    border-left: 4px solid var(--ndx-primary-500) !important;
    font-weight: 500 !important;
}

/* 6. Títulos de Categoria (Submenus) */
.mm-divider, .site-menu-category {
    background-color: transparent !important;
    color: rgba(255, 255, 255, 0.4) !important;
    text-transform: uppercase !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.05em !important;
    padding: 20px 20px 8px 20px !important;
    border-bottom: none !important;
}

/* 7. Submenus */
.site-menu-sub {
    background-color: rgba(0, 0, 0, 0.15) !important;
}
.site-menu-sub .site-menu-item > a {
    padding-left: 48px !important;
    font-size: 0.85rem !important;
    color: rgba(255, 255, 255, 0.55) !important;
}
.site-menu-sub .site-menu-item > a:hover {
    color: rgba(255, 255, 255, 0.9) !important;
    background-color: rgba(255, 255, 255, 0.03) !important;
}

/* 8. Ocultar blocos de perfil/logos antigos na sidebar */
.site-menubar-header {
    display: none !important;
}

/* 9. Scrollbar dark styling */
.site-menubar::-webkit-scrollbar {
    width: 4px;
}
.site-menubar::-webkit-scrollbar-track {
    background: transparent;
}
.site-menubar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.1);
    border-radius: var(--ndx-radius-full);
}
.site-menubar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.2);
}

/* 10. Menu arrow indicators (dark theme) */
.site-menu-arrow {
    color: rgba(255,255,255,0.3) !important;
}
.site-menu-item.active > a .site-menu-arrow,
.site-menu-item:hover > a .site-menu-arrow {
    color: rgba(255,255,255,0.6) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 2.1: mmenu Panels & Topbar Fixes
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. mmenu Sliding Subpanels (Indigo) ── */
.mm-panels, .mm-panel {
    background-color: var(--ndx-sidebar-bg) !important;
    color: #FFFFFF !important;
}

/* mmenu navbar (back button bar) */
.mm-navbar {
    background-color: var(--ndx-sidebar-bg) !important;
    border-bottom: 1px solid rgba(255,255,255,0.05) !important;
}
.mm-navbar a, .mm-navbar .mm-title {
    color: var(--ndx-slate-100) !important;
    font-family: 'Inter', sans-serif !important;
    font-weight: 500 !important;
}

/* mmenu back arrow (<<<) */
.mm-btn:before, .mm-btn:after {
    border-color: rgba(255,255,255,0.7) !important;
}

/* mmenu list items inside panels */
.mm-listview > li {
    border-color: rgba(255,255,255,0.04) !important;
}

/* ── 2. Topbar — White SaaS Style ── */
.site-navbar {
    background-color: #FFFFFF !important;
    border-bottom: 1px solid var(--ndx-slate-100) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
}

/* Logo area — keeps dark for sidebar continuity */
.site-navbar .navbar-header {
    background-color: var(--ndx-sidebar-bg) !important;
    border-right: 1px solid rgba(255,255,255,0.05) !important;
}
.site-navbar .navbar-header .navbar-brand {
    color: var(--ndx-white) !important;
}

/* Topbar icons and links */
.site-navbar .navbar-nav > li > a {
    color: var(--ndx-slate-600) !important;
}
.site-navbar .navbar-nav > li > a:hover {
    color: var(--ndx-primary-500) !important;
    background-color: var(--ndx-slate-50) !important;
}
.site-navbar .icon {
    color: var(--ndx-slate-600) !important;
}
.site-navbar .icon:hover {
    color: var(--ndx-primary-500) !important;
}

/* Hamburger menu icon on white bar — Indigo + restore 3 bars */
.site-navbar .hamburger .hamburger-bar,
.site-navbar .hamburger .hamburger-bar:before,
.site-navbar .hamburger .hamburger-bar:after {
    background-color: var(--ndx-primary-500) !important;
    height: 2px !important;
}
/* Restore the top and bottom bars (hamburger-close sets content:none) */
.site-navbar .hamburger .hamburger-bar:before,
.site-navbar .hamburger .hamburger-bar:after {
    content: "" !important;
    display: block !important;
    position: absolute !important;
    width: 100% !important;
    background-color: var(--ndx-primary-500) !important;
    height: 2px !important;
    left: 0 !important;
}
.site-navbar .hamburger .hamburger-bar:before {
    top: -6px !important;
}
.site-navbar .hamburger .hamburger-bar:after {
    bottom: -6px !important;
    top: auto !important;
}
/* Ensure the bar container has relative positioning */
.site-navbar .hamburger .hamburger-bar {
    position: relative !important;
    display: block !important;
}
/* Hover */
.site-navbar .hamburger:hover .hamburger-bar,
.site-navbar .hamburger:hover .hamburger-bar:before,
.site-navbar .hamburger:hover .hamburger-bar:after {
    background-color: var(--ndx-primary-600) !important;
}

/* When sidebar is OPEN/unfold → revert to arrow (hide extra hamburger bars) */
body.site-menubar-unfold .site-navbar .hamburger .hamburger-bar:before,
body.site-menubar-unfold .site-navbar .hamburger .hamburger-bar:after {
    content: none !important;
    display: none !important;
}
/* Arrow tip (chevron) on unfold — force Indigo */
body.site-menubar-unfold .site-navbar .hamburger-arrow-left::before,
body.site-menubar-unfold .site-navbar .hamburger-arrow-left::after,
.site-menubar-unfold [data-toggle="menubar"] .hamburger-arrow-left::before,
.site-menubar-unfold [data-toggle="menubar"] .hamburger-arrow-left::after {
    background-color: var(--ndx-primary-500) !important;
    height: 2px !important;
}

/* Search icon area on topbar */
.site-navbar .navbar-form .form-control {
    background-color: var(--ndx-slate-50) !important;
    border: 1px solid var(--ndx-slate-200) !important;
    border-radius: var(--ndx-radius-md) !important;
    color: var(--ndx-slate-800) !important;
}

/* Badge notifications (keep vibrant) */
.site-navbar .badge-danger {
    background-color: #EF4444 !important;
}

/* ── 3. Residual Header Blocks Cleanup ── */
.site-menubar-header, .site-menubar-user {
    display: none !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 2.2: Specificity Hotfixes
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Icon Font Protection (FontAwesome first, Material Design for md- classes) ── */
[class*="fa-"], .fa, .fas, .far, .fab,
.icon, .glyphicon, .brand-icons,
.site-menu-icon {
    font-family: "Font Awesome 5 Free", "Font Awesome 5 Pro", "Font Awesome 5 Brands", "FontAwesome", "Material Design Iconic Font", "Glyphicons Halflings", "brand-icons" !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-transform: none !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
/* FA5 Solid needs weight 900 to render glyphs */
.fas, .fa-solid {
    font-weight: 900 !important;
}
/* FA5 Regular needs weight 400 */
.far, .fa-regular {
    font-weight: 400 !important;
}
/* FA5 Brands needs weight 400 */
.fab, .fa-brands {
    font-weight: 400 !important;
}
/* Material Design icons — keep Material Design font first for md- classes */
[class*="md-"], .zmdi,
.mm-navbar .mm-btn:before, .mm-navbar .mm-btn:after {
    font-family: "Material Design Iconic Font", "Material Design Icons", "FontAwesome", "Font Awesome 5 Free" !important;
    font-style: normal !important;
    font-weight: normal !important;
    font-variant: normal !important;
    text-transform: none !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
/* Ensure mmenu title text still uses Inter */
.mm-navbar .mm-title {
    font-family: 'Inter', sans-serif !important;
}

/* ── 2. Topbar Dynamic Colour Override ── */
nav.site-navbar,
.site-navbar.navbar-inverse,
.site-navbar[class*="bg-"],
nav[class*="bg-teal"],
nav[class*="bg-primary"],
nav[class*="bg-blue"],
nav[class*="bg-green"],
nav[class*="bg-red"],
nav[class*="bg-purple"],
nav[class*="bg-orange"],
nav[class*="bg-cyan"],
.navbar-inverse.bg-teal-600,
.navbar.bg-teal-600 {
    background-color: #FFFFFF !important;
    background-image: none !important;
    border-bottom: 1px solid var(--ndx-slate-100) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
}
/* Logo area stays dark */
nav.site-navbar .navbar-header,
.site-navbar.navbar-inverse .navbar-header,
.site-navbar[class*="bg-"] .navbar-header {
    background-color: var(--ndx-sidebar-bg) !important;
}
/* Topbar text/icons dark on white bg */
.site-navbar .navbar-nav > li > a,
.site-navbar .navbar-nav > li > a .icon,
.site-navbar.navbar-inverse .navbar-nav > li > a {
    color: var(--ndx-slate-600) !important;
}
.site-navbar .navbar-nav > li > a:hover,
.site-navbar.navbar-inverse .navbar-nav > li > a:hover {
    color: var(--ndx-primary-500) !important;
    background-color: var(--ndx-slate-50) !important;
}
/* Hamburger bars on white — Indigo */
.site-navbar .hamburger .hamburger-bar,
.site-navbar .hamburger .hamburger-bar:before,
.site-navbar .hamburger .hamburger-bar:after,
.site-navbar.navbar-inverse .hamburger .hamburger-bar,
.site-navbar.navbar-inverse .hamburger .hamburger-bar:before,
.site-navbar.navbar-inverse .hamburger .hamburger-bar:after {
    background-color: var(--ndx-primary-500) !important;
}
.site-navbar .hamburger:hover .hamburger-bar,
.site-navbar .hamburger:hover .hamburger-bar:before,
.site-navbar .hamburger:hover .hamburger-bar:after,
.site-navbar.navbar-inverse .hamburger:hover .hamburger-bar,
.site-navbar.navbar-inverse .hamburger:hover .hamburger-bar:before,
.site-navbar.navbar-inverse .hamburger:hover .hamburger-bar:after {
    background-color: var(--ndx-primary-600) !important;
}

/* ── 3. mmenu Selected State (high specificity) ── */
.mm-listitem.mm-selected > a,
.mm-listitem.mm-selected > span,
.mm-listview > li.mm-selected > a,
.mm-listview > li.mm-selected > .mm-panel,
.site-menu-item.active > a,
.site-menu-item.open > a {
    background-color: rgba(79, 70, 229, 0.1) !important;
    color: #FFFFFF !important;
    border-left: 4px solid var(--ndx-primary-500) !important;
    font-weight: 500 !important;
}

/* ── 4. Sidebar Footer (Gear / Power icons) ── */
.site-menubar-footer {
    background-color: var(--ndx-sidebar-bg-dark) !important;
    border-top: 1px solid rgba(255,255,255,0.05) !important;
}
.site-menubar-footer > a {
    color: var(--ndx-slate-400) !important;
    background: transparent !important;
}
.site-menubar-footer > a:hover {
    color: #FFFFFF !important;
    background-color: rgba(255,255,255,0.05) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 3: Brand Identity & Dashboard Modernization
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Indigo Primary Override (exclude sidebar/mmenu) ── */
.text-primary,
a:not(.butAction):not(.butActionDelete):not(.dropdown-item):not(.site-menu-item > a):not(.mm-listview > li > a):not(.site-menubar a):not(.mm-navbar a):not(.site-menubar-footer a) {
    color: var(--ndx-primary-500) !important;
}
a:not(.butAction):not(.butActionDelete):not(.dropdown-item):not(.site-menu-item > a):not(.mm-listview > li > a):not(.site-menubar a):not(.mm-navbar a):not(.site-menubar-footer a):hover {
    color: var(--ndx-primary-600) !important;
    text-decoration: none !important;
}
.bg-primary, .btn-primary, .label-primary, .badge-primary {
    background-color: var(--ndx-primary-500) !important;
    border-color: var(--ndx-primary-500) !important;
    color: #FFFFFF !important;
}
.btn-primary:hover, .btn-primary:active, .btn-primary:focus {
    background-color: var(--ndx-primary-600) !important;
    border-color: var(--ndx-primary-600) !important;
}

/* Dolibarr action buttons */
.butAction {
    background-color: var(--ndx-primary-500) !important;
    color: #FFFFFF !important;
    border: none !important;
    border-radius: var(--ndx-radius-sm) !important;
    padding: 8px 20px !important;
    font-weight: 500 !important;
}
.butAction:hover {
    background-color: var(--ndx-primary-600) !important;
    box-shadow: var(--ndx-shadow-md) !important;
}
.butActionDelete {
    background-color: #EF4444 !important;
    color: #FFFFFF !important;
    border: none !important;
    border-radius: var(--ndx-radius-sm) !important;
    padding: 8px 20px !important;
    font-weight: 500 !important;
}
.butActionDelete:hover {
    background-color: #DC2626 !important;
}

/* ── 2. Dashboard Widgets (Clean White Cards) ── */
.widget {
    background-color: #FFFFFF !important;
    border-radius: var(--ndx-radius-md) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
    border: 1px solid var(--ndx-slate-100) !important;
    margin-bottom: 20px !important;
    overflow: hidden !important;
}
/* Remove rainbow bg-* from icon blocks */
.widget [class*="bg-"] {
    background-color: var(--ndx-slate-50) !important;
    color: var(--ndx-primary-500) !important;
    border-right: 1px solid var(--ndx-slate-100) !important;
}
/* Force dark text inside widgets */
.widget .white, .widget [class*="white"] {
    color: var(--ndx-slate-800) !important;
}
.widget .counter-number {
    font-weight: 700 !important;
    color: var(--ndx-slate-900) !important;
}
.widget .counter-label {
    color: var(--ndx-slate-500) !important;
    font-size: 0.85rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.03em !important;
}
/* Widget icon size */
.widget .widget-icon i, .widget .icon {
    font-size: 2rem !important;
}

/* ── 3. Panels & Tables ── */
.panel {
    background-color: #FFFFFF !important;
    border: none !important;
    border-radius: var(--ndx-radius-md) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
    margin-bottom: 24px !important;
}
/* Panel heading (remove coloured backgrounds) */
.panel-heading, .panel-title,
.panel-heading[class*="bg-"],
.panel-heading.bg-teal-600,
.panel-heading.bg-primary {
    background-color: #FFFFFF !important;
    background-image: none !important;
    color: var(--ndx-slate-800) !important;
    border-bottom: 1px solid var(--ndx-slate-100) !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    padding: 16px 20px !important;
}
.panel-title .icon {
    color: var(--ndx-primary-500) !important;
    margin-right: 8px !important;
}
.panel-body {
    padding: 20px !important;
}

/* ── 4. Dolibarr Tabs & Tables ── */
div.tabs, div.tabBar {
    background-color: #FFFFFF !important;
    border-radius: var(--ndx-radius-md) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
}
.oddeven:hover td {
    background-color: var(--ndx-primary-alpha-08) !important;
}
table.liste th, table.noborder th {
    background-color: var(--ndx-slate-50) !important;
    color: var(--ndx-slate-700) !important;
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    border-bottom: 2px solid var(--ndx-slate-200) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 3.1: NUCLEAR HOTFIX
   Obliterar as cores nativas do RevolutionPro/Bootstrap
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Destruição do Arco-íris (TODAS as classes bg-*-NNN) ── */
.bg-red-100, .bg-red-200, .bg-red-300, .bg-red-400, .bg-red-500, .bg-red-600, .bg-red-700, .bg-red-800, .bg-red-900,
.bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400, .bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-teal-100, .bg-teal-200, .bg-teal-300, .bg-teal-400, .bg-teal-500, .bg-teal-600, .bg-teal-700, .bg-teal-800, .bg-teal-900,
.bg-green-100, .bg-green-200, .bg-green-300, .bg-green-400, .bg-green-500, .bg-green-600, .bg-green-700, .bg-green-800, .bg-green-900,
.bg-orange-100, .bg-orange-200, .bg-orange-300, .bg-orange-400, .bg-orange-500, .bg-orange-600, .bg-orange-700, .bg-orange-800, .bg-orange-900,
.bg-purple-100, .bg-purple-200, .bg-purple-300, .bg-purple-400, .bg-purple-500, .bg-purple-600, .bg-purple-700, .bg-purple-800, .bg-purple-900,
.bg-cyan-100, .bg-cyan-200, .bg-cyan-300, .bg-cyan-400, .bg-cyan-500, .bg-cyan-600, .bg-cyan-700, .bg-cyan-800, .bg-cyan-900,
.bg-amber-100, .bg-amber-200, .bg-amber-300, .bg-amber-400, .bg-amber-500, .bg-amber-600, .bg-amber-700, .bg-amber-800, .bg-amber-900,
.bg-brown-100, .bg-brown-200, .bg-brown-300, .bg-brown-400, .bg-brown-500, .bg-brown-600, .bg-brown-700, .bg-brown-800, .bg-brown-900,
.bg-pink-100, .bg-pink-200, .bg-pink-300, .bg-pink-400, .bg-pink-500, .bg-pink-600, .bg-pink-700, .bg-pink-800, .bg-pink-900,
.bg-grey-100, .bg-grey-200, .bg-grey-300, .bg-grey-400, .bg-grey-500, .bg-grey-600, .bg-grey-700, .bg-grey-800, .bg-grey-900,
.bg-yellow-100, .bg-yellow-200, .bg-yellow-300, .bg-yellow-400, .bg-yellow-500, .bg-yellow-600, .bg-yellow-700, .bg-yellow-800, .bg-yellow-900,
.bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400, .bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900,
.bg-primary-100, .bg-primary-200, .bg-primary-300, .bg-primary-400, .bg-primary-500, .bg-primary-600, .bg-primary-700, .bg-primary-800, .bg-primary-900,
.bg-info-100, .bg-info-200, .bg-info-300, .bg-info-400, .bg-info-500, .bg-info-600, .bg-info-700, .bg-info-800, .bg-info-900,
.bg-success-100, .bg-success-200, .bg-success-300, .bg-success-400, .bg-success-500, .bg-success-600, .bg-success-700, .bg-success-800, .bg-success-900,
.bg-warning-100, .bg-warning-200, .bg-warning-300, .bg-warning-400, .bg-warning-500, .bg-warning-600, .bg-warning-700, .bg-warning-800, .bg-warning-900,
.bg-danger-100, .bg-danger-200, .bg-danger-300, .bg-danger-400, .bg-danger-500, .bg-danger-600, .bg-danger-700, .bg-danger-800, .bg-danger-900 {
    background-color: var(--ndx-slate-50) !important;
    background-image: none !important;
    color: var(--ndx-primary-500) !important;
}

/* Also catch any bg-COLOR without number suffix */
.bg-red, .bg-blue, .bg-teal, .bg-green, .bg-orange, .bg-purple, .bg-cyan,
.bg-amber, .bg-brown, .bg-pink, .bg-grey, .bg-yellow, .bg-indigo,
.bg-primary, .bg-info, .bg-success, .bg-warning, .bg-danger {
    background-color: var(--ndx-slate-50) !important;
    background-image: none !important;
    color: var(--ndx-primary-500) !important;
}

/* EXCEPTION: Keep topbar navbar-header dark */
nav.site-navbar .navbar-header[class*="bg-"],
.site-navbar .navbar-header {
    background-color: var(--ndx-sidebar-bg) !important;
    color: var(--ndx-white) !important;
}
/* EXCEPTION: Keep sidebar Indigo */
.site-menubar[class*="bg-"],
.site-menubar {
    background-color: var(--ndx-sidebar-bg) !important;
    color: #FFFFFF !important;
}

/* ── 2. Panel Heading Nuclear Override ── */
.panel > .panel-heading,
.panel > .panel-heading[class*="bg-"],
.panel-heading.bg-teal-600,
.panel-heading.bg-teal-400,
.panel-heading.bg-primary-600,
div.panel > div.panel-heading {
    background-color: #FFFFFF !important;
    background-image: none !important;
    border-bottom: 1px solid var(--ndx-slate-100) !important;
    padding: 16px 20px !important;
}
.panel-heading .panel-title,
.panel-heading h1, .panel-heading h2, .panel-heading h3, .panel-heading h4 {
    color: var(--ndx-slate-800) !important;
    font-family: 'Inter', sans-serif !important;
    font-weight: 600 !important;
    text-transform: none !important;
}

/* ── 3. Web Icons Font Protection (.wb-*) ── */
[class*="wb-"], .wb-icon {
    font-family: "Web Icons" !important;
    font-style: normal !important;
    font-weight: normal !important;
    color: var(--ndx-primary-500) !important;
    font-size: 2rem !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ── 4. Force dark text on light backgrounds ── */
.white, [class*="color-white"], .text-white {
    color: var(--ndx-slate-800) !important;
}
/* Keep white text for items that need it (navbar-header, sidebar, buttons) */
.site-navbar .navbar-header .white,
.site-navbar .navbar-header [class*="color-white"],
.site-menubar .white,
.btn-primary .white, .butAction .white,
.badge .white, .label .white {
    color: #FFFFFF !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — NUCLEAR: Sidebar Text WHITE (last = wins cascade)
   ══════════════════════════════════════════════════════════════════ */
.site-menubar a,
.site-menubar .site-menu-title,
.site-menubar .site-menu-item > a,
.site-menubar .site-menu-item > a span,
.mm-menu a,
.mm-listview > li > a,
.mm-listview > li > a span,
.mm-navbar a,
.mm-navbar .mm-title,
.site-menubar-footer > a {
    color: rgba(255, 255, 255, 0.85) !important;
}
.site-menubar a:hover,
.site-menubar .site-menu-item > a:hover,
.mm-listview > li > a:hover {
    color: #FFFFFF !important;
}
/* Active item stays white */
.site-menubar .site-menu-item.active > a,
.site-menubar .site-menu-item.active > a span,
.mm-listview > li.mm-selected > a {
    color: #FFFFFF !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4: Widget Sweep & Login Modernization
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Widget Inline Style Override ── */
.widget [style*="background-color"],
.widget [style*="background"] {
    background-color: var(--ndx-slate-50) !important;
    background: var(--ndx-slate-50) !important;
}

/* Rescue icons inside color blocks */
.widget .icon, .widget i, .widget [class*="wb-"], .widget [class*="md-"],
.widget [class*="fa-"], .widget .fa {
    color: var(--ndx-primary-500) !important;
    background: transparent !important;
    text-shadow: none !important;
    font-size: 2.2rem !important;
}

/* Force dark text on now-light widgets */
.widget-title, .widget-content [class*="white"],
.widget-content [style*="color"],
.widget [style*="color: white"], .widget [style*="color:#fff"],
.widget [style*="color: #fff"], .widget [style*="color:white"] {
    color: var(--ndx-slate-800) !important;
}

/* Widget links — Indigo on white */
.widget a {
    color: var(--ndx-primary-500) !important;
}
.widget a:hover {
    color: var(--ndx-primary-600) !important;
}

/* Widget counter styling */
.widget .counter, .widget .counter-number {
    color: var(--ndx-slate-900) !important;
    font-weight: 700 !important;
}

/* ── 2. Login Screen (page-login-v2) ── */

/* Base page */
body.page-login-v2, body.bodylogin {
    background-color: var(--ndx-slate-50) !important;
}

/* Left panel — dark branding */
.page-login-v2 .page-brand-info,
body.bodylogin.page-login-v2:before {
    background-color: var(--ndx-sidebar-bg) !important;
    background-blend-mode: multiply;
}

/* Right panel — white form card */
.page-login-v2 .page-login-main {
    background: #FFFFFF !important;
    border-radius: var(--ndx-radius-lg) !important;
    box-shadow: var(--ndx-shadow-xl) !important;
    padding: 40px !important;
    margin: auto !important;
}

/* Login form inputs */
.page-login-v2 input.form-control,
body.bodylogin input.form-control,
body.bodylogin .login_table input[type="text"],
body.bodylogin .login_table input[type="password"] {
    border-radius: var(--ndx-radius-sm) !important;
    border: 1px solid var(--ndx-slate-200) !important;
    padding: 12px 16px !important;
    height: auto !important;
    background-color: var(--ndx-slate-50) !important;
    box-shadow: none !important;
    font-family: 'Inter', sans-serif !important;
    font-size: 0.9rem !important;
    transition: all var(--ndx-transition-fast) !important;
}
.page-login-v2 input.form-control:focus,
body.bodylogin input.form-control:focus,
body.bodylogin .login_table input:focus {
    border-color: var(--ndx-primary-500) !important;
    background-color: #FFFFFF !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    outline: none !important;
}

/* Login button */
.page-login-v2 .btn-primary,
body.bodylogin .login_table input[type="submit"],
body.bodylogin input.button {
    background-color: var(--ndx-primary-500) !important;
    border-color: var(--ndx-primary-500) !important;
    border-radius: var(--ndx-radius-sm) !important;
    padding: 12px !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    font-family: 'Inter', sans-serif !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    color: #FFFFFF !important;
    cursor: pointer !important;
    transition: all var(--ndx-transition-fast) !important;
    width: 100% !important;
}
.page-login-v2 .btn-primary:hover,
body.bodylogin .login_table input[type="submit"]:hover,
body.bodylogin input.button:hover {
    background-color: var(--ndx-primary-600) !important;
    border-color: var(--ndx-primary-600) !important;
    box-shadow: var(--ndx-shadow-md) !important;
}

/* Login logo area */
.page-login-v2 .brand-text,
body.bodylogin .login-logo {
    font-family: 'Inter', sans-serif !important;
    font-weight: 700 !important;
    color: #FFFFFF !important;
}

/* Login form labels */
.page-login-v2 label,
body.bodylogin label {
    color: var(--ndx-slate-600) !important;
    font-family: 'Inter', sans-serif !important;
    font-weight: 500 !important;
    font-size: 0.85rem !important;
}

/* Login footer links */
.page-login-v2 a,
body.bodylogin .login-forgot a {
    color: var(--ndx-primary-500) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4.1: WIDGET WIPE (scoped to .page-content)
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Kill widget background (class + inline) ── */
.page-content .widget [class*="bg-"],
.page-content .widget [style*="background"],
.page-content .widget [style*="background-color"],
.page-content .panel [class*="bg-"],
.page-content .panel [style*="background"] {
    background-color: var(--ndx-slate-50) !important;
    background-image: none !important;
    background: var(--ndx-slate-50) !important;
}

/* ── 2. Kill swoosh curves on top 4 cards ── */
.page-content .widget::before,
.page-content .widget::after,
.page-content .widget .overlay,
.page-content .widget .widget-header::before,
.page-content .widget .widget-header::after {
    display: none !important;
    background-image: none !important;
    background: none !important;
    content: none !important;
}

/* ── 3. Force white card base ── */
.page-content .widget {
    background-color: #FFFFFF !important;
    border: 1px solid var(--ndx-slate-100) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
    border-radius: var(--ndx-radius-md) !important;
    overflow: hidden !important;
}

/* ── 4. Rescue icons — Indigo on transparent ── */
.page-content .widget .icon,
.page-content .widget i,
.page-content .widget [class*="wb-"],
.page-content .widget [class*="md-"],
.page-content .widget [class*="fa-"] {
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    background: transparent !important;
    text-shadow: none !important;
    font-size: 2.2rem !important;
    opacity: 1 !important;
}

/* ── 5. Force dark text (was white on coloured bg) ── */
.page-content .widget .white,
.page-content .widget [class*="color-white"],
.page-content .widget [style*="color: white"],
.page-content .widget [style*="color:#fff"],
.page-content .widget [style*="color: #fff"],
.page-content .widget [style*="color:white"],
.page-content .widget [style*="color: rgb(255"] {
    color: var(--ndx-slate-800) !important;
    -webkit-text-fill-color: var(--ndx-slate-800) !important;
}

/* Widget link text — Indigo */
.page-content .widget a:not(.site-menubar a) {
    color: var(--ndx-primary-500) !important;
}
.page-content .widget a:hover {
    color: var(--ndx-primary-600) !important;
}

/* Counter numbers */
.page-content .widget .counter,
.page-content .widget .counter-number,
.page-content .widget h2,
.page-content .widget h3,
.page-content .widget h4 {
    color: var(--ndx-slate-900) !important;
    -webkit-text-fill-color: var(--ndx-slate-900) !important;
    font-weight: 700 !important;
}

/* ── 6. Top 4 summary cards (.thefourboxes) ── */
.thefourboxes .card,
.thefourboxes .card [class*="bg-"],
.thefourboxes .card [style*="background"] {
    background: #FFFFFF !important;
    background-color: #FFFFFF !important;
    background-image: none !important;
    border: 1px solid var(--ndx-slate-100) !important;
    border-radius: var(--ndx-radius-md) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
}
.thefourboxes .card::before,
.thefourboxes .card::after {
    display: none !important;
    content: none !important;
}
.thefourboxes .card .icon,
.thefourboxes .card i {
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    background: transparent !important;
}
.thefourboxes .card .white,
.thefourboxes .card [style*="color"] {
    color: var(--ndx-slate-800) !important;
    -webkit-text-fill-color: var(--ndx-slate-800) !important;
}

/* ── 7. Login refinements ── */
body.page-login-v2 {
    background-color: var(--ndx-slate-50) !important;
}
.page-login-v2 .page-brand-info {
    background-color: var(--ndx-sidebar-bg) !important;
    background-blend-mode: multiply;
}
.page-login-v2 .page-login-main {
    background: #FFFFFF !important;
    border-radius: var(--ndx-radius-lg) !important;
    box-shadow: var(--ndx-shadow-xl) !important;
}
.page-login-v2 input.form-control {
    border-radius: var(--ndx-radius-sm) !important;
    border: 1px solid var(--ndx-slate-200) !important;
    background-color: var(--ndx-slate-50) !important;
}
.page-login-v2 input.form-control:focus {
    border-color: var(--ndx-primary-500) !important;
    background-color: #FFFFFF !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4.2: MAX SPECIFICITY (body #mainbody #id-right)
   Esmagar estilos inline injetados via AJAX no Dashboard
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Destruir fundos coloridos inline ── */
body #mainbody #id-right div[style*="background-color"],
body #mainbody #id-right div[style*="background:"],
body #mainbody #id-right div[class*="bg-"],
body #mainbody #id-right td[style*="background-color"],
body #mainbody #id-right td[style*="background:"],
body #mainbody #id-right span[style*="background-color"],
body #mainbody #id-right span[style*="background:"] {
    background-color: var(--ndx-slate-50) !important;
    background-image: none !important;
    background: var(--ndx-slate-50) !important;
}

/* ── 2. Matar bordas coloridas inline ── */
body #mainbody #id-right div[style*="border-color"],
body #mainbody #id-right div[style*="borderLeftColor"],
body #mainbody #id-right div[style*="border-left-color"],
body #mainbody #id-right div[style*="border-left:"],
body #mainbody #id-right div[style*="border-right-color"],
body #mainbody #id-right div[style*="border-right:"] {
    border-color: var(--ndx-slate-200) !important;
    border-left-color: var(--ndx-slate-200) !important;
    border-right-color: var(--ndx-slate-200) !important;
}

/* ── 3. Resgatar ícones (Indigo) ── */
body #mainbody #id-right div[style*="background"] > i,
body #mainbody #id-right div[style*="background"] > span.icon,
body #mainbody #id-right div[style*="background"] > [class*="wb-"],
body #mainbody #id-right div[style*="background"] > [class*="md-"],
body #mainbody #id-right div[style*="background"] > [class*="fa-"],
body #mainbody #id-right div[class*="bg-"] > i,
body #mainbody #id-right div[class*="bg-"] > [class*="wb-"],
body #mainbody #id-right div[class*="bg-"] > [class*="md-"],
body #mainbody #id-right div[class*="bg-"] > [class*="fa-"],
body #mainbody #id-right span[style*="background"] > i {
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    font-size: 2rem !important;
    opacity: 1 !important;
    text-shadow: none !important;
    background: transparent !important;
}

/* ── 4. Círculos dos ícones nos 4 cartões de topo ── */
body #mainbody #id-right .align-items-center > div[style*="background"],
body #mainbody #id-right .d-flex > div[style*="background"],
body #mainbody #id-right .card > div[style*="background"],
body #mainbody #id-right .row > div > div[style*="background"] {
    background-color: rgba(99, 102, 241, 0.1) !important;
    background: rgba(99, 102, 241, 0.1) !important;
    border-radius: 50% !important;
}
body #mainbody #id-right .align-items-center > div[style*="background"] i,
body #mainbody #id-right .d-flex > div[style*="background"] i,
body #mainbody #id-right .card > div[style*="background"] i,
body #mainbody #id-right .row > div > div[style*="background"] i {
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    font-size: 1.5rem !important;
    background: transparent !important;
}

/* ── 5. Texto branco forçado escuro ── */
body #mainbody #id-right [style*="color: white"],
body #mainbody #id-right [style*="color:#fff"],
body #mainbody #id-right [style*="color: #fff"],
body #mainbody #id-right [style*="color:white"],
body #mainbody #id-right [style*="color: rgb(255, 255, 255)"] {
    color: var(--ndx-slate-800) !important;
    -webkit-text-fill-color: var(--ndx-slate-800) !important;
}

/* ── 6. Links dentro dos blocos limpos ── */
body #mainbody #id-right div[style*="background"] a,
body #mainbody #id-right div[class*="bg-"] a {
    color: var(--ndx-primary-500) !important;
}
body #mainbody #id-right div[style*="background"] a:hover,
body #mainbody #id-right div[class*="bg-"] a:hover {
    color: var(--ndx-primary-600) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4.3: UNIFORM WIDGET STYLE
   Target exact PHP HTML: .boxdiv, .wave, .icon-circle, .onebox
   Make ALL boxes look like the Members badge format
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Kill Wave Swooshes (generated by PHP .wave.bg-{color}) ── */
.boxdiv,
.boxdiv .wave,
.boxdiv .wave.-one,
.boxdiv .wave.-two,
.boxdiv .wave.-three {
    display: none !important;
    background: none !important;
    background-color: transparent !important;
}

/* ── 2. Top 4 Cards: White clean card ── */
.thefourboxes .card,
.thefourboxes .card.card-shadow,
.thefourboxes .onebox .card {
    background: #FFFFFF !important;
    background-color: #FFFFFF !important;
    border: 1px solid var(--ndx-slate-100) !important;
    border-radius: var(--ndx-radius-md) !important;
    box-shadow: var(--ndx-shadow-sm) !important;
    overflow: hidden !important;
}

/* ── 3. Icon Circle: Indigo translucent (like Members badge) ── */
.thefourboxes .icon.icon-circle,
.thefourboxes i.icon.icon-circle,
#rowboxrevolutionpro .icon.icon-circle {
    background-color: rgba(99, 102, 241, 0.1) !important;
    background: rgba(99, 102, 241, 0.1) !important;
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    border: none !important;
}

/* ── 4. Counter Numbers & Labels: Dark text ── */
.thefourboxes .counter-number,
.thefourboxes .counter-number-related,
.thefourboxes .counter-label,
#rowboxrevolutionpro .counter-number,
#rowboxrevolutionpro .counter-number-related,
#rowboxrevolutionpro .counter-label {
    color: var(--ndx-slate-800) !important;
    -webkit-text-fill-color: var(--ndx-slate-800) !important;
}

/* ── 5. Force the .white wrapper text to dark ── */
.thefourboxes .white,
#rowboxrevolutionpro .white,
.thefourboxes .card .white {
    color: var(--ndx-slate-800) !important;
}

/* ── 6. Dolibarr Dashboard Boxes (AJAX-loaded) ── */
/* These are the AGENDA, INVOICES, PROJECTS, etc. boxes */
body #mainbody .box-flex-container,
body #mainbody .box-flex-container > div,
body #mainbody .box-flex-item,
body #mainbody .box-flex-item-with-icon {
    background: #FFFFFF !important;
    background-color: #FFFFFF !important;
    border: 1px solid var(--ndx-slate-100) !important;
    border-radius: var(--ndx-radius-md) !important;
    box-shadow: var(--ndx-shadow-xs) !important;
}

/* Box Left Color Strip → Indigo */
body #mainbody .box-flex-item[style*="border-left"],
body #mainbody .box-flex-item-with-icon[style*="border-left"],
body #mainbody div[style*="border-left-color"],
body #mainbody div[style*="border-left: 3px"] {
    border-left: 3px solid var(--ndx-primary-500) !important;
    border-left-color: var(--ndx-primary-500) !important;
}

/* Box icons — keep original FA icons, force Indigo */
body #mainbody .box-flex-item .fa,
body #mainbody .box-flex-item i,
body #mainbody .box-flex-item-with-icon .fa,
body #mainbody .box-flex-item-with-icon i,
body #mainbody .box-flex-item [class*="icon"],
body #mainbody .box-flex-item-with-icon [class*="icon"] {
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    background: rgba(99, 102, 241, 0.08) !important;
    border-radius: 50% !important;
    font-size: 1.8rem !important;
}

/* Box text / values — dark */
body #mainbody .box-flex-item a,
body #mainbody .box-flex-item-with-icon a {
    color: var(--ndx-primary-500) !important;
}
body #mainbody .box-flex-item span,
body #mainbody .box-flex-item-with-icon span,
body #mainbody .box-flex-item .info-box-content,
body #mainbody .box-flex-item-with-icon .info-box-content {
    color: var(--ndx-slate-700) !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4.4: DOLIBARR INFO-BOX OVERRIDE
   Target: span.info-box-icon.bg-infobox-{key} (from core index.php)
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Kill ALL bg-infobox-* coloured icon squares ── */
.info-box-icon,
span.info-box-icon,
.info-box-icon[class*="bg-infobox-"],
.info-box-icon.bg-infobox-action,
.info-box-icon.bg-infobox-project,
.info-box-icon.bg-infobox-propal,
.info-box-icon.bg-infobox-commande,
.info-box-icon.bg-infobox-facture,
.info-box-icon.bg-infobox-supplier_proposal,
.info-box-icon.bg-infobox-order_supplier,
.info-box-icon.bg-infobox-invoice_supplier,
.info-box-icon.bg-infobox-contrat,
.info-box-icon.bg-infobox-ticket,
.info-box-icon.bg-infobox-bank_account,
.info-box-icon.bg-infobox-member,
.info-box-icon.bg-infobox-expensereport,
.info-box-icon.bg-infobox-holiday,
.info-box-icon.bg-infobox-cubes {
    background-color: rgba(99, 102, 241, 0.1) !important;
    background: rgba(99, 102, 241, 0.1) !important;
    background-image: none !important;
    border-radius: var(--ndx-radius-md) !important;
}

/* ── 2. Icons inside info-box-icon → Indigo ── */
.info-box-icon i,
.info-box-icon .fa,
.info-box-icon [class*="fa-"],
span.info-box-icon i,
span.info-box-icon .fa {
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    font-size: 1.8rem !important;
}

/* ── 3. Info-box container: white card ── */
.info-box,
.info-box.info-box-sm {
    background: #FFFFFF !important;
    background-color: #FFFFFF !important;
    border: 1px solid #F1F5F9 !important;
    border-radius: 12px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
    overflow: visible !important;
    position: relative !important;
}

/* ── 4. Info-box text: enforce dark readable colors ── */
/* Title: override core opacity: 0.6 with max specificity */
.info-box-title,
.info-box-content .info-box-title,
.info-box-module .info-box-content .info-box-title,
body .info-box .info-box-content .info-box-title,
body .box-flex-item .info-box .info-box-content .info-box-title,
body div.info-box-content span.info-box-title {
    color: #1E293B !important;
    font-weight: 600 !important;
    font-family: 'Inter', -apple-system, sans-serif !important;
    opacity: 1 !important;
    -webkit-opacity: 1 !important;
    filter: none !important;
}
/* Description text */
.info-box-text,
.info-box-content .info-box-text,
.info-box-content .info-box-line,
.info-box-module .info-box-content .info-box-text,
body .info-box .info-box-content .info-box-text,
body div.info-box-content span.info-box-text {
    color: #334155 !important;
    opacity: 1 !important;
    font-weight: 500 !important;
}
.info-box-content a,
.info-box-content a.info-box-text-a {
    color: #4F46E5 !important;
}
.info-box-content a:hover {
    color: #4338CA !important;
}

/* ── Module page cards fix ── */
.info-box-content,
.info-box-module .info-box-content,
body .box-flex-item .info-box-content {
    opacity: 1 !important;
}
/* Icon container */
.info-box-icon,
.info-box-module .info-box-icon,
body .box-flex-item .info-box-icon,
span.info-box-icon {
    opacity: 1 !important;
    background-color: #EEF2FF !important;
}
/* Icon font — ensure FontAwesome takes priority */
.info-box-icon i,
.info-box-icon .fa,
.info-box-icon [class*="fa-"],
span.info-box-icon i,
span.info-box-icon .fa,
body .info-box .info-box-icon i {
    color: #4F46E5 !important;
    -webkit-text-fill-color: #4F46E5 !important;
    opacity: 1 !important;
    font-family: "Font Awesome 5 Free", "Font Awesome 5 Pro", "FontAwesome", "Material Design Iconic Font" !important;
    font-size: 1.8rem !important;
}

/* ── Module action buttons: toggle, gear, info ── */
.info-box-actions,
.info-box .info-box-actions {
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: absolute !important;
    right: 8px !important;
    bottom: 6px !important;
    z-index: 10 !important;
    gap: 4px !important;
}
.info-box-actions a,
.info-box-actions span,
.info-box .info-box-actions a {
    display: inline-flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    color: #64748B !important;
    font-size: 1.1em !important;
}
.info-box-actions a:hover {
    color: #4F46E5 !important;
}
/* Toggle icons */
.info-box-actions .fa-toggle-on,
.info-box-actions .fa-toggle-off,
.info-box-actions .fas,
.info-box-actions .far,
.info-box-actions .fa {
    font-family: "Font Awesome 5 Free" !important;
    -webkit-text-fill-color: unset !important;
    opacity: 1 !important;
    visibility: visible !important;
}
.info-box-actions .fa-toggle-on {
    color: #16A34A !important;
}
.info-box-actions .fa-toggle-off {
    color: #94A3B8 !important;
}
.info-box-actions .fa-cog,
.info-box-actions .fa-gear {
    color: #64748B !important;
}
.info-box-actions .fa-info-circle {
    color: #4F46E5 !important;
}

/* ── 5. Box flex items: clean wrapper ── */
.box-flex-item,
.box-flex-item-with-margin {
    background: transparent !important;
    border: none !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4.5: FA-DOL ICON DEFINITIONS
   Dolibarr uses fa-dol-{key} classes with NO CSS → empty icons
   Map each to a standard FontAwesome glyph
   ══════════════════════════════════════════════════════════════════ */

/* Base: ensure FA font */
.info-box-icon i[class*="fa-dol-"],
i.fa[class*="fa-dol-"] {
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900 !important;
    font-style: normal !important;
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
    display: inline-block !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    line-height: 1 !important;
}

/* Individual icon mappings */
i.fa.fa-dol-action::before,
.info-box-icon i.fa-dol-action::before       { content: "\f073" !important; } /* calendar */
i.fa.fa-dol-project::before,
.info-box-icon i.fa-dol-project::before       { content: "\f0e8" !important; } /* sitemap */
i.fa.fa-dol-propal::before,
.info-box-icon i.fa-dol-propal::before        { content: "\f15c" !important; } /* file-alt */
i.fa.fa-dol-commande::before,
.info-box-icon i.fa-dol-commande::before      { content: "\f07a" !important; } /* shopping-cart */
i.fa.fa-dol-facture::before,
.info-box-icon i.fa-dol-facture::before       { content: "\f570" !important; } /* file-invoice */
i.fa.fa-dol-supplier_proposal::before,
.info-box-icon i.fa-dol-supplier_proposal::before { content: "\f022" !important; } /* list-alt */
i.fa.fa-dol-order_supplier::before,
.info-box-icon i.fa-dol-order_supplier::before { content: "\f466" !important; } /* dolly */
i.fa.fa-dol-invoice_supplier::before,
.info-box-icon i.fa-dol-invoice_supplier::before { content: "\f571" !important; } /* file-invoice-dollar */
i.fa.fa-dol-contrat::before,
.info-box-icon i.fa-dol-contrat::before       { content: "\f328" !important; } /* file-signature */
i.fa.fa-dol-ticket::before,
.info-box-icon i.fa-dol-ticket::before        { content: "\f3ff" !important; } /* ticket */
i.fa.fa-dol-bank_account::before,
.info-box-icon i.fa-dol-bank_account::before  { content: "\f19c" !important; } /* university/bank */
i.fa.fa-dol-member::before,
.info-box-icon i.fa-dol-member::before        { content: "\f0c0" !important; } /* users */
i.fa.fa-dol-expensereport::before,
.info-box-icon i.fa-dol-expensereport::before { content: "\f4c0" !important; } /* money-check-alt */
i.fa.fa-dol-holiday::before,
.info-box-icon i.fa-dol-holiday::before       { content: "\f5ca" !important; } /* umbrella-beach */
i.fa.fa-dol-cubes::before,
.info-box-icon i.fa-dol-cubes::before         { content: "\f1b3" !important; } /* cubes */

/* ── Override right-side coloured borders → Indigo uniform ── */
html body [class*="bg-infobox-"] ~ .info-box-content,
html body [class*="bg-infoxbox-"] ~ .info-box-content {
    border-right: 2px solid var(--ndx-primary-500) !important;
    border-right-color: var(--ndx-primary-500) !important;
}

/* ── Sidebar FA icons: white (override grey #838383 from custom.css) ── */
html body .site-menu-icon.mainmenu::before,
html body .site-menubar .site-menu-icon.mainmenu::before,
html body .site-menubar .mainmenu::before,
html body .site-menu-icon::before {
    color: #FFFFFF !important;
    -webkit-text-fill-color: #FFFFFF !important;
}

/* ── Company name next to logo: white ── */
html body .navbar-brand-text,
html body .site-navbar .navbar-brand-text {
    color: #FFFFFF !important;
    -webkit-text-fill-color: #FFFFFF !important;
}

/* ── Submenu header: kill grey background → subtle Indigo highlight ── */
html body li.site-menu-item.metrovmenu a.rpfirstmenuglobal,
html body .site-menubar-dark li.site-menu-item.metrovmenu a.rpfirstmenuglobal {
    background-color: rgba(255, 255, 255, 0.12) !important;
    color: #FFFFFF !important;
}

/* ── Fix invisible view toggle icons (List, Kanban, +) ── */
.btnTitle-icon,
.btnTitle-icon .fas,
.btnTitle-icon .fa,
.btnTitle-icon .far,
.btnTitle-icon [class^="fa-"] {
    font-family: "Font Awesome 5 Free", FontAwesome, sans-serif !important;
    font-weight: 900 !important;
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
}
/* Fix forced 50px dimensions — let icons size naturally */
span.btnTitle-icon {
    width: auto !important;
    height: auto !important;
    display: inline-block !important;
    font-size: 14px !important;
}
a.btnTitle:hover .btnTitle-icon,
a.btnTitle:hover .btnTitle-icon .fas,
a.btnTitle:hover .btnTitle-icon .fa {
    color: var(--ndx-primary-600) !important;
    -webkit-text-fill-color: var(--ndx-primary-600) !important;
}
a.btnTitle.btnTitleSelected {
    border: 1px solid var(--ndx-primary-500) !important;
    background-color: rgba(99, 102, 241, 0.1) !important;
    border-radius: 4px !important;
}

/* ── Remove dark line below topbar ── */
.site-navbar {
    border-bottom: none !important;
    box-shadow: none !important;
}
#id-top.side-nav-vert {
    border: none !important;
    background: transparent !important;
}
.page {
    border-top: none !important;
}

/* (hamburger-bar Indigo — handled in main theme block above) */

/* ── Sidebar arrow: fix □ glyph → FA chevron ── */
.site-menu-arrow {
    font-size: 0 !important; /* hide the broken glyph */
    width: 16px !important;
    height: 16px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.site-menu-arrow::before {
    content: "\f054" !important; /* fa-chevron-right */
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900 !important;
    font-size: 10px !important;
    color: rgba(255, 255, 255, 0.5) !important;
    -webkit-text-fill-color: rgba(255, 255, 255, 0.5) !important;
    display: inline-block !important;
}

/* ── Back arrow << to return to main menu ── */
html body .site-menubar .mm-navbar .mm-prev::before {
    content: "\f100" !important; /* fa-angle-double-left (<<) */
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900 !important;
    font-size: 20px !important;
    color: #FFFFFF !important;
    -webkit-text-fill-color: #FFFFFF !important;
    display: inline-block !important;
    animation: none !important;
}
html body .site-menubar .mm-navbar .mm-prev {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* ── Fixed/unfold sidebar: compact MAIN menu spacing ── */
body.site-menubar-unfold .site-menu > .site-menu-item > a {
    padding-top: 10px !important;
    padding-bottom: 10px !important;
    line-height: 1.4 !important;
}
body.site-menubar-unfold .site-menu > .site-menu-item {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
}
body.site-menubar-unfold li.site-menu-item.metrovmenu a {
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

/* ── Submenu panel: alignment matching original demo ── */
/* All submenu items — base reset */
.mm-panel li.site-menu-item a {
    padding-left: 15px !important;
}
/* Section headers (bold groups: "Commercial proposals", "Sales Orders") */
.mm-panel li.site-menu-item.vmenu > a,
.mm-panel li.site-menu-item.metrovmenu > a {
    padding-left: 15px !important;
    padding-top: 8px !important;
    padding-bottom: 4px !important;
    font-weight: 600 !important;
    font-size: 13px !important;
}
/* Sub-items ("New proposal", "List", "Statistics") */
.mm-panel li.site-menu-item.metrovsmenu > a,
.mm-panel .site-menu-sub li.site-menu-item > a {
    padding-left: 30px !important;
    padding-top: 4px !important;
    padding-bottom: 4px !important;
    font-weight: 400 !important;
    font-size: 13px !important;
    line-height: 1.4 !important;
}
/* Reset span margin-left to prevent double-indent */
.mm-panel li.site-menu-item a .site-menu-title {
    margin-left: 0 !important;
}
/* Nav bar in submenu panel */
.mm-panels .mm-panel.mm-hasnavbar .mm-navbar {
    margin-bottom: 4px !important;
}

/* ══════════════════════════════════════════════════════════════════
   NovaDX Pro — Phase 4.6: RIGHT BORDER, GLOBAL VIEW, BADGE FIX
   ══════════════════════════════════════════════════════════════════ */

/* ── 1. Global View (Weather) — FA icon via ::before ── */
.info-box-weather .info-box-icon,
.info-box-weather span.info-box-icon {
    background-color: rgba(99, 102, 241, 0.1) !important;
    background: rgba(99, 102, 241, 0.1) !important;
    position: relative !important;
}
/* Hide the weather img that doesn't render properly */
.info-box-weather .info-box-icon img {
    display: none !important;
}
/* Inject a FA icon via pseudo-element */
.info-box-weather .info-box-icon::before {
    content: "\f200" !important; /* fa-chart-pie */
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900 !important;
    font-size: 1.8rem !important;
    color: var(--ndx-primary-500) !important;
    -webkit-text-fill-color: var(--ndx-primary-500) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    height: 100% !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
}

/* ── 2. Left side shadow for contour depth ── */
.info-box,
.info-box.info-box-sm {
    border-left: 1px solid var(--ndx-slate-100) !important;
    box-shadow: -3px 0 8px -2px rgba(99, 102, 241, 0.2),
                0 1px 3px 0 rgba(0, 0, 0, 0.04) !important;
}

/* ── 3. Uniform Indigo RIGHT border on ALL badges (html body for max specificity over custom.css) ── */
html body [class*="bg-infobox-"] ~ .info-box-content,
html body .bg-infobox-action ~ .info-box-content,
html body .bg-infobox-project ~ .info-box-content,
html body .bg-infobox-propal ~ .info-box-content,
html body .bg-infobox-commande ~ .info-box-content,
html body .bg-infobox-facture ~ .info-box-content,
html body .bg-infobox-contrat ~ .info-box-content,
html body .bg-infobox-ticket ~ .info-box-content,
html body .bg-infobox-bank_account ~ .info-box-content,
html body .bg-infobox-member ~ .info-box-content,
html body .bg-infobox-adherent ~ .info-box-content,
html body .bg-infobox-expensereport ~ .info-box-content,
html body .bg-infobox-holiday ~ .info-box-content,
html body .bg-infobox-invoice_supplier ~ .info-box-content,
html body .bg-infobox-order_supplier ~ .info-box-content,
html body .bg-infobox-supplier_proposal ~ .info-box-content,
html body .bg-infoxbox-action ~ .info-box-content,
html body .bg-infoxbox-project ~ .info-box-content,
html body .bg-infoxbox-propal ~ .info-box-content,
html body .bg-infoxbox-commande ~ .info-box-content,
html body .bg-infoxbox-facture ~ .info-box-content,
html body .bg-infoxbox-contrat ~ .info-box-content,
html body .bg-infoxbox-ticket ~ .info-box-content,
html body .bg-infoxbox-bank_account ~ .info-box-content,
html body .bg-infoxbox-member ~ .info-box-content,
html body .bg-infoxbox-adherent ~ .info-box-content,
html body .bg-infoxbox-expensereport ~ .info-box-content,
html body .bg-infoxbox-holiday ~ .info-box-content,
html body .bg-infoxbox-invoice_supplier ~ .info-box-content,
html body .bg-infoxbox-order_supplier ~ .info-box-content,
html body .bg-infoxbox-supplier_proposal ~ .info-box-content {
    border-right: 2px solid var(--ndx-primary-500) !important;
    border-right-color: var(--ndx-primary-500) !important;
}

/* ── 4. Badge-info numeric indicator → Indigo ── */
.badge-info,
.badge.badge-info,
span.badge.badge-info,
.info-box .badge-info,
.info-box-content .badge-info {
    background-color: var(--ndx-primary-500) !important;
    border-color: var(--ndx-primary-500) !important;
    color: #FFFFFF !important;
}
.badge-warning,
.badge.badge-warning {
    background-color: #F59E0B !important;
    border-color: #F59E0B !important;
    color: #FFFFFF !important;
}

/* ── Modern Dashboard Charts ── */
/* Chart container cards */
.dolgraphchart {
    background: #ffffff !important;
    border-radius: 16px !important;
    box-shadow: 0 2px 12px rgba(79, 70, 229, 0.08) !important;
    padding: 20px !important;
    margin: 8px 4px !important;
    border: 1px solid rgba(79, 70, 229, 0.06) !important;
    transition: box-shadow 0.3s ease, transform 0.2s ease !important;
}
.dolgraphchart:hover {
    box-shadow: 0 4px 20px rgba(79, 70, 229, 0.14) !important;
    transform: translateY(-1px);
}
/* Chart title styling */
.dolgraphtitle {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #4338CA !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 12px !important;
    padding-bottom: 8px !important;
    border-bottom: 2px solid rgba(79, 70, 229, 0.1) !important;
}
/* Canvas responsive */
.dolgraphchart canvas {
    border-radius: 8px !important;
}
/* No-data placeholder */
.nographyet {
    background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%) !important;
    border-radius: 12px !important;
    border: 2px dashed rgba(79, 70, 229, 0.2) !important;
}
.nographyettext {
    font-family: 'Inter', -apple-system, sans-serif !important;
    color: #6366F1 !important;
    font-weight: 500 !important;
    font-size: 13px !important;
}
/* Graph tooltip */
.graph-tooltip-inner {
    font-family: 'Inter', -apple-system, sans-serif !important;
    font-size: 12px !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    background: #1E1B4B !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}
/* Chart legend refinement */
.dolgraph .chart-legend,
.dolgraphchart .chart-legend {
    font-family: 'Inter', -apple-system, sans-serif !important;
    font-size: 11px !important;
}

/* ═════════════════════════════════════════════════════════════════
   Flavor Pro — Login Page (Clean Split-Panel)
   ═════════════════════════════════════════════════════════════════ */

/* ── Body: remove ALL background images, use clean white ── */
body.bodylogin {
    background: #FFFFFF !important;
    background-image: none !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

/* ── Remove the dark overlay pseudo-element ── */
body.bodylogin.page-login-v2.page-dark.layout-full::after {
    display: none !important;
    background: none !important;
    content: none !important;
}
/* ── Remove the background photo pseudo-element ── */
body.bodylogin.page-login-v2::before,
body.bodylogin.page-login-v2.page-dark.layout-full::before {
    background-image: none !important;
    background: none !important;
    display: none !important;
    content: none !important;
}
/* ── Remove diagonal shapes from right panel ── */
body.bodylogin.page-login-v2 .page-login-main {
    background-image: none !important;
}

/* ── Left panel: clean purple gradient (NO background image) ── */
body.bodylogin .page-brand-info {
    background: linear-gradient(160deg, #7C3AED 0%, #8B5CF6 30%, #A78BFA 70%, #C4B5FD 100%) !important;
    position: relative;
    overflow: hidden;
}
/* Decorative circles on the gradient panel */
body.bodylogin .page-brand-info::before {
    content: '' !important;
    position: absolute !important;
    width: 300px !important;
    height: 300px !important;
    border-radius: 50% !important;
    background: rgba(255,255,255,0.08) !important;
    bottom: -80px !important;
    right: -40px !important;
    z-index: 0 !important;
}
body.bodylogin .page-brand-info::after {
    content: '' !important;
    position: absolute !important;
    width: 150px !important;
    height: 150px !important;
    border-radius: 50% !important;
    background: rgba(255,255,255,0.06) !important;
    top: -30px !important;
    left: -30px !important;
    z-index: 0 !important;
}
/* Brand text & logo on the gradient panel */
body.bodylogin .page-brand-info * {
    color: #FFFFFF !important;
    position: relative;
    z-index: 1;
}
body.bodylogin .page-brand-info .brand-text {
    font-family: 'Inter', sans-serif !important;
    font-weight: 700 !important;
    color: #FFFFFF !important;
    font-size: 28px !important;
}
body.bodylogin .page-brand-info p {
    opacity: 0.85 !important;
    color: rgba(255,255,255,0.9) !important;
    font-family: 'Inter', sans-serif !important;
}

/* ── Right panel: clean white ── */
body.bodylogin .page-login-main {
    background: #FFFFFF !important;
    padding: 60px 50px 40px !important;
}

/* ── "Welcome back" heading ── */
body.bodylogin .flavor-welcome {
    font-family: 'Inter', sans-serif !important;
    font-size: 26px !important;
    font-weight: 700 !important;
    color: #1E293B !important;
    margin-bottom: 28px !important;
    margin-top: 20px !important;
}

/* ── Version text (top right discreet) ── */
body.bodylogin .login_table_title {
    font-size: 11px !important;
    color: #94A3B8 !important;
    text-align: right !important;
    margin-bottom: 8px !important;
    font-weight: 400 !important;
    text-decoration: none !important;
}
body.bodylogin a.login_table_title {
    color: #94A3B8 !important;
    text-decoration: none !important;
}

/* ── Login card (inside right panel — no card shadow, flat) ── */
body.bodylogin .login_table {
    background: transparent !important;
    box-shadow: none !important;
    border: none !important;
    padding: 0 !important;
    width: 100% !important;
    max-width: 380px !important;
}

/* ── Hide logo inside the form (it's on the left panel) ── */
body.bodylogin #login_left {
    display: none !important;
}
body.bodylogin #login_line1 > br {
    display: none !important;
}

/* ── Input rows ── */
body.bodylogin .trinputlogin {
    display: block !important;
    margin-bottom: 18px !important;
}
body.bodylogin .tdinputlogin {
    display: flex !important;
    align-items: center !important;
    background: #FFFFFF !important;
    border: 1.5px solid #CBD5E1 !important;
    border-radius: 8px !important;
    padding: 0 14px !important;
    transition: all 0.2s ease !important;
    width: 100% !important;
    box-sizing: border-box !important;
}
body.bodylogin .tdinputlogin:focus-within {
    border-color: #7C3AED !important;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1) !important;
}

/* ── Input icons ── */
body.bodylogin .tdinputlogin > .fa,
body.bodylogin .tdinputlogin > span.fa {
    color: #94A3B8 !important;
    font-size: 14px !important;
    margin-right: 10px !important;
    flex-shrink: 0 !important;
    width: 16px !important;
    text-align: center !important;
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
/* Override the custom.min.css rule that hides FA icons on login */
body.bodylogin .login_table .tdinputlogin .fa.fa-user,
body.bodylogin .login_table .tdinputlogin .fa.fa-key,
body.bodylogin .login_table .tdinputlogin .fa.fa-unlock,
body.bodylogin .login_table .tdinputlogin .fal.fa-user,
body.bodylogin .login_table .tdinputlogin .fal.fa-key {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    color: #94A3B8 !important;
}
body.bodylogin .login_table .fa.fa-user:before,
body.bodylogin .login_table .fa.fa-key:before,
body.bodylogin .login_table .fa.fa-unlock:before {
    display: inline !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* ── Input fields ── */
body.bodylogin .tdinputlogin input[type="text"],
body.bodylogin .tdinputlogin input[type="password"] {
    flex: 1 !important;
    border: none !important;
    background: transparent !important;
    padding: 13px 0 !important;
    font-size: 14px !important;
    font-family: 'Inter', sans-serif !important;
    color: #1E293B !important;
    outline: none !important;
    box-shadow: none !important;
    width: 100% !important;
    margin: 0 !important;
}
body.bodylogin .tdinputlogin input::placeholder {
    color: #94A3B8 !important;
    font-weight: 400 !important;
}

/* ── Submit button ── */
body.bodylogin #login_line2 {
    clear: both !important;
}
body.bodylogin #login-submit-wrapper {
    margin-top: 10px !important;
}
body.bodylogin #login-submit-wrapper input[type="submit"],
body.bodylogin .login_table input.button,
body.bodylogin .login_table input[type="submit"] {
    width: 100% !important;
    padding: 13px 24px !important;
    background: linear-gradient(135deg, #7C3AED, #8B5CF6) !important;
    color: #FFFFFF !important;
    border: none !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    font-family: 'Inter', sans-serif !important;
    letter-spacing: 1px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.35) !important;
    text-transform: uppercase !important;
    line-height: 1.5 !important;
}
body.bodylogin #login-submit-wrapper input[type="submit"]:hover,
body.bodylogin .login_table input.button:hover,
body.bodylogin .login_table input[type="submit"]:hover {
    background: linear-gradient(135deg, #6D28D9, #7C3AED) !important;
    box-shadow: 0 6px 18px rgba(124, 58, 237, 0.45) !important;
    transform: translateY(-1px) !important;
}

/* ── Forgot password link ── */
body.bodylogin a.alogin {
    color: #7C3AED !important;
    text-decoration: none !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    font-family: 'Inter', sans-serif !important;
}
body.bodylogin a.alogin:hover {
    color: #6D28D9 !important;
    text-decoration: underline !important;
}

/* ── Error / warning messages ── */
body.bodylogin .jnotify-container-login,
body.bodylogin .login_main_message {
    max-width: 380px !important;
}

/* ── Responsive ── */
@media (max-width: 991px) {
    body.bodylogin .page-login-main {
        padding: 40px 30px 30px !important;
    }
    body.bodylogin .flavor-welcome {
        font-size: 22px !important;
    }
}


<?php

$valcss = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUECSS) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUECSS : '';
if($valcss){
    print ($valcss);
}
// ── Fix: .hideobject must override .inline-block from bootstrap-extend.min.css ──
// bootstrap-extend defines .inline-block { display: inline-block !important }
// which breaks Dolibarr's AJAX toggle ON/OFF visibility (both shown at once)
print "\n/* Fix: .hideobject must override .inline-block (bootstrap-extend conflict) */\n";
print ".hideobject { display: none !important; }\n";
print ".inline-block.hideobject { display: none !important; }\n";

// ── Hide the ? help button (links to wiki.dolibarr.org) ──
// Must beat custom.min.css: body #id-top .navbar-toolbar .dropdown-menu.revolutionothersrightmenus .dropdown-item { display: inline-block !important }
print "\n/* White-Label: Hide help button linking to Dolibarr wiki */\n";
print "body #id-top .dropdown-item.righttopmenu4, .righttopmenu4 { display: none !important; }\n";

// ── Flavor Pro: Include generated visibility CSS ──
$hiddenCssPath = dirname(__FILE__).'/../admin/flavorpro_hidden.css';
if (file_exists($hiddenCssPath)) {
    print "\n/* Flavor Pro Menu Visibility (auto-generated) */\n";
    print file_get_contents($hiddenCssPath);
}

// ── Flavor Pro: Pass icon config from llx_revolutionpro_config to JS via CSS variable ──
if (is_object($db)) {
    // FA class name → unicode lookup (Font Awesome 4/5 compatible)
    $faUnicode = array(
        'fa-home'=>'\f015','fa-star'=>'\f005','fa-building'=>'\f1ad','fa-cube'=>'\f1b2',
        'fa-briefcase'=>'\f0b1','fa-university'=>'\f19c','fa-calculator'=>'\f1ec',
        'fa-project-diagram'=>'\f542','fa-user-tie'=>'\f508','fa-ticket-alt'=>'\f3ff',
        'fa-wrench'=>'\f0ad','fa-users'=>'\f0c0','fa-calendar-check'=>'\f274',
        'fa-money-bill-wave'=>'\f53a','fa-folder-open'=>'\f07c','fa-industry'=>'\f275',
        'fa-cash-register'=>'\f788','fa-cogs'=>'\f085','fa-cog'=>'\f013',
        'fa-layer-group'=>'\f5fd','fa-file-invoice'=>'\f570','fa-globe'=>'\f0ac',
        'fa-user-plus'=>'\f234','fa-umbrella-beach'=>'\f5ca','fa-money-check-alt'=>'\f53d',
        'fa-hand-holding-heart'=>'\f4be','fa-piggy-bank'=>'\f4d3','fa-file-signature'=>'\f573',
        'fa-shipping-fast'=>'\f48b','fa-warehouse'=>'\f494','fa-rocket'=>'\f135',
        'fa-heart'=>'\f004','fa-flag'=>'\f024','fa-bolt'=>'\f0e7','fa-bell'=>'\f0f3',
        'fa-shield-alt'=>'\f3ed','fa-lock'=>'\f023','fa-key'=>'\f084','fa-magic'=>'\f0d0',
        'fa-paint-brush'=>'\f1fc','fa-palette'=>'\f53f','fa-diamond'=>'\f219',
        'fa-trophy'=>'\f091','fa-chart-line'=>'\f201','fa-chart-bar'=>'\f080',
        'fa-chart-pie'=>'\f200','fa-database'=>'\f1c0','fa-server'=>'\f233',
        'fa-cloud'=>'\f0c2','fa-code'=>'\f121','fa-terminal'=>'\f120',
        'fa-desktop'=>'\f108','fa-laptop'=>'\f109','fa-mobile-alt'=>'\f3cd',
        'fa-envelope'=>'\f0e0','fa-phone'=>'\f095','fa-map-marker-alt'=>'\f3c5',
        'fa-search'=>'\f002','fa-edit'=>'\f044','fa-trash'=>'\f1f8','fa-plus'=>'\f067',
        'fa-minus'=>'\f068','fa-check'=>'\f00c','fa-times'=>'\f00d','fa-eye'=>'\f06e',
        'fa-download'=>'\f019','fa-upload'=>'\f093','fa-print'=>'\f02f',
        'fa-file'=>'\f15b','fa-file-alt'=>'\f15c','fa-copy'=>'\f0c5',
        'fa-bookmark'=>'\f02e','fa-tag'=>'\f02b','fa-tags'=>'\f02c',
        'fa-comment'=>'\f075','fa-comments'=>'\f086','fa-share'=>'\f064',
        'fa-link'=>'\f0c1','fa-paperclip'=>'\f0c6','fa-calendar'=>'\f073',
        'fa-clock'=>'\f017','fa-history'=>'\f1da','fa-undo'=>'\f0e2',
        'fa-handshake'=>'\f2b5','fa-address-book'=>'\f2b9','fa-sitemap'=>'\f0e8',
        'fa-bars'=>'\f0c9','fa-th'=>'\f00a','fa-list'=>'\f03a',
        'fa-shopping-cart'=>'\f07a','fa-credit-card'=>'\f09d','fa-money-bill'=>'\f0d6',
        'fa-balance-scale'=>'\f24e','fa-gavel'=>'\f0e3','fa-landmark'=>'\f66f',
        'fa-headset'=>'\f590','fa-cubes'=>'\f1b3','fa-box'=>'\f466',
        'fa-dolly'=>'\f472','fa-truck'=>'\f0d1','fa-plane'=>'\f072',
        'fa-anchor'=>'\f13d','fa-compass'=>'\f14e','fa-globe-americas'=>'\f57d',
        'fa-solar-panel'=>'\f5ba','fa-city'=>'\f64f','fa-balance-scale-right'=>'\f516',
        'fa-address-card'=>'\f2bb',
    );

    $sql_ic = "SELECT menu_key, fa_icon FROM ".MAIN_DB_PREFIX."revolutionpro_config WHERE entity=1 AND fa_icon != ''";
    $resql_ic = $db->query($sql_ic);
    if ($resql_ic) {
        $iconMap = array();
        while ($obj_ic = $db->fetch_object($resql_ic)) {
            $iconMap[$obj_ic->menu_key] = $obj_ic->fa_icon;
        }
        if (!empty($iconMap)) {
            $jsonMap = json_encode($iconMap);
            print "\n/* Icon config carrier (read by revolutionpro.js.php) */\n";
            print ":root { --revpro-icon-map: '".$jsonMap."'; }\n";

            // Generate per-key CSS ::before content overrides
            // Theme uses: div.mainmenu.home::before { content: "\f015" }
            // We override with: div.mainmenu.home::before { content: "\f005" !important }
            print "\n/* Flavor Pro: Per-menu icon overrides */\n";
            foreach ($iconMap as $menuKey => $faClass) {
                // Extract the icon name from class (e.g. "fa fa-star" -> "fa-star")
                $parts = explode(' ', trim($faClass));
                $iconName = '';
                foreach ($parts as $part) {
                    if (strpos($part, 'fa-') === 0 && $part !== 'fa-fw') {
                        $iconName = $part;
                        break;
                    }
                }
                if ($iconName && isset($faUnicode[$iconName])) {
                    $unicode = $faUnicode[$iconName];
                    $safeKey = htmlspecialchars($menuKey);
                    print "div.mainmenu.{$safeKey}::before { content: \"{$unicode}\" !important; }\n";
                }
            }
            // Hide submenu arrows in folded sidebar (they overlap with icons)
            print "\n/* Hide submenu arrows in folded sidebar to prevent icon overlap */\n";
            print ".site-menubar-fold .site-menu > .site-menu-item > a .site-menu-arrow { display: none !important; }\n";
            print "#mainbody:not(.site-navbar-small) .site-menu > .site-menu-item > a .site-menu-arrow { display: none !important; }\n";
        }
    }
}


// ── Flavor Pro: Include visibility rules (flavorpro_hidden.css) ──
$hiddenCssFile = dirname(__DIR__).'/admin/flavorpro_hidden.css';
if (file_exists($hiddenCssFile)) {
    print "\n/* Flavor Pro visibility rules */\n";
    print file_get_contents($hiddenCssFile);
    print "\n";
}

// ── White-Label: Brand name, URL, and logo CSS ──
if (is_object($db)) {
    $wlEnabled = getDolGlobalString('REVOLUTIONPRO_WHITELABEL_ENABLED', '0');
    if ($wlEnabled) {
        $brandName   = getDolGlobalString('REVOLUTIONPRO_BRAND_NAME', '');
        $sidebarLogo = getDolGlobalString('REVOLUTIONPRO_SIDEBAR_LOGO', '');
        $loginLogo   = getDolGlobalString('REVOLUTIONPRO_LOGIN_LOGO', '');

        if ($brandName) {
            $safeBrand = str_replace("'", "\\'", $brandName);
            // Also pass company name for version strings (e.g. "Dolisys 22.0.4")
            // Note: $mysoc is not available here (NOREQUIRESOC), so read from DB config
            $companyName = getDolGlobalString('MAIN_INFO_SOCIETE_NOM', $brandName);
            $safeCompany = str_replace("'", "\\'", $companyName);
            print "\n/* White-Label: Brand + company name carriers (read by revolutionpro.js.php) */\n";
            print ":root { --revpro-brand-name: '".$safeBrand."'; --revpro-brand-url: 'https://www.novadx.pt'; --revpro-company-name: '".$safeCompany."'; }\n";
        }

        // Fix sidebar brand text truncation — show full company name
        print "\n/* White-Label: Fix brand text truncation */\n";
        print "body.site-navbar-small .site-navbar .navbar-brand { max-width: none !important; overflow: visible !important; text-overflow: unset !important; }\n";
        print ".navbar-brand-text { white-space: nowrap !important; overflow: visible !important; text-overflow: unset !important; }\n";

        // Sidebar logo: fallback ONLY — use white-label logo if no company logo is set
        // The company logo has src like viewimage.php?...mycompany...
        // If company has a logo, don't override it. Only set for the case where
        // no company logo exists (img src will be empty or default dolibarr icon)
        if ($sidebarLogo) {
            $logoUrl = dol_buildpath('/revolutionpro/img/logos/'.$sidebarLogo, 1);
            print "\n/* White-Label: Sidebar logo (fallback when no company logo) */\n";
            // Only apply to imgs WITHOUT a company logo URL
            // Company logos have src containing 'viewimage.php?...mycompany'
            // Default Dolibarr logo has src like 'dolibarr_logo.svg' or similar
            print ".navbar-brand-logo:not([src*='viewimage.php']) { content: url('".$logoUrl."') !important; max-height: 30px !important; }\n";
        }

        // Login page logo override (always applies — login page is where branding matters most)
        if ($loginLogo) {
            $loginLogoUrl = dol_buildpath('/revolutionpro/img/logos/'.$loginLogo, 1);
            print "\n/* White-Label: Login page logo override */\n";
            print ".page-login-v2 .page-brand-info img, .bodylogin .login_logo_title img, .bodylogin .login_main_home img { content: url('".$loginLogoUrl."') !important; max-height: 80px !important; }\n";
            print ".bodylogin img.login_logo_title { content: url('".$loginLogoUrl."') !important; max-height: 80px !important; }\n";
        }
    }
}
