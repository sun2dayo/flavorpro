<?php
// TAB: White-Label — Brand name, logo, text replacement
print '<div role="tabpanel" id="revoproWhiteLabel" class="tab-pane">';

// ── Read current settings ──
$wlEnabled   = getDolGlobalString('REVOLUTIONPRO_WHITELABEL_ENABLED', '0');
$brandName   = getDolGlobalString('REVOLUTIONPRO_BRAND_NAME', '');
$sidebarLogo = getDolGlobalString('REVOLUTIONPRO_SIDEBAR_LOGO', '');
$loginLogo   = getDolGlobalString('REVOLUTIONPRO_LOGIN_LOGO', '');

// ── Discover available logos ──
$logoDir  = dirname(__DIR__).'/img/logos';
$logoUrl  = dol_buildpath('/revolutionpro/img/logos', 1);
$logos    = array();
if (is_dir($logoDir)) {
    $files = scandir($logoDir);
    foreach ($files as $f) {
        if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('png','svg','jpg','jpeg','gif','webp'))) {
            $logos[] = $f;
        }
    }
}

print '<form method="post" action="setup.php">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="savewhitelabel">';

// ── Card: White-Label Settings ──
print '<div class="flavorpro-card">';
print '<h3>🏷️ White-Label Settings</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Replace all "Dolibarr" references with your brand name and customize the logo. Changes are CSS/JS only — no core files are modified. Portable across Dolibarr instances.</p>';

// Enable toggle
print '<div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding:12px 16px;background:#F8FAFC;border-radius:8px;border:1px solid #E2E8F0;">';
print '<label class="fp-toggle"><input type="checkbox" name="wl_enabled" value="1"'.($wlEnabled ? ' checked' : '').'><span class="fp-toggle-slider"></span></label>';
print '<div><strong style="font-size:.9rem;">Enable White-Labeling</strong><br><span style="font-size:.78rem;color:#94A3B8;">Activates brand name replacement and custom logos</span></div>';
print '</div>';

// Brand name input
print '<div style="margin-bottom:20px;">';
print '<label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:6px;">Brand Name</label>';
print '<input type="text" name="brand_name" value="'.dol_escape_htmltag($brandName).'" placeholder="e.g. NovaDX, Dolisys, MyCompany" style="width:100%;max-width:400px;padding:8px 12px;border:1px solid #E2E8F0;border-radius:8px;font-size:.9rem;font-family:inherit;outline:none;" onfocus="this.style.borderColor=\'#6366F1\'" onblur="this.style.borderColor=\'#E2E8F0\'">';
print '<p style="font-size:.75rem;color:#94A3B8;margin-top:4px;">Replaces all visible "Dolibarr" text references on every page</p>';
print '</div>';

print '</div>'; // end card

// ── Card: Logo Configuration ──
print '<div class="flavorpro-card">';
print '<h3>🖼️ Logo Configuration</h3>';
print '<p style="color:#64748B;font-size:.85rem;margin-bottom:16px;">Select logos for the sidebar and login page. Place your logos (PNG/SVG) in <code>revolutionpro/img/logos/</code> and they will appear here automatically.</p>';

if (empty($logos)) {
    print '<div style="padding:20px;background:#FEF3C7;border-radius:8px;border:1px solid #FCD34D;color:#92400E;font-size:.85rem;">⚠️ No logo files found in <code>img/logos/</code>. Place your PNG/SVG logos there first.</div>';
} else {
    // Sidebar Logo
    print '<div style="margin-bottom:24px;">';
    print '<label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:8px;">Sidebar Logo</label>';
    print '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;">';
    // No logo option
    print '<label style="cursor:pointer;padding:10px;border-radius:8px;border:2px solid '.(!$sidebarLogo ? '#6366F1' : '#E2E8F0').';background:'.(!$sidebarLogo ? '#EEF2FF' : '#FAFBFC').';text-align:center;transition:all .15s;">';
    print '<input type="radio" name="sidebar_logo" value="" '.(!$sidebarLogo ? 'checked' : '').' style="display:none;">';
    print '<div style="font-size:.75rem;color:#64748B;padding:8px 0;">Default (system)</div>';
    print '</label>';
    foreach ($logos as $logo) {
        $selected = ($sidebarLogo === $logo);
        print '<label style="cursor:pointer;padding:10px;border-radius:8px;border:2px solid '.($selected ? '#6366F1' : '#E2E8F0').';background:'.($selected ? '#EEF2FF' : '#FAFBFC').';text-align:center;transition:all .15s;">';
        print '<input type="radio" name="sidebar_logo" value="'.dol_escape_htmltag($logo).'" '.($selected ? 'checked' : '').' style="display:none;">';
        print '<img src="'.$logoUrl.'/'.$logo.'" style="max-width:100px;max-height:40px;object-fit:contain;margin-bottom:4px;" alt="'.dol_escape_htmltag($logo).'">';
        print '<div style="font-size:.65rem;color:#94A3B8;word-break:break-all;">'.dol_escape_htmltag($logo).'</div>';
        print '</label>';
    }
    print '</div>';
    print '</div>';

    // Login Logo
    print '<div>';
    print '<label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:8px;">Login Page Logo</label>';
    print '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;">';
    // No logo option
    print '<label style="cursor:pointer;padding:10px;border-radius:8px;border:2px solid '.(!$loginLogo ? '#6366F1' : '#E2E8F0').';background:'.(!$loginLogo ? '#EEF2FF' : '#FAFBFC').';text-align:center;transition:all .15s;">';
    print '<input type="radio" name="login_logo" value="" '.(!$loginLogo ? 'checked' : '').' style="display:none;">';
    print '<div style="font-size:.75rem;color:#64748B;padding:8px 0;">Default (system)</div>';
    print '</label>';
    foreach ($logos as $logo) {
        $selected = ($loginLogo === $logo);
        print '<label style="cursor:pointer;padding:10px;border-radius:8px;border:2px solid '.($selected ? '#6366F1' : '#E2E8F0').';background:'.($selected ? '#EEF2FF' : '#FAFBFC').';text-align:center;transition:all .15s;">';
        print '<input type="radio" name="login_logo" value="'.dol_escape_htmltag($logo).'" '.($selected ? 'checked' : '').' style="display:none;">';
        print '<img src="'.$logoUrl.'/'.$logo.'" style="max-width:100px;max-height:40px;object-fit:contain;margin-bottom:4px;" alt="'.dol_escape_htmltag($logo).'">';
        print '<div style="font-size:.65rem;color:#94A3B8;word-break:break-all;">'.dol_escape_htmltag($logo).'</div>';
        print '</label>';
    }
    print '</div>';
    print '</div>';
}

print '</div>'; // end logo card

// Save button
print '<div style="margin-top:16px;">';
print '<button type="submit" class="fp-btn fp-btn-primary">💾 Save White-Label Settings</button>';
print '</div>';

print '</form>';

print '</div>'; // end tab-pane

?>
<script>
// Radio highlight: toggle border color on logo selection
document.querySelectorAll('#revoproWhiteLabel input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var name = this.name;
        // Reset all labels for this radio group
        document.querySelectorAll('#revoproWhiteLabel input[name="'+name+'"]').forEach(function(r) {
            r.closest('label').style.borderColor = '#E2E8F0';
            r.closest('label').style.background = '#FAFBFC';
        });
        // Highlight selected
        this.closest('label').style.borderColor = '#6366F1';
        this.closest('label').style.background = '#EEF2FF';
    });
});
</script>
