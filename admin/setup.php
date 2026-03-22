<?php
/**
 * Flavor Pro — Advanced Setup & Configuration Panel
 * Copyright (C) 2025-2026  NovaDX  <ola@novadx.pt>  https://novadx.pt
 *
 * Features:
 * - Original Revolution Pro settings (Colors, Four Boxes, Login, CSS)
 * - Icon Manager: Customize FontAwesome icons and labels for sidebar menus
 * - Menu Manager: Hide/show sidebar menu items via generated CSS
 * - Admin Tools Control: Granular visibility for Admin Tools submenu
 * - Module Tabs Manager: Hide external modules, deploy, develop tabs
 * - Security Lock: Lock the setup page with flavorpro.lock
 */

// ──────────────────────────────────────────────────────────────────────────────
// SECURITY LOCK CHECK
// ──────────────────────────────────────────────────────────────────────────────
if (file_exists(__DIR__ . '/flavorpro.lock')) {
    die('<div style="font-family: \'Inter\', -apple-system, sans-serif; background: #F8FAFC; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0;">
            <div style="background: #FFF; padding: 48px; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); text-align: center; max-width: 420px;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #FEE2E2, #FECACA); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 28px;">🔒</div>
                <h2 style="color: #1E293B; margin-top: 0; font-size: 1.25rem;">Setup Locked</h2>
                <p style="color: #64748B; line-height: 1.6; margin-bottom: 0;">Configuration is locked. Delete <code>flavorpro.lock</code> from the admin directory to unlock.</p>
            </div>
         </div>');
}

// ──────────────────────────────────────────────────────────────────────────────
// Dolibarr Bootstrap
// ──────────────────────────────────────────────────────────────────────────────
$res=@include("../../main.inc.php");
if (! $res && file_exists($_SERVER['DOCUMENT_ROOT']."/main.inc.php"))
	$res=@include($_SERVER['DOCUMENT_ROOT']."/main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");

require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
dol_include_once('/revolutionpro/class/revolutionpro.class.php');
dol_include_once('/revolutionpro/lib/revolutionpro.lib.php');
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

$langs->load("admin");
$langs->load("revolutionpro@revolutionpro");

// ──────────────────────────────────────────────────────────────────────────────
// Menu definitions
// ──────────────────────────────────────────────────────────────────────────────
$availableMenus = array(
    'home'          => array('label' => 'Home / Dashboard',   'icon' => '🏠'),
    'members'       => array('label' => 'Members',            'icon' => '👤'),
    'companies'     => array('label' => 'Third Parties',      'icon' => '🏢'),
    'products'      => array('label' => 'Products/Services',  'icon' => '📦'),
    'mrp'           => array('label' => 'MRP / Manufacturing','icon' => '🏭'),
    'project'       => array('label' => 'Projects',           'icon' => '📋'),
    'commercial'    => array('label' => 'Commercial',         'icon' => '🤝'),
    'billing'       => array('label' => 'Billing / Payment',  'icon' => '💰'),
    'bank'          => array('label' => 'Banks / Cash',       'icon' => '🏦'),
    'accountancy'   => array('label' => 'Accountancy',        'icon' => '📊'),
    'hrm'           => array('label' => 'HRM',                'icon' => '👥'),
    'ticket'        => array('label' => 'Tickets',            'icon' => '🎫'),
    'tools'         => array('label' => 'Tools',              'icon' => '🛠️'),
);

$adminToolsSubmenus = array(
    'info_dolibarr'    => array('label' => 'About Dolisys',      'icon' => 'ℹ️',  'css' => 'a[href*="system/dolibarr.php"]'),
    'info_browser'     => array('label' => 'About Browser',      'icon' => '🌐', 'css' => 'a[href*="system/browser.php"]'),
    'info_os'          => array('label' => 'About OS',           'icon' => '💻', 'css' => 'a[href*="system/os.php"]'),
    'info_web'         => array('label' => 'About Web Server',   'icon' => '🖥️',  'css' => 'a[href*="system/web.php"]'),
    'info_php'         => array('label' => 'About PHP',          'icon' => '🐘', 'css' => 'a[href*="system/phpinfo.php"]'),
    'info_database'    => array('label' => 'About Database',     'icon' => '🗄️',  'css' => 'a[href*="system/database.php"]'),
    'info_perf'        => array('label' => 'Performances',       'icon' => '⚡', 'css' => 'a[href*="system/perf.php"]'),
    'info_security'    => array('label' => 'Security',           'icon' => '🛡️',  'css' => 'a[href*="system/security.php"]'),
    'audit'            => array('label' => 'Audit / Events',     'icon' => '📜', 'css' => 'a[href*="tools/listevents.php"]'),
    'sessions'         => array('label' => 'User Sessions',      'icon' => '👥', 'css' => 'a[href*="tools/listsessions.php"]'),
    'backup'           => array('label' => 'Backup',             'icon' => '💾', 'css' => 'a[href*="dolibarr_export.php"]'),
    'restore'          => array('label' => 'Restore',            'icon' => '📥', 'css' => 'a[href*="dolibarr_import.php"]'),
    'upgrade'          => array('label' => 'Upgrade / Extend',   'icon' => '🔄', 'css' => 'a[href*="tools/update.php"]'),
    'purge'            => array('label' => 'Purge',              'icon' => '🗑️',  'css' => 'a[href*="tools/purge.php"]'),
    'vat_update'       => array('label' => 'Global VAT Update',  'icon' => '💱', 'css' => 'a[href*="product_tools.php"]'),
);

$setupSubmenus = array(
    'company'       => array('label' => 'Company/Organization',      'icon' => '🏢', 'css' => 'a[href*="admin/company.php"]'),
    'modules'       => array('label' => 'Modules/Applications',      'icon' => '📦', 'css' => 'a[href*="admin/modules.php"]'),
    'display'       => array('label' => 'Display',                   'icon' => '🖥️',  'css' => 'a[href*="admin/ihm.php"]'),
    'menus_setup'   => array('label' => 'Menus',                     'icon' => '📋', 'css' => 'a[href*="admin/menus.php"]'),
    'translation'   => array('label' => 'Translation',               'icon' => '🌐', 'css' => 'a[href*="admin/translation.php"]'),
    'defaultvalues' => array('label' => 'Default values/filters',    'icon' => '⚙️',  'css' => 'a[href*="admin/defaultvalues.php"]'),
    'widgets'       => array('label' => 'Widgets',                   'icon' => '🧩', 'css' => 'a[href*="admin/boxes.php"]'),
    'alerts'        => array('label' => 'Alerts',                    'icon' => '🔔', 'css' => 'a[href*="admin/delais.php"]'),
    'security'      => array('label' => 'Security',                  'icon' => '🛡️',  'css' => 'a[href*="security_other.php"]'),
    'limits'        => array('label' => 'Limits and accuracy',       'icon' => '📐', 'css' => 'a[href*="admin/limits.php"]'),
    'pdf'           => array('label' => 'PDF',                       'icon' => '📄', 'css' => 'a[href*="admin/pdf.php"]'),
    'emails'        => array('label' => 'Emails',                    'icon' => '📧', 'css' => 'a[href*="admin/mails.php"]'),
    'sms'           => array('label' => 'SMS',                       'icon' => '💬', 'css' => 'a[href*="admin/sms.php"]'),
    'dictionaries'  => array('label' => 'Dictionaries',              'icon' => '📚', 'css' => 'a[href*="admin/dict.php"]'),
    'othersetup'    => array('label' => 'Other Setup',               'icon' => '🔧', 'css' => 'a[href*="admin/const.php"]'),
);

$moduleTabs = array(
    'marketplace' => array('label' => 'Find external modules', 'icon' => '🔍', 'css' => 'a[href*="mode=marketplace"]'),
    'deploy'      => array('label' => 'Deploy/install module',  'icon' => '📤', 'css' => 'a[href*="mode=deploy"]'),
    'develop'     => array('label' => 'Develop your own module','icon' => '🧑‍💻', 'css' => 'a[href*="mode=develop"]'),
);

$nativeDefaults = array(
    'home'        => array('fa fa-home',                'Dashboard'),
    'members'     => array('fa fa-users',               'Members'),
    'companies'   => array('fa fa-building',            'Third Parties'),
    'products'    => array('fa fa-cube',                'Products / Services'),
    'mrp'         => array('fa fa-industry',            'MRP / Manufacturing'),
    'project'     => array('fa fa-project-diagram',     'Projects'),
    'commercial'  => array('fa fa-briefcase',           'Commercial'),
    'billing'     => array('fa fa-money-bill-wave',     'Billing / Payments'),
    'bank'        => array('fa fa-university',          'Banks / Cash'),
    'accountancy' => array('fa fa-calculator',          'Accountancy'),
    'hrm'         => array('fa fa-user-tie',            'HR / Leaves'),
    'ticket'      => array('fa fa-ticket-alt',          'Tickets / Support'),
    'tools'       => array('fa fa-wrench',              'Tools'),
);

// ──────────────────────────────────────────────────────────────────────────────
// Actions
// ──────────────────────────────────────────────────────────────────────────────
$mesg = "";
$action = GETPOST('action', 'aZ09');
$sendit = GETPOST('sendit', 'alpha');

if(!$conf->revolutionpro->enabled){ accessforbidden(); }
if(!empty($action) || !empty($sendit)){
	if (!$user->admin) accessforbidden();
}
if (!$user->admin && (!empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUEX) && $conf->global->REVOLUTIONPRO_PARAMETRES_VALUEX != 'demo')) accessforbidden();

