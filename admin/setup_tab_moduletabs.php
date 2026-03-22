<?php
// TAB: Module Setup Tabs Manager (NEW)
print '<div role="tabpanel" id="revoproModuleTabs" class="tab-pane">';
print '<div class="flavorpro-card">';
print '<h3>🧩 Module Setup Tabs</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Hide tabs from the Modules/Application setup page. Check to <strong>hide</strong>.</p>';
print '</div>';

print '<form method="POST" action="?action=savemoduletabs">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<div class="fp-menu-grid">';
foreach ($moduleTabs as $key => $tab) {
    $isChecked = !empty($currentlyHiddenMT[$key]);
    print '<label class="fp-menu-item '.($isChecked ? 'checked' : '').'">';
    print '<input type="checkbox" name="hide_mt_'.$key.'" value="1" '.($isChecked ? 'checked' : '').'>';
    print '<span style="font-size:16px;">'.$tab['icon'].'</span>';
    print '<span class="fp-menu-item-label">'.htmlspecialchars($tab['label']).'</span>';
    print '</label>';
}
print '</div>';
print '<div style="margin-top:12px;"><button type="submit" class="fp-btn fp-btn-success">💾 Save Tab Visibility</button></div>';
print '</form>';
print '</div>';
