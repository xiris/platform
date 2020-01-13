<?php

use function Claxifieds\Helpers\_e;
use function Claxifieds\Helpers\getBoolPreference;
use function Claxifieds\Helpers\osc_add_hook;
use function Claxifieds\Helpers\osc_assets_url;
use function Claxifieds\Helpers\osc_cache_init;
use function Claxifieds\Helpers\osc_die;
use function Claxifieds\Helpers\osc_get_absolute_url;
use function Claxifieds\Helpers\osc_get_preference;
use function Claxifieds\Helpers\osc_register_script;
use function Claxifieds\Helpers\osc_timezone;

    if ( !file_exists(CONFIG_PATH . 'config.php')) {
        $title   = 'Osclass &raquo; Error';
        $message = 'There doesn\'t seem to be a <code>config.php</code> file. Osclass isn\'t installed. <a href="https://osclass.discourse.group/">Need more help?</a></p>';
        $message .= '<p><a class="button" href="' . osc_get_absolute_url() . 'installer/install.php">Install</a></p>';
        osc_die($title, $message);
    }
    
    require_once CONFIG_PATH . 'config.php';

// Sets PHP error handling
    if (OSC_DEBUG) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL | E_STRICT);

        if (OSC_DEBUG_LOG) {
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
            ini_set('error_log', CONTENT_PATH . 'debug.log');
        }
    } else {
        error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING);
    }

// check if Osclass is installed
    if ( ! getBoolPreference('osclass_installed') && MULTISITE) {
        header('Location: ' . WEB_PATH);
        die;
    } elseif ( ! getBoolPreference('osclass_installed')) {

        $title   = 'Osclass &raquo; Error';
        $message = 'Osclass isn\'t installed. <a href="https://osclass.discourse.group/">Need more help?</a></p>';
        $message .= '<p><a class="button" href="' . osc_get_absolute_url() . 'oc-includes/osclass/install.php">Install</a></p>';

        osc_die($title, $message);
    }
    
    require_once LIB_PATH . 'osclass/core/Params.php';
    require_once LIB_PATH . 'osclass/core/Cookie.php';
    require_once LIB_PATH . 'osclass/core/Session.php';
    require_once LIB_PATH . 'osclass/core/View.php';
    require_once LIB_PATH . 'osclass/core/BaseModel.php';
    require_once LIB_PATH . 'osclass/core/AdminBaseModel.php';
    require_once LIB_PATH . 'osclass/core/SecBaseModel.php';
    require_once LIB_PATH . 'osclass/core/WebSecBaseModel.php';
    require_once LIB_PATH . 'osclass/core/AdminSecBaseModel.php';
    require_once LIB_PATH . 'osclass/core/Translation.php';
    require_once LIB_PATH . 'osclass/core/Themes.php';
    require_once LIB_PATH . 'osclass/core/AdminThemes.php';
    require_once LIB_PATH . 'osclass/core/WebThemes.php';
    require_once LIB_PATH . 'osclass/utils.php';
    require_once LIB_PATH . 'osclass/formatting.php';
    require_once LIB_PATH . 'osclass/locales.php';
    require_once LIB_PATH . 'osclass/core/classes/Plugins.php';
    require_once LIB_PATH . 'osclass/core/ItemActions.php';
    require_once LIB_PATH . 'osclass/emails.php';
    require_once LIB_PATH . 'osclass/core/classes/Cache.php';
    require_once LIB_PATH . 'osclass/core/classes/ImageProcessing.php';
    require_once LIB_PATH . 'osclass/core/classes/RSSFeed.php';
    require_once LIB_PATH . 'osclass/core/classes/Sitemap.php';
    require_once LIB_PATH . 'osclass/core/classes/Pagination.php';
    require_once LIB_PATH . 'osclass/core/classes/Rewrite.php';
    require_once LIB_PATH . 'osclass/core/classes/Stats.php';
    require_once LIB_PATH . 'osclass/core/classes/AdminMenu.php';
    require_once LIB_PATH . 'osclass/core/classes/datatables/DataTable.php';
    require_once LIB_PATH . 'osclass/core/classes/AdminToolbar.php';
    require_once LIB_PATH . 'osclass/core/classes/Breadcrumb.php';
    require_once LIB_PATH . 'osclass/core/classes/EmailVariables.php';
    require_once LIB_PATH . 'osclass/alerts.php';
    require_once LIB_PATH . 'osclass/core/classes/Dependencies.php';
    require_once LIB_PATH . 'osclass/core/classes/Scripts.php';
    require_once LIB_PATH . 'osclass/core/classes/Styles.php';
    require_once LIB_PATH . 'osclass/frm/Form.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Page.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Category.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Item.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Contact.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Comment.form.class.php';
    require_once LIB_PATH . 'osclass/frm/User.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Language.form.class.php';
    require_once LIB_PATH . 'osclass/frm/SendFriend.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Alert.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Field.form.class.php';
    require_once LIB_PATH . 'osclass/frm/Admin.form.class.php';
    require_once LIB_PATH . 'osclass/frm/ManageItems.form.class.php';
    require_once LIB_PATH . 'osclass/frm/BanRule.form.class.php';
    require_once LIB_PATH . 'osclass/functions.php';
    require_once LIB_PATH . 'osclass/compatibility.php';
    
    if ( ! defined('OSC_CRYPT_KEY')) {
        define('OSC_CRYPT_KEY', osc_get_preference('crypt_key'));
    }

    osc_cache_init();

    define('__OSC_LOADED__', true);

    Params::init();
    Session::newInstance()->session_start();

    if (osc_timezone() != '') {
        date_default_timezone_set(osc_timezone());
    }

    function osc_show_maintenance()
    {
        if (defined('__OSC_MAINTENANCE__')) { ?>
            <div id="maintenance" name="maintenance">
                <?php _e("The website is currently undergoing maintenance"); ?>
            </div>
            <style>
                #maintenance {
                    position: static;
                    top: 0px;
                    right: 0px;
                    background-color: #bc0202;
                    width: 100%;
                    height: 20px;
                    text-align: center;
                    padding: 5px 0;
                    font-size: 14px;
                    color: #fefefe;
                }
            </style>
        <?php }
    }


    function osc_meta_generator()
    {
        echo '<meta name="generator" content="Osclass ' . OSCLASS_VERSION . '" />';
    }


    osc_add_hook('header', 'osc_show_maintenance');
    osc_add_hook('header', 'osc_meta_generator');
    osc_add_hook('header', 'osc_load_styles', 9);
    osc_add_hook('header', 'osc_load_scripts', 10);