// ── Original Revolution Pro values ──
$val1 = "light"; $val2 = "inverse"; $val3 = "teal"; $val6 = "primary";
$val4 = "show"; $val7 = "show"; $val5 = 'revolutionprologin1.jpg';

$val1 = GETPOST('value1','alpha') ? GETPOST('value1','alpha') : $val1;
$val2 = GETPOST('value2','alpha') ? GETPOST('value2','alpha') : $val2;
$val3 = GETPOST('value3','alpha') ? GETPOST('value3','alpha') : $val3;
$val6 = GETPOST('value6','alpha') ? GETPOST('value6','alpha') : $val6;
$val4 = GETPOST('value4','alpha') ? GETPOST('value4','alpha') : $val4;
$val7 = GETPOST('value7','alpha') ? GETPOST('value7','alpha') : $val7;
$val5 = GETPOST('value5','alpha') ? GETPOST('value5','alpha') : $val5;
$val8 = GETPOST('value8','alpha') ? GETPOST('value8','alpha') : 'tiers';
$val9 = GETPOST('value9','alpha') ? GETPOST('value9','alpha') : 'projets';
$val10 = GETPOST('value10','alpha') ? GETPOST('value10','alpha') : 'devis';
$val11 = GETPOST('value11','alpha') ? GETPOST('value11','alpha') : 'factures';
$val8c = GETPOST('value8c','alpha') ? GETPOST('value8c','alpha') : 'indigo-400';
$val9c = GETPOST('value9c','alpha') ? GETPOST('value9c','alpha') : 'green-300';
$val10c = GETPOST('value10c','alpha') ? GETPOST('value10c','alpha') : 'purple-300';
$val11c = GETPOST('value11c','alpha') ? GETPOST('value11c','alpha') : 'amber-600';
$valcss = '';

