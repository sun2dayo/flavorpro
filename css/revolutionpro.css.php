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

$val3 = $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3 ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3 : 'teal';
$val6 = $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE6 ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE6 : 'primary';

// ── NovaDX Pro: Modern colour palette (replaces old Material colours) ──
$colorsarr['primary']  = '4F46E5';
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


.bodylogin.page-login-v2 .page-login-main{
    background-image: url(<?php echo dol_buildpath('/revolutionpro/img/login/revolutionprologindesign.png', 1); ?>) !important;
}
.bodylogin.page-login-v2:before {
    background-image: url(<?php echo $urlf; ?>) !important;
}
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

<?php

$valcss = $conf->global->REVOLUTIONPRO_PARAMETRES_VALUECSS ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUECSS : '';
if($valcss){
    print ($valcss);
}