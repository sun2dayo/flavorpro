<?php
// TAB: Admin Tools — Submenu Control (NEW)
print '<div role="tabpanel" id="revoproAdminTools" class="tab-pane">';
print '<div class="flavorpro-card">';
print '<h3>🛠️ Admin Tools — Submenu Control</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Granular control over each Admin Tools sidebar item. Check to <strong>hide</strong>.</p>';
print '</div>';

print '<form method="POST" action="?action=saveadmintools">';
print '<input type="hidden" name="token" value="'.newToken().'">';

$groupLabels = array(
    'admin_tools_listevents' => 'Security & Sessions',
    'admin_tools_dolibarr_export' => 'Maintenance',
    'product_admin_product_tools' => 'Utilities',
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
print '<div style="margin-top:12px;"><button type="submit" class="fp-btn fp-btn-success">💾 Save Admin Tools Visibility</button></div>';
print '</form>';
print '</div>';