$arr = explode(".", $val5);
if(empty($val5) || count($arr) <= 1) $val5 = 'revolutionprologin1.jpg';

// ── Original update action ──
if ($action == 'update') {
	$val2 = GETPOST('value2','alpha') ? GETPOST('value2','alpha') : 'notinverse';
   	dolibarr_set_const($db, "MAIN_HOME", dol_htmlcleanlastbr(GETPOST("main_home", 'none')), 'chaine', 0, '', $conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUECSS", dol_htmlcleanlastbr(GETPOST("valuecss", 'none')), 'chaine', 0, '', $conf->entity);
}
if ($action == 'update' || $action == 'defaultparameters') {
 	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE1", $val1,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE2", $val2,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE3", $val3,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE6", $val6,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE4", $val4,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE7", $val7,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE5", $val5,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE8", $val8,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE9", $val9,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE10", $val10,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE11", $val11,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE8C", $val8c,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE9C", $val9c,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE10C", $val10c,'chaine',0,'',$conf->entity);
   	dolibarr_set_const($db, "REVOLUTIONPRO_PARAMETRES_VALUE11C", $val11c,'chaine',0,'',$conf->entity);
}

// ── Action: Save Icons ──
if ($action === 'saveicons') {
    // Dolibarr's GETPOST doesn't handle PHP array inputs (icon_keys[]) properly
    $iconKeys = isset($_POST['icon_keys']) ? $_POST['icon_keys'] : array();
    $errCount = 0;
    if (is_array($iconKeys) && count($iconKeys) > 0) {
        foreach ($iconKeys as $rawKey) {
            $rawKey = trim($rawKey);
            if (empty($rawKey)) continue;
            // Get POST values using the raw key BEFORE escaping
            $faIcon      = GETPOST('fa_'.$rawKey, 'alphanohtml');
            $customLabel = GETPOST('label_'.$rawKey, 'alphanohtml');
            $isHidden    = GETPOST('hidden_'.$rawKey, 'int') ? 1 : 0;
            // Now escape for SQL
            $safeKey   = $db->escape($rawKey);
            $safeIcon  = $db->escape($faIcon);
            $safeLabel = $db->escape($customLabel);
            $sql_upd = "UPDATE ".MAIN_DB_PREFIX."revolutionpro_config SET fa_icon='".$safeIcon."', custom_label='".$safeLabel."', is_hidden=".$isHidden." WHERE menu_key='".$safeKey."' AND entity=1";
            if (!$db->query($sql_upd)) { $errCount++; }
        }
    } else {
        $errCount = -1; // No keys received
    }
    $mesg = $errCount === 0
        ? '<div class="ok">✅ Icons saved! Reload the app (Ctrl+Shift+R) to see changes.</div>'
        : '<div class="error">❌ '.($errCount == -1 ? 'No icon data received. Check form.' : $errCount.' icon(s) could not be saved.').'</div>';
}