// register scripts
    osc_register_script('jquery', osc_assets_url('js/jquery.min.js'));
    osc_register_script('jquery-ui', osc_assets_url('js/jquery-ui.min.js'), 'jquery');
    osc_register_script('jquery-json', osc_assets_url('js/jquery.json.js'), 'jquery');
    osc_register_script('jquery-treeview', osc_assets_url('js/jquery.treeview.js'), 'jquery');
    osc_register_script('jquery-nested', osc_assets_url('js/jquery.ui.nestedSortable.js'), 'jquery');
    osc_register_script('jquery-validate', osc_assets_url('js/jquery.validate.min.js'), 'jquery');
    osc_register_script('tabber', osc_assets_url('js/tabber-minimized.js'), 'jquery');
    osc_register_script('tiny_mce', osc_assets_url('js/tinymce/tinymce.min.js'));
    osc_register_script('colorpicker', osc_assets_url('js/colorpicker/js/colorpicker.js'));
    osc_register_script('fancybox', osc_assets_url('js/fancybox/jquery.fancybox.pack.js'), array ('jquery'));
    osc_register_script('jquery-migrate', osc_assets_url('js/jquery-migrate.min.js'), array ('jquery'));
    osc_register_script('php-date', osc_assets_url('js/date.js'));
    osc_register_script('jquery-fineuploader', osc_assets_url('js/fineuploader/jquery.fineuploader.min.js'), 'jquery');


    Plugins::init();
    Translation::init();
    osc_csrfguard_start();

    if (OC_ADMIN) {
        // init admin menu
        AdminMenu::newInstance()->init();
        $functions_path = AdminThemes::newInstance()->getCurrentThemePath() . 'functions.php';
        if (file_exists($functions_path)) {
            require_once $functions_path;
        }
    } else {
        Rewrite::newInstance()->init();
    }
