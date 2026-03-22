<?php
// TAB: Admin Tools — Submenu Control (NEW)
print '<div role="tabpanel" id="revoproAdminTools" class="tab-pane">';

// ── Section 1: Admin Tools Submenus ──
print '<div class="flavorpro-card">';
print '<h3>🛠️ Admin Tools — Submenu Control</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Granular control over each Admin Tools sidebar item. Check to <strong>hide</strong>.</p>';
print '</div>';

print '<form method="POST" action="?action=saveadmintools">';
print '<input type="hidden" name="token" value="'.newToken().'">';

$groupLabels = array(
    'audit' => 'Security & Sessions',
    'backup' => 'Maintenance',
    'vat_update' => 'Utilities',
);

print '<div class="fp-section-label">System Information</div>';
print '<div class="fp-menu-grid">';
foreach ($adminToolsSubmenus as $key => $item) {
    // Insert group dividers
    if (isset($groupLabels[$key])) {
        print '</div>';
        print '<div class="fp-section-label">'.$groupLabels[$key].'</div>';
        print '<div class="fp-menu-grid">';
    }
    $isChecked = !empty($currentlyHiddenAT[$key]);
    print '<label class="fp-menu-item '.($isChecked ? 'checked' : '').'">';
    print '<input type="checkbox" name="hide_at_'.$key.'" value="1" '.($isChecked ? 'checked' : '').'>';
    print '<span style="font-size:16px;">'.$item['icon'].'</span>';
    print '<span class="fp-menu-item-label">'.htmlspecialchars($item['label']).'</span>';
    print '</label>';
}
print '</div>';
print '<div style="margin-top:12px;"><button type="submit" class="fp-btn fp-btn-success">💾 Save Admin Tools Visibility</button>';
if (file_exists(__DIR__.'/flavorpro_hidden.css')) {
    $cssContent = file_get_contents(__DIR__.'/flavorpro_hidden.css');
    if (preg_match('/SECTION: ADMINTOOLS/', $cssContent)) {
        print ' <span style="color:#64748B;font-size:.8rem;margin-left:8px;">Last saved: '.date('d/m/Y H:i', filemtime(__DIR__.'/flavorpro_hidden.css')).'</span>';
    }
}
print '</div>';
print '</form>';

// ── Section 2: Setup Submenus ──
print '<div class="flavorpro-card" style="margin-top:24px;">';
print '<h3>⚙️ Setup — Submenu Control</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Granular control over each Setup sidebar item (Home → Setup). Check to <strong>hide</strong>.</p>';
print '</div>';

print '<form method="POST" action="?action=savesetupmenu">';
print '<input type="hidden" name="token" value="'.newToken().'">';

$smGroupLabels = array(
    'widgets' => 'UI & Notifications',
    'pdf' => 'Communication',
    'dictionaries' => 'Configuration',
);

print '<div class="fp-section-label">Core Setup</div>';
print '<div class="fp-menu-grid">';
foreach ($setupSubmenus as $key => $item) {
    if (isset($smGroupLabels[$key])) {
        print '</div>';
        print '<div class="fp-section-label">'.$smGroupLabels[$key].'</div>';
        print '<div class="fp-menu-grid">';
    }
    $isChecked = !empty($currentlyHiddenSM[$key]);
    print '<label class="fp-menu-item '.($isChecked ? 'checked' : '').'">';
    print '<input type="checkbox" name="hide_sm_'.$key.'" value="1" '.($isChecked ? 'checked' : '').'>';
    print '<span style="font-size:16px;">'.$item['icon'].'</span>';
    print '<span class="fp-menu-item-label">'.htmlspecialchars($item['label']).'</span>';
    print '</label>';
}
print '</div>';
print '<div style="margin-top:12px;"><button type="submit" class="fp-btn fp-btn-success">💾 Save Setup Submenu Visibility</button>';
if (file_exists(__DIR__.'/flavorpro_hidden.css')) {
    $cssContent2 = file_get_contents(__DIR__.'/flavorpro_hidden.css');
    if (preg_match('/SECTION: SETUPMENUS/', $cssContent2)) {
        print ' <span style="color:#64748B;font-size:.8rem;margin-left:8px;">Last saved: '.date('d/m/Y H:i', filemtime(__DIR__.'/flavorpro_hidden.css')).'</span>';
    }
}
print '</div>';
print '</form>';

print '</div>';