// ── Action: Save visibility (menus, admin tools, module tabs) ──
if (in_array($action, array('savemenus','saveadmintools','savesetupmenu','savemoduletabs'))) {
    $cssFile = __DIR__ . '/flavorpro_hidden.css';
    $existingContent = file_exists($cssFile) ? file_get_contents($cssFile) : '';

    $sections = array('menus' => '', 'admintools' => '', 'setupmenus' => '', 'moduletabs' => '');
    if (preg_match('/\/\* SECTION: MENUS \*\/(.*?)\/\* END: MENUS \*\//s', $existingContent, $m)) $sections['menus'] = $m[1];
    if (preg_match('/\/\* SECTION: ADMINTOOLS \*\/(.*?)\/\* END: ADMINTOOLS \*\//s', $existingContent, $m)) $sections['admintools'] = $m[1];
    if (preg_match('/\/\* SECTION: SETUPMENUS \*\/(.*?)\/\* END: SETUPMENUS \*\//s', $existingContent, $m)) $sections['setupmenus'] = $m[1];
    if (preg_match('/\/\* SECTION: MODULETABS \*\/(.*?)\/\* END: MODULETABS \*\//s', $existingContent, $m)) $sections['moduletabs'] = $m[1];

    if ($action === 'savemenus') {
        $sections['menus'] = "\n";
        foreach ($availableMenus as $key => $menu) {
            if (GETPOST('hide_'.$key, 'alpha')) {
                // Revolution Pro sidebar uses: <li class="site-menu-item {mainmenu} ...">
                $sections['menus'] .= "li.site-menu-item.{$key}, #mainmenutd_{$key}, li.tmenu[data-mainmenu=\"{$key}\"] { display: none !important; }\n";
            }
        }
    }
    if ($action === 'saveadmintools') {
        $sections['admintools'] = "\n";
        foreach ($adminToolsSubmenus as $key => $item) {
            if (GETPOST('hide_at_'.$key, 'alpha')) {
                $sections['admintools'] .= "li.site-menu-item:has({$item['css']}), {$item['css']} { display: none !important; }\n";
            }
        }
    }
    if ($action === 'savesetupmenu') {
        $sections['setupmenus'] = "\n";
        foreach ($setupSubmenus as $key => $item) {
            if (GETPOST('hide_sm_'.$key, 'alpha')) {
                $sections['setupmenus'] .= "li.site-menu-item:has({$item['css']}), {$item['css']} { display: none !important; }\n";
            }
        }
    }
    if ($action === 'savemoduletabs') {
        $sections['moduletabs'] = "\n";
        foreach ($moduleTabs as $key => $tab) {
            if (GETPOST('hide_mt_'.$key, 'alpha')) {
                $sections['moduletabs'] .= "li:has({$tab['css']}), {$tab['css']} { display: none !important; }\n";
            }
        }
    }

    $cssContent  = "/* Auto-generated by Flavor Pro Setup — ".date('Y-m-d H:i:s')." */\n\n";
    $cssContent .= "/* SECTION: MENUS */".$sections['menus']."/* END: MENUS */\n\n";
    $cssContent .= "/* SECTION: ADMINTOOLS */".$sections['admintools']."/* END: ADMINTOOLS */\n\n";
    $cssContent .= "/* SECTION: SETUPMENUS */".$sections['setupmenus']."/* END: SETUPMENUS */\n\n";
    $cssContent .= "/* SECTION: MODULETABS */".$sections['moduletabs']."/* END: MODULETABS */\n";

    if (file_put_contents($cssFile, $cssContent) !== false) {
        $mesg = '<div class="ok">✅ Visibility settings saved! Changes are active immediately.</div>';
    } else {
        $mesg = '<div class="error">❌ Could not write flavorpro_hidden.css. Check permissions.</div>';
    }
}

