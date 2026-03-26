# Changelog

All notable changes to FlavorPro are documented in this file.

## [1.1.0] — 2026-03-26

### 🛒 TakePOS Improvements
- **Top Bar Visibility**: Fixed invisible "Customer", "Currency" labels by adding `a:link/a:visited/a:active` pseudo-class selectors to override Dolibarr core CSS specificity
- **Icons Fixed**: Backspace (cancel), Fullscreen, Shopping Cart icons now render correctly — fixed `font-weight: 900` override for Font Awesome Solid icons
- **Payment Popup**: Fixed overlap between payment amount bars and numpad — container height now `auto` with `min-height`

### 🔧 Module Configuration
- **Icon**: Replaced static image (`thumb.png`) with Font Awesome `fa-palette` icon in module list
- **Version**: Corrected module version from `18.9` to `1.1.0` (aligned with CHANGELOG)
- **Description**: Updated module description to "Flavor Pro Theme Dolisys - Dolisys Theme"
- **Deactivation Lock**: When `admin/flavorpro.lock` exists, module cannot be deactivated — shows error message

### 🐛 Bug Fixes
- Fixed `Undefined property: stdClass::$REVOLUTIONPRO_PARAMETRES_VALUEX` warning on public proposal approval pages
- Eliminated persistent indigo loading screen between page navigations (animsition neutered)

## [1.0.0] — 2026-03-24


### 🎨 NovaDX Design System
- **Design Tokens**: Indigo palette (`#6366F1` primary, `#312E81` sidebar), Inter font, consistent radii/shadows/transitions
- **Global Typography**: Inter font-family enforced across all elements
- **Background**: Clean `#F8FAFC` (Slate-50) SaaS background

### 🗂️ Sidebar
- Dark Indigo (#312E81) background with white text and icons
- mmenu sliding sub-panel support matching sidebar colour
- Submenu arrows, hover effects, and active state indicators
- Company name/logo in navbar header
- Folded sidebar — hidden arrows, clean icon-only display

### 🔝 Topbar
- White SaaS-style topbar with subtle shadow
- Indigo hamburger toggle with animated bars
- Dark logo area matching sidebar for visual continuity
- View toggle icons (List/Kanban) visibility fix

### 🔐 Login Page
- Split-panel layout: left gradient branding + right clean form
- "Welcome back" heading, labelled inputs with FA icons
- Indigo submit button, forgot-password link
- Decorative circles on gradient panel
- Responsive layout for mobile

### 📊 Dashboard
- Unified white widget backgrounds (killed 162+ `bg-*` colour classes)
- Indigo info-box icon circles with left border accent
- Chart.js modernization: rounded bars, thin doughnuts, Inter font, dark tooltips
- Clean panel headings and table styling

### 🛒 TakePOS
- **Topbar**: Indigo gradient matching `var(--colorbackhmenu1)` with white text
- **Numpad**: Dark slate gradient buttons with rounded corners
- **Function Keys**: Indigo-violet gradient (Qty, Price, Line disc.)
- **Payment Popup**: Modernized via `body.revolutionpro:not(.bodytakepos)` selectors
- **Payment Methods**: Color-coded — Cash (emerald), Check (sky blue), Card (indigo), Transfer (teal)
- **History Popup**: Responsive content, no top cutoff
- **Split Sale / Discount**: Indigo gradient headers, refined action buttons

### 🏷️ White-Label
- Company name, logo, and URL replacement engine
- Configurable domain (default: `novadx.pt`)
- Version string: `Dolisys {version}` instead of `NovaDX {version}`
- Update Help button (`?`) hidden

### 🛠️ Admin Panel
- **Icon Manager**: Per-menu Font Awesome icon customisation with live preview
- **Menu Visibility**: Show/hide 15 Setup submenu items via toggle switches
- **Admin Tools**: Quick-access links to Module, User, Security pages
- **Module Tabs**: Configurable tab visibility
- **Security Lock**: Hide module from module list

### 🐛 Bug Fixes
- PHP 8.x compatibility: `!empty()` guards on `$conf->global` access, instance methods instead of static calls
- Fixed `.hideobject` vs `.inline-block` toggle conflict (bootstrap-extend)
- Fixed double Font Awesome icon rendering (font-weight 900 for FA5 Solid)
- Fixed broken images from UTF-8 BOM in PHP files
- Fixed form nesting bug in admin panel (Icon/Menu/Admin/Module forms)
- Fixed CSS specificity issues with `pos.css.php` in TakePOS iframes
