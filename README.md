# FlavorPro — Premium Theme Module for Dolibarr

<p align="center">
  <img src="img/logo/thumb.png" alt="FlavorPro" width="80">
</p>

> **A modern, SaaS-grade UI overhaul for Dolibarr ERP/CRM.**
> Transforms the default Dolibarr interface into a clean, Indigo-branded experience with Inter typography, dark sidebar, white topbar, redesigned login, and TakePOS modernization.

---

## ✨ Features

| Area | What it does |
|------|-------------|
| **Design System** | NovaDX tokens — Indigo palette, Inter font, consistent spacing, shadows & radii |
| **Sidebar** | Dark Indigo (#312E81) with white text, mmenu sub-panel support, custom FA icons per menu |
| **Topbar** | Clean white SaaS-style bar, Indigo hamburger, dark logo area |
| **Login Page** | Split-panel layout — left gradient branding + right clean form, "Welcome back" heading |
| **Dashboard** | Unified white widgets, Indigo info-box icons, modernized Chart.js (rounded bars, thin doughnuts) |
| **TakePOS** | Slate numpad, Indigo function keys, color-coded payment methods, responsive popups |
| **White-Label** | Company name, logo & URL replacement — `dolibarr.org` → your domain |
| **Admin Panel** | Icon Manager, Menu Visibility, Admin Tools toggles, Module Tab config |

## 📋 Requirements

- **Dolibarr** ≥ 7.0 (tested up to 22.x)
- **PHP** ≥ 5.3 (PHP 8.x compatible)
- **Theme**: `eldy` (set automatically on activation)

## 🚀 Installation

1. Copy the `revolutionpro/` folder to `htdocs/custom/revolutionpro/`
2. In **Dolibarr → Setup → Modules**, enable **Revolutionpro**
3. Go to **Setup → Revolutionpro** to configure

Or clone directly:
```bash
cd /path/to/dolibarr/htdocs/custom
git clone https://github.com/sun2dayo/flavorpro.git revolutionpro
```

## ⚙️ Configuration

| Setting | Description |
|---------|-------------|
| **Display** | Toggle topbar items, sidebar behaviour |
| **Login** | Background image, branding text |
| **Menus** | Show/hide 15 Setup submenu items |
| **Icons** | Per-menu Font Awesome icon customization |
| **Admin Tools** | Quick links to Module, User, Security pages |

## 🏗️ Project Structure

```
revolutionpro/
├── admin/            # Setup pages (tabs: General, Display, Login, Menus, Icons)
├── class/            # ActionsRevolutionpro hook class
├── core/modules/     # modRevolutionpro.class.php (module descriptor)
├── css/              # revolutionpro.css.php (main CSS — NovaDX design system)
├── img/              # Logo, login backgrounds, icons
├── js/               # revolutionpro.js.php (chart modernization, icon swap)
├── langs/            # Translations (en_US, fr_FR, pt_PT, …)
├── lib/              # Helper functions
├── sql/              # llx_revolutionpro_config table
└── theme/            # Bootstrap assets, login template, custom.min.css
```

## 📄 License

GNU General Public License v3.0 — see [COPYING](COPYING).

## 🔗 Links

- **Website**: [novadx.pt](https://www.novadx.pt)
- **Support**: ola@novadx.pt
- **Issues**: [github.com/sun2dayo/flavorpro/issues](https://github.com/sun2dayo/flavorpro/issues)

---

*Made with ❤️ by [NovaDX](https://www.novadx.pt)*