// ── Action: Lock ──
if ($action === 'lock') {
    if (file_put_contents(__DIR__ . '/flavorpro.lock', 'Locked on '.date('Y-m-d H:i:s').' by '.$user->login) !== false) {
        $mesg = '<div class="ok">🔒 Setup locked! Delete <code>flavorpro.lock</code> to unlock.</div>';
    } else {
        $mesg = '<div class="error">❌ Could not create lock file.</div>';
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// Read current state
// ──────────────────────────────────────────────────────────────────────────────
$object = new revolutionpro($db);
$val1 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE1) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE1 : $val1;
$val2 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE2) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE2 : $val2;
$val3 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE3 : $val3;
$val6 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE6) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE6 : $val6;
$val4 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE4) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE4 : $val4;
$val7 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE7) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE7 : $val7;
$val5 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE5) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE5 : $val5;
$val8 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE8) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE8 : $val8;
$val9 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE9) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE9 : $val9;
$val10 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE10) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE10 : $val10;
$val11 = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE11) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE11 : $val11;
$val8c = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE8C) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE8C : $val8c;
$val9c = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE9C) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE9C : $val9c;
$val10c = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE10C) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE10C : $val10c;
$val11c = !empty($conf->global->REVOLUTIONPRO_PARAMETRES_VALUE11C) ? $conf->global->REVOLUTIONPRO_PARAMETRES_VALUE11C : $val11c;
$valcss = $object->parametrevalcss ? $object->parametrevalcss : $valcss;

$arr = explode(".", $val5);
if(empty($val5) || count($arr) <= 1) $val5 = 'revolutionprologin1.jpg';

// ── Read icon configuration from llx_revolutionpro_config ──
$iconConfig = array();
$sql_icons = "SELECT menu_key, fa_icon, custom_label, is_hidden, sort_order FROM ".MAIN_DB_PREFIX."revolutionpro_config WHERE entity=1 ORDER BY sort_order ASC";
$resql = $db->query($sql_icons);
if ($resql) {
    while ($obj = $db->fetch_object($resql)) {
        $iconConfig[$obj->menu_key] = array(
            'fa_icon'      => $obj->fa_icon,
            'custom_label' => $obj->custom_label,
            'is_hidden'    => (int) $obj->is_hidden,
            'sort_order'   => (int) $obj->sort_order,
        );
    }
}

// ── Pre-seed native menus ──
$nativeSort = 10;
foreach ($availableMenus as $nativeKey => $nativeMenu) {
    if (!isset($iconConfig[$nativeKey])) {
        $icon  = isset($nativeDefaults[$nativeKey]) ? $nativeDefaults[$nativeKey][0] : 'fas fa-layer-group';
        $label = isset($nativeDefaults[$nativeKey]) ? $nativeDefaults[$nativeKey][1] : ucfirst($nativeKey);
        $safeMk    = $db->escape($nativeKey);
        $safeIcon  = $db->escape($icon);
        $safeLabel = $db->escape($label);
        $db->query("INSERT INTO ".MAIN_DB_PREFIX."revolutionpro_config (menu_key, fa_icon, custom_label, sort_order, entity) VALUES ('".$safeMk."', '".$safeIcon."', '".$safeLabel."', ".$nativeSort.", 1)");
        $iconConfig[$nativeKey] = array('fa_icon' => $icon, 'custom_label' => $label, 'is_hidden' => 0, 'sort_order' => $nativeSort);
    }
    $nativeSort += 10;
}

// ── Auto-detect module menus from llx_menu ──
$moduleIconDefaults = array(
    'agenda' => array('fa fa-calendar-check', 'Agenda'), 'billing' => array('fa fa-file-invoice', 'Billing'),
    'ecm' => array('fa fa-folder-open', 'Documents'), 'mrp' => array('fa fa-industry', 'Manufacturing'),
    'takepos' => array('fa fa-cash-register', 'TakePOS'), 'website' => array('fa fa-globe', 'Website'),
    'recruitment' => array('fa fa-user-plus', 'Recruitment'), 'holiday' => array('fa fa-umbrella-beach', 'Holidays'),
    'expensereport' => array('fa fa-money-check-alt', 'Expenses'), 'don' => array('fa fa-hand-holding-heart', 'Donations'),
    'loan' => array('fa fa-piggy-bank', 'Loans'), 'contracts' => array('fa fa-file-signature', 'Contracts'),
    'shipping' => array('fa fa-shipping-fast', 'Shipments'), 'stock' => array('fa fa-warehouse', 'Stock'),
);

