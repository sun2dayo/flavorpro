<?php
// TAB: Menu Manager (NEW)
print '<div role="tabpanel" id="revoproMenus" class="tab-pane">';
print '<div class="flavorpro-card">';
print '<h3>📋 Menu Manager</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Check the menus you want to <strong>hide</strong> from the sidebar navigation. Changes take effect immediately.</p>';
print '</div>';

print '<form method="POST" action="?action=savemenus">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<div class="fp-menu-grid">';
foreach ($availableMenus as $key => $menu) {
    $isChecked = !empty($currentlyHidden[$key]);
    print '<label class="fp-menu-item '.($isChecked ? 'checked' : '').'">';
    print '<input type="checkbox" name="hide_'.$key.'" value="1" '.($isChecked ? 'checked' : '').'>';
    print '<span style="font-size:16px;">'.$menu['icon'].'</span>';
    print '<span class="fp-menu-item-label">'.htmlspecialchars($menu['label']).'</span>';
    print '</label>';
}
print '</div>';
print '<div style="margin-top:12px;display:flex;align-items:center;gap:12px;">';
print '<button type="submit" class="fp-btn fp-btn-success">💾 Save Menu Visibility</button>';
if (file_exists($cssFile)) {
    print '<span style="color:#94A3B8;font-size:.8rem;">Last saved: '.date('d/m/Y H:i', filemtime($cssFile)).'</span>';
}
print '</div>';
print '</form>';
print '</div>';
