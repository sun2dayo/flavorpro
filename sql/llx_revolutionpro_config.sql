-- ============================================================================
-- Table: llx_revolutionpro_config
-- Purpose: Stores sidebar icon/label customizations for Flavor Pro theme
-- Created by: modRevolutionpro module on install
-- ============================================================================

CREATE TABLE IF NOT EXISTS llx_revolutionpro_config (
    rowid        INTEGER AUTO_INCREMENT PRIMARY KEY,
    menu_key     VARCHAR(128)  NOT NULL,
    fa_icon      VARCHAR(128)  DEFAULT 'fas fa-layer-group',
    custom_label VARCHAR(255)  DEFAULT '',
    is_hidden    TINYINT(1)    DEFAULT 0,
    sort_order   INTEGER       DEFAULT 0,
    entity       INTEGER       DEFAULT 1,
    UNIQUE KEY uk_revpro_config_menu (menu_key, entity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