$sql_menus = "SELECT DISTINCT mainmenu FROM ".MAIN_DB_PREFIX."menu WHERE mainmenu != '' AND entity IN (0,1) ORDER BY mainmenu";
$resql = $db->query($sql_menus);
if ($resql) {
    $maxSort = count($iconConfig) * 10 + 10;
    while ($obj = $db->fetch_object($resql)) {
        $mk = $obj->mainmenu;
        if (!isset($iconConfig[$mk])) {
            $autoIcon  = isset($moduleIconDefaults[$mk]) ? $moduleIconDefaults[$mk][0] : 'fas fa-layer-group';
            $autoLabel = isset($moduleIconDefaults[$mk]) ? $moduleIconDefaults[$mk][1] : ucfirst($mk);
            $safeMk    = $db->escape($mk);
            $safeIcon  = $db->escape($autoIcon);
            $safeLabel = $db->escape($autoLabel);
            $db->query("INSERT INTO ".MAIN_DB_PREFIX."revolutionpro_config (menu_key, fa_icon, custom_label, sort_order, entity) VALUES ('".$safeMk."', '".$safeIcon."', '".$safeLabel."', ".$maxSort.", 1)");
            $iconConfig[$mk] = array('fa_icon' => $autoIcon, 'custom_label' => $autoLabel, 'is_hidden' => 0, 'sort_order' => $maxSort);
            $maxSort += 10;
        }
    }
}

// ── Read hidden items from CSS file ──
$currentlyHidden = array();
$currentlyHiddenAT = array();
$currentlyHiddenSM = array();
$currentlyHiddenMT = array();
$cssFile = __DIR__ . '/flavorpro_hidden.css';
if (file_exists($cssFile)) {
    $content = file_get_contents($cssFile);
    foreach ($availableMenus as $key => $menu) {
        if (strpos($content, 'li.site-menu-item.'.$key) !== false) $currentlyHidden[$key] = true;
    }
    foreach ($adminToolsSubmenus as $key => $item) {
        if (strpos($content, $item['css']) !== false) $currentlyHiddenAT[$key] = true;
    }
    foreach ($setupSubmenus as $key => $item) {
        if (strpos($content, $item['css']) !== false) $currentlyHiddenSM[$key] = true;
    }
    foreach ($moduleTabs as $key => $tab) {
        if (strpos($content, $tab['css']) !== false) $currentlyHiddenMT[$key] = true;
    }
}

// ── File upload setup for login images ──
$sortfield = GETPOST("sortfield", 'alpha');
$sortorder = GETPOST("sortorder", 'alpha');
$permissiontoadd = $user->admin;
$upload_dir = $conf->mycompany->dir_output."/logos/login/1";
$relativepathwithnofile = "/logos/login/1/";
$permissiontoadd = 1;
include_once DOL_DOCUMENT_ROOT.'/core/actions_linkedfiles.inc.php';

global $user,$conf;
$form = new Form($db);
$filearray = dol_dir_list($upload_dir, "files", 0, '', '(\.meta|_preview.*\.png)$', $sortfield, (strtolower($sortorder) == 'desc' ?SORT_DESC:SORT_ASC), 1);
$modulepart = 'mycompany';
$permission = $user->admin;
$permtoedit = $user->admin;
$param = '';

// ──────────────────────────────────────────────────────────────────────────────
// VIEW
// ──────────────────────────────────────────────────────────────────────────────
$pagen = "RevolutionproSetup";
llxHeader('', $langs->trans($pagen),'','','','', array(),'' );

$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($langs->trans($pagen), $linkback);

$head = revolutionpro_admin_prepare_head();
dol_fiche_head($head, 'settings', $langs->trans("Module940326081Name"), 0, "revolutionpro@revolutionpro");

dol_htmloutput_mesg($mesg);

print '<form id="settingrevopro" method="post" action="setup.php">';
print '<div id="settingrevolutionprotheme">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="update">';
print '<div class="nav-tabs-horizontal">';
print '<ul role="tablist" class="nav nav-tabs nav-tabs-line">';
	// Original tabs
	print '<li class="nav-item revoprocolors"><a class="nav-link active show" role="tab" href="#revoprocolors" data-toggle="tab">'.$langs->trans('Colors').'</a></li>';
	print '<li class="nav-item revoprofourboxcontent"><a class="nav-link" role="tab" href="#revoprofourboxcontent" data-toggle="tab">'.$langs->trans('RevolutionProFourBoxesContent').'</a></li>';
	print '<li class="nav-item revoprofourbox"><a class="nav-link" role="tab" href="#revoprofourbox" data-toggle="tab">'.$langs->trans('RevolutionProFourBoxes').'</a></li>';
	print '<li class="nav-item revoprologin"><a class="nav-link" role="tab" href="#revoprologin" data-toggle="tab">'.$langs->trans('LoginPage').'</a></li>';
	print '<li class="nav-item revoproCSSaddi"><a class="nav-link" role="tab" href="#revoproCSSaddi" data-toggle="tab">'.$langs->trans('CSS additionnel').'</a></li>';
	// New tabs
	print '<li class="nav-item"><a class="nav-link" role="tab" href="#revoproIcons" data-toggle="tab">🎯 Icon Manager</a></li>';
	print '<li class="nav-item"><a class="nav-link" role="tab" href="#revoproMenus" data-toggle="tab">📋 Menu Manager</a></li>';
	print '<li class="nav-item"><a class="nav-link" role="tab" href="#revoproAdminTools" data-toggle="tab">🛠️ Admin Tools</a></li>';
	print '<li class="nav-item"><a class="nav-link" role="tab" href="#revoproModuleTabs" data-toggle="tab">🧩 Module Tabs</a></li>';
