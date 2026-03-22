<?php
// TAB: Icon Manager (NEW)
$nativeKeys = array_keys($availableMenus);

print '<div role="tabpanel" id="revoproIcons" class="tab-pane">';
print '<div class="flavorpro-card">';
print '<h3>🎯 Icon Manager</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Customize FontAwesome icons and labels for each sidebar menu. Changes require Ctrl+Shift+R to take effect.</p>';
print '</div>';

print '<form method="POST" action="?action=saveicons">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<table class="fp-icon-table">';
print '<thead><tr>';
print '<th style="width:36px">Preview</th>';
print '<th>Menu</th>';
print '<th>FA Icon Class</th>';
print '<th>Custom Label</th>';
print '<th style="width:60px">Hide</th>';
print '</tr></thead>';
print '<tbody>';

foreach ($iconConfig as $key => $cfg) {
    $isNative = in_array($key, $nativeKeys);
    print '<tr>';
    print '<td><div class="fp-fa-preview" id="preview_'.$key.'"><i class="'.htmlspecialchars($cfg['fa_icon']).'"></i></div></td>';
    print '<td><span style="font-weight:600;">'.htmlspecialchars($key).'</span>';
    print $isNative ? '<span class="fp-native">native</span>' : '<span class="fp-module">module</span>';
    print '</td>';
    print '<td><input type="hidden" name="icon_keys[]" value="'.htmlspecialchars($key).'">';
    print '<input type="text" name="fa_'.$key.'" value="'.htmlspecialchars($cfg['fa_icon']).'" placeholder="fas fa-icon" oninput="document.querySelector(\'#preview_'.$key.' i\').className = this.value"></td>';
    print '<td><input type="text" name="label_'.$key.'" value="'.htmlspecialchars($cfg['custom_label']).'" placeholder="Label"></td>';
    print '<td><label class="fp-toggle"><input type="checkbox" name="hidden_'.$key.'" value="1" '.($cfg['is_hidden'] ? 'checked' : '').'><span class="fp-toggle-slider"></span></label></td>';
    print '</tr>';
}

print '</tbody></table>';
print '<div style="margin-top:12px;"><button type="submit" class="fp-btn fp-btn-primary">💾 Save Icons</button></div>';
print '</form>';
print '</div>';