print '</ul>';
print '<div class="tab-content">';

// ═══════════════════════════════════════════════════════════════════════════════
// ORIGINAL TAB: COLORS (kept exactly as before)
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_colors.php';

// ═══════════════════════════════════════════════════════════════════════════════
// ORIGINAL TAB: FOUR BOX CONTENT
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_fourboxcontent.php';

// ═══════════════════════════════════════════════════════════════════════════════
// ORIGINAL TAB: SHOW & HIDE
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_fourbox.php';

// ═══════════════════════════════════════════════════════════════════════════════
// ORIGINAL TAB: LOGIN PAGE
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_login.php';

// ═══════════════════════════════════════════════════════════════════════════════
// ORIGINAL TAB: CSS ADDITIONNEL
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_css.php';

// ═══════════════════════════════════════════════════════════════════════════════
// NEW TAB: ICON MANAGER
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_icons.php';

// ═══════════════════════════════════════════════════════════════════════════════
// NEW TAB: MENU MANAGER
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_menus.php';

// ═══════════════════════════════════════════════════════════════════════════════
// NEW TAB: ADMIN TOOLS
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_admintools.php';

// ═══════════════════════════════════════════════════════════════════════════════
// NEW TAB: MODULE TABS
// ═══════════════════════════════════════════════════════════════════════════════
require __DIR__.'/setup_tab_moduletabs.php';

print '</div>'; // tab-content
print '</div>'; // nav-tabs-horizontal

print '</div>'; // settingrevolutionprotheme

print '<div class="divrevolutionprobuttonadmin">';
print '<div style="clear:both;"></div>';
print '<input type="submit" id="submitbutton" class="butAction" value="'.$langs->trans('Validate').'" style="float: left;">';
print '<a class="butActionDelete" style="float: right;" href="'.dol_buildpath('/revolutionpro/admin/setup.php', 2).'?action=defaultparameters">'.$langs->trans('RevoProDefaultsParamets').'</a>';
print '</div>';
print '</form>';

// ═══════════════════════════════════════════════════════════════════════════════
// SECURITY LOCK CARD (outside the main form)
// ═══════════════════════════════════════════════════════════════════════════════
print '<div style="margin-top:30px;padding:20px;background:#FFF;border-radius:12px;border:1px solid #E2E8F0;">';
print '<h3 style="margin-top:0;">🔐 Security Lock</h3>';
print '<p style="color:#64748B;">Lock this setup page after configuration. Delete <code>flavorpro.lock</code> from the admin directory to unlock.</p>';
print '<a href="?action=lock&token='.newToken().'" class="butActionDelete" onclick="return confirm(\'Lock the setup page? You need file access to unlock.\');" style="margin-top:10px;">🔒 Lock Setup Page</a>';
print '</div>';

print '<div id="uploadimagelogin" style="display:none;">';
include_once DOL_DOCUMENT_ROOT.'/core/tpl/document_actions_post_headers.tpl.php';
print '</div>';

?>
<style type="text/css">
	div.tabs a.ds_url_module_name{ margin-right: 11px; }
	/* Flavor Pro Setup Styles */
	.flavorpro-card{background:#FFF;border-radius:12px;padding:24px;border:1px solid #E2E8F0;margin-bottom:16px;}
	.flavorpro-card h3{margin-top:0;font-size:1rem;display:flex;align-items:center;gap:8px;}
	.fp-menu-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:8px;}
	.fp-menu-item{display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;border:1px solid #E2E8F0;background:#FAFBFC;cursor:pointer;transition:all .15s;}
	.fp-menu-item:hover{border-color:#CBD5E1;background:#F1F5F9;}
	.fp-menu-item.checked{border-color:#FCA5A5;background:#FEF2F2;}
	.fp-menu-item input[type="checkbox"]{width:16px;height:16px;cursor:pointer;}
	.fp-menu-item-label{font-size:.85rem;font-weight:500;}
	.fp-section-label{font-size:.75rem;font-weight:600;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin:12px 0 6px;}
	.fp-icon-table{width:100%;border-collapse:separate;border-spacing:0;}
	.fp-icon-table th{background:#F8FAFC;padding:8px 10px;font-size:.75rem;font-weight:600;text-transform:uppercase;color:#64748B;border-bottom:2px solid #E2E8F0;text-align:left;}
	.fp-icon-table td{padding:8px 10px;border-bottom:1px solid #F1F5F9;vertical-align:middle;}
	.fp-icon-table tr:hover td{background:#FAFBFC;}
	.fp-icon-table input[type=text]{padding:6px 8px;border:1px solid #E2E8F0;border-radius:6px;font-size:.85rem;width:100%;outline:none;font-family:inherit;}
	.fp-icon-table input[type=text]:focus{border-color:#6366F1;box-shadow:0 0 0 2px rgba(99,102,241,.08);}
	.fp-fa-preview{width:30px;height:30px;background:#312C81;border-radius:6px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.9);font-size:13px;}
	.fp-toggle{position:relative;width:38px;height:20px;display:inline-block;}
	.fp-toggle input{opacity:0;width:0;height:0;}
	.fp-toggle-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#CBD5E1;border-radius:20px;transition:.2s;}
	.fp-toggle-slider::before{content:'';position:absolute;width:14px;height:14px;left:3px;top:3px;background:#FFF;border-radius:50%;transition:.2s;}
	.fp-toggle input:checked+.fp-toggle-slider{background:#EF4444;}
	.fp-toggle input:checked+.fp-toggle-slider::before{transform:translateX(18px);}
	.fp-native{font-size:.6rem;background:#DCFCE7;color:#166534;padding:1px 5px;border-radius:3px;font-weight:600;margin-left:4px;}
	.fp-module{font-size:.6rem;background:#E0E7FF;color:#3730A3;padding:1px 5px;border-radius:3px;font-weight:600;margin-left:4px;}
	.fp-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;border:none;color:#FFF;transition:all .2s;font-family:inherit;}
	.fp-btn-primary{background:linear-gradient(135deg,#6366F1,#818CF8);box-shadow:0 4px 12px rgba(99,102,241,.25);}
	.fp-btn-primary:hover{transform:translateY(-1px);}
	.fp-btn-success{background:linear-gradient(135deg,#059669,#10B981);box-shadow:0 4px 12px rgba(16,185,129,.25);}
	.fp-btn-success:hover{transform:translateY(-1px);}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('div.revolutionprofilename').click(function(){
		if(!$(this).find('input').is(':checked')) $(this).find('input').click();
	});
	$('ul.nav-tabs li').click(function(){
		$('#uploadimagelogin').toggle($(this).hasClass('revoprologin'));
	});
	$('#skintoolsNavbar2-inverse').click(function(){
		$('body.site-navbar-small .site-navbar').toggleClass('navbar-inverse', $(this).is(':checked'));
	});
	$('#revoprocolors .radio-custom input[name="value1"]').change(function() {
		$('body #tmenu_tooltip > .site-menubar').toggleClass('site-menubar-dark', this.value == 'dark');
	});
	$('#showorhidefourboxes input[name="value4"]').change(function() {
		$('body .row.thefourboxes').toggle(this.value !== 'hide');
	});
	$('#showorhidecompanyname input[name="value7"]').change(function() {
		if($('body').hasClass('site-menubar-fold')) $('.navbar-toolbar #toggleMenubar>a').click();
		var showIt = this.value !== 'hide';
		setTimeout(function(){ $('body.site-menubar-unfold .site-navbar .navbar-brand-text').toggle(showIt); }, 300);
	});
	$('#revoprocolors .radio-custom input[name="value6"]').change(function() {
		var bg = "bg-".concat(this.value, "-600");
		if (this.value === 'yellow') bg = 'bg-yellow-800';
		$('.butAction, #mainbody input.button:not(.buttongen):not(.bordertransp)').removeClass(function(i,c){return(c.match(/(^|\s)bg-\S+/g)||[]).join(' ');}).addClass(bg);
	});
	$('#revoprocolors .radio-custom input[name="value3"]').change(function() {
		var bg = "bg-".concat(this.value, "-600");
		if (this.value === 'yellow') bg = 'bg-yellow-800';
		if (this.value === 'primary') bg = '';
		$("body.site-navbar-small .site-navbar").removeClass(function(i,c){return(c.match(/(^|\s)bg-\S+/g)||[]).join(' ');}).addClass(bg);
	});
	// Toggle checked class on menu items
	$('.fp-menu-item input[type="checkbox"]').change(function(){
		$(this).closest('.fp-menu-item').toggleClass('checked', this.checked);
	});
});
</script>
<?php

dol_fiche_end();
llxFooter();
$db->close();
?>