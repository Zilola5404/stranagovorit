<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WprAddons\Admin\Templates\WPR_Templates_Data;
use WprAddons\Classes\Utilities;
use Elementor\Plugin;

// Register Menus
function wpr_addons_add_templates_kit_menu() {
    add_submenu_page( 'wpr-addons', 'Templates Kit', 'Templates Kit', 'manage_options', 'wpr-templates-kit', 'wpr_addons_templates_kit_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_templates_kit_menu' );

// Import Template Kit
add_action( 'wp_ajax_wpr_install_reuired_plugins', 'wpr_install_reuired_plugins' );
add_action( 'wp_ajax_wpr_activate_reuired_theme', 'wpr_activate_reuired_theme' );
add_action( 'wp_ajax_wpr_import_templates_kit', 'wpr_import_templates_kit' );
add_action( 'wp_ajax_wpr_final_settings_setup', 'wpr_final_settings_setup' );
add_action( 'wp_ajax_wpr_search_query_results', 'wpr_search_query_results' );


/**
** Render Templates Kit Page
*/
function wpr_addons_templates_kit_page() {

?>

<div class="wpr-templates-kit-page">

    <header>
        <div class="wpr-templates-kit-logo">
            <div><img src="<?php echo !empty(get_option('wpr_wl_plugin_logo')) ? esc_url(wp_get_attachment_image_src(get_option('wpr_wl_plugin_logo'), 'full')[0]) : esc_url(WPR_ADDONS_ASSETS_URL .'img/logo-40x40.png'); ?>"></div>
            <div class="back-btn"><?php printf( esc_html__('%s Back to Library', 'wpr-addons'), '<span class="dashicons dashicons-arrow-left-alt2"></span>'); ?></div>
        </div>

        <div class="wpr-templates-kit-search">
            <input type="text" autocomplete="off" placeholder="<?php esc_html_e('Search Templates Kit...', 'wpr-addons'); ?>">
            <span class="dashicons dashicons-search"></span>
        </div>

        <div class="wpr-templates-kit-price-filter">
            <span data-price="mixed"><?php esc_html_e('Price: Mixed', 'wpr-addons'); ?></span>
            <span class="dashicons dashicons-arrow-down-alt2"></span>
            <ul>
                <li><?php esc_html_e('Mixed', 'wpr-addons'); ?></li>
                <li><?php esc_html_e('Free', 'wpr-addons'); ?></li>
                <li><?php esc_html_e('Premium', 'wpr-addons'); ?></li>
            </ul>
        </div>

        <div class="wpr-templates-kit-filters">
            <div>Filter: All</div>
            <ul>
                <li data-filter="all">Blog</li>
                <li data-filter="blog">Blog</li>
                <li data-filter="business">Business</li>
                <li data-filter="ecommerce">eCommerce</li>
                <li data-filter="beauty">Beauty</li>
            </ul>
        </div>
    </header>

    <div class="wpr-templates-kit-page-title">
        <h1><?php esc_html_e('Royal Elementor Templates Kit', 'wpr-addons'); ?></h1>
        <p><?php esc_html_e('Import any Templates Kit with just a Single click', 'wpr-addons'); ?></p>
    </div>

    <div class="wpr-templates-kit-grid main-grid" data-theme-status="<?php echo esc_attr(get_theme_status()); ?>">
        <?php
            $kits = WPR_Templates_Data::get_available_kits();
            $sorted_kits = [];

            foreach ($kits as $slug => $kit) {
                foreach ($kit as $version => $data ) {
                    $sorted_kits[$slug .'-'. $version] = $data;
                }
            }

            // Sort by Priority
            uasort($sorted_kits, function ($item1, $item2) {
                if ($item1['priority'] == $item2['priority']) return 0;
                return $item1['priority'] < $item2['priority'] ? -1 : 1;
            });

            // Loop
            foreach ($sorted_kits as $kit_id => $data) {
                echo '<div class="grid-item" data-kit-id="'. esc_attr($kit_id) .'" data-tags="'. esc_attr($data['tags']) .'" data-plugins="'. esc_attr($data['plugins']) .'" data-pages="'. esc_attr($data['pages']) .'" data-price="'. esc_attr($data['price']) .'">';
                    echo '<div class="image-wrap">';
                        echo '<img src="'. esc_url('https://royal-elementor-addons.com/library/templates-kit/'. $kit_id .'/home.jpg') .'">';
                        echo '<div class="image-overlay"><span class="dashicons dashicons-search"></span></div>';
                    echo '</div>';
                    echo '<footer>';
                        echo '<h3>'. esc_html($data['name']) .'</h3>';
                        echo esc_html($data['theme-builder']) ? '<span>'. esc_html__( 'Theme Builder', 'wpr-addons' ) .'</span>' : '';
                    echo '</footer>';
                echo '</div>';
            }

        ?>

    </div>

    <div class="wpr-templates-kit-single">
        <div class="wpr-templates-kit-grid single-grid"></div>

        <footer class="action-buttons-wrap">
            <a href="https://royal-elementor-addons.com/" class="preview-demo button" target="_blank"><?php esc_html_e('Preview Demo', 'wpr-addons'); ?> <span class="dashicons dashicons-external"></span></a>

            <div class="import-template-buttons">
                <?php
                    echo '<button class="import-kit button">'. esc_html__('Import Templates Kit', 'wpr-addons') .' <span class="dashicons dashicons-download"></span></button>';
                    echo '<a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-templates-upgrade-pro#purchasepro" class="get-access button" target="_blank">'. esc_html__('Get Access', 'wpr-addons') .' <span class="dashicons dashicons-external"></span></a>';
                ?>
                <button class="import-template button"><?php printf( esc_html__( 'Import %s Template', 'wpr-addons' ), '<strong></strong>' ); ?></button>
                
            </div>
        </footer>
    </div>

    <div class="wpr-import-kit-popup-wrap">
        <div class="overlay"></div>
        <div class="wpr-import-kit-popup">
            <header>
                <h3><?php esc_html_e('Template Kit is being imported', 'wpr-addons'); ?><span>.</span></h3>
                <span class="dashicons dashicons-no-alt close-btn"></span>
            </header>
            <div class="content">
                <p><?php esc_html_e('The import process can take a few seconds depending on the size of the kit you are importing and speed of the connection.', 'wpr-addons'); ?></p>
                <p><?php esc_html_e('Please do NOT close this browser window until import is completed.', 'wpr-addons'); ?></p>

                <div class="progress-wrap">
                    <div class="progress-bar"></div>
                    <strong></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="wpr-templates-kit-not-found">
        <img src="<?php echo esc_url(WPR_ADDONS_ASSETS_URL .'img/not-found.png'); ?>">
        <h1><?php esc_html_e('No Search Results Found.', 'wpr-addons'); ?></h1>
        <p><?php esc_html_e('Cant find a Templates Kit you are looking for?', 'wpr-addons'); ?></p>
        <a href="https://royal-elementor-addons.com/library/request-new-kit-red.html" target="_blank"><?php esc_html_e('Request Templates Kit.', 'wpr-addons'); ?></a>
    </div>

</div>


<?php

} // End wpr_addons_templates_kit_page()

/**
** Get Theme Status
*/
function get_theme_status() {
    $theme = wp_get_theme();

    // Theme installed and activate.
    if ( 'Royal Elementor Kit' === $theme->name || 'Royal Elementor Kit' === $theme->parent_theme ) {
        return 'req-theme-active';
    }

    // Theme installed but not activate.
    foreach ( (array) wp_get_themes() as $theme_dir => $theme ) {
        if ( 'Royal Elementor Kit' === $theme->name || 'Royal Elementor Kit' === $theme->parent_theme ) {
            return 'req-theme-inactive';
        }
    }

    return 'req-theme-not-installed';
}

/**
** Install/Activate Required Theme
*/
function wpr_activate_reuired_theme() {
    // Get Current Theme
    $theme = get_option('stylesheet');

    // Activate Royal Elementor Kit Theme
    if ( 'ashe-pro-premium' !== $theme && 'bard-pro-premium' !== $theme
        && 'vayne-pro-premium' !== $theme && 'kayn-pro-premium' !== $theme ) {
        switch_theme( 'royal-elementor-kit' );
        set_transient( 'royal-elementor-kit_activation_notice', true );
    }

    // TODO: maybe return back  - 'ashe' !== $theme && 'bard' !== $theme && 
}

/**
** Install/Activate Required Plugins
*/
function wpr_install_reuired_plugins() {
    // Get currently active plugins
    $active_plugins = (array) get_option( 'active_plugins', array() );

    // Add Required Plugins
    if ( isset($_POST['plugin']) ) {
        if ( 'contact-form-7' == $_POST['plugin'] ) {
            array_push( $active_plugins, 'contact-form-7/wp-contact-form-7.php' );
        } elseif ( 'media-library-assistant' == $_POST['plugin'] ) {
            array_push( $active_plugins, 'media-library-assistant/index.php' );
        }
    }

    // Set Active Plugins
    update_option( 'active_plugins', $active_plugins ); 

    // Get Current Theme
    $theme = get_option('stylesheet');

    // Activate Royal Elementor Kit Theme
    if ( 'ashe-pro-premium' !== $theme && 'bard-pro-premium' !== $theme
        && 'vayne-pro-premium' !== $theme && 'kayn-pro-premium' !== $theme ) {
        switch_theme( 'royal-elementor-kit' );
        set_transient( 'royal-elementor-kit_activation_notice', true );
    }

    // TODO: maybe return back  - 'ashe' !== $theme && 'bard' !== $theme && 
    
}

/**
** Import Template Kit
*/
function wpr_import_templates_kit() {

    // Temp Define Importers
    if ( ! defined('WP_LOAD_IMPORTERS') ) {
        define('WP_LOAD_IMPORTERS', true);
    }

    // Include if Class Does NOT Exist
    if ( ! class_exists( 'WP_Import' ) ) {
        $class_wp_importer = WPR_ADDONS_PATH .'admin/import/class-wordpress-importer.php';
        if ( file_exists( $class_wp_importer ) ) {
            require $class_wp_importer;
        }
    }

    if ( class_exists( 'WP_Import' ) ) {
        $kit = isset($_POST['wpr_templates_kit']) ? sanitize_file_name(wp_unslash($_POST['wpr_templates_kit'])) : '';
        $file = isset($_POST['wpr_templates_kit_single']) ? sanitize_file_name(wp_unslash($_POST['wpr_templates_kit_single'])) : '';

        // Tmp
        update_option( 'wpr-import-kit-id', $kit );

        // Download Import File
        $local_file_path = download_template( $kit, $file );

        // Prepare for Import
        $wp_import = new WP_Import( $local_file_path, ['fetch_attachments' => true] );

        // Import
        ob_start();
            $wp_import->run();
        ob_end_clean();

        // Delete Import File
        unlink( $local_file_path );

        // Send to JS
        echo esc_html(serialize( $wp_import ));
    }

}

/**
** Download Template
*/
function download_template( $kit, $file ) {
    $file = ! $file ? 'main' : $file;

    // Avoid Cache
    $randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

    // Remote and Local Files
    $remote_file_url = 'https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/main.xml?='. $randomNum;
    $local_file_path = WPR_ADDONS_PATH .'admin/import/tmp.xml';

    // No Limit for Execution
    set_time_limit(0);

    // Copy File From Server
    copy( $remote_file_url, $local_file_path );

    return $local_file_path;
}

/**
** Import Elementor Site Settings
*/
function import_elementor_site_settings( $kit ) {
    // Avoid Cache
    // $randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

    // Get Remote File
    $site_settings = @file_get_contents('https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/site-settings.json');

    if ( false !== $site_settings ) {
        $site_settings = json_decode($site_settings, true);

        if ( ! empty($site_settings['settings']) ) {
            $default_kit = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( get_option( 'elementor_active_kit' ) );

            $kit_settings = $default_kit->get_settings();
            $new_settings = $site_settings['settings'];
            $settings = array_merge($kit_settings, $new_settings);

            $default_kit->save( [ 'settings' => $settings ] );
        }
    }
}

/**
** Setup WPR Templates
*/
function setup_wpr_templates( $kit ) {
    $kit = isset($kit) ? sanitize_text_field(wp_unslash($kit)) : '';

    // Check if kit has Theme Builder templates
    $kit_name = substr($kit, 0, strripos($kit, '-v'));
    $kit_version = substr($kit, (strripos($kit, '-v') + 1), strlen($kit));
    $get_available_kits = WPR_Templates_Data::get_available_kits();
    $has_theme_builder = $get_available_kits[$kit_name][$kit_version]['theme-builder'];

    // Set Home & Blog Pages
    $home_page = get_page_by_path('home-'. $kit);
    $blog_page = get_page_by_path('blog-'. $kit);

    if ( $home_page ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home_page->ID );
        
        if ( $blog_page ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
    }

    // Set Headers and Footers
    update_option('wpr_header_conditions', '{"user-header-'. $kit .'-header":["global"]}');
    update_post_meta( Utilities::get_template_id('user-header-'. $kit), 'wpr_header_show_on_canvas', 'true' );
    update_option('wpr_footer_conditions', '{"user-footer-'. $kit .'-footer":["global"]}');
    update_post_meta( Utilities::get_template_id('user-footer-'. $kit), 'wpr_footer_show_on_canvas', 'true' );

    // Theme Builder
    if ( $has_theme_builder ) {
        update_option('wpr_archive_conditions', '{"user-archive-'. $kit .'-blog":["archive/posts"],"user-archive-'. $kit .'-author":["archive/author"],"user-archive-'. $kit .'-date":["archive/date"],"user-archive-'. $kit .'-category-tag":["archive/categories/all","archive/tags/all"],"user-archive-'. $kit .'-search":["archive/search"]}');
        update_option('wpr_single_conditions', '{"user-single-'. $kit .'-404":["single/page_404"],"user-single-'. $kit .'-post":["single/posts/all"],"user-single-'. $kit .'-page":["single/pages/all"]}');
    }

    // Set Popup
    update_option('wpr_popup_conditions', '{"user-popup-'. $kit .'-popup":["global"]}'); 
}

/**
** Fix Elementor Images
*/
function wpr_fix_elementor_images() {
    $args = array(
        'post_type' => ['wpr_templates', 'page'],
        'posts_per_page' => '-1',
        'meta_key' => '_elementor_version'
    );
    $elementor_pages = new WP_Query ( $args );

    // Check that we have query results.
    if ( $elementor_pages->have_posts() ) {
     
        // Start looping over the query results.
        while ( $elementor_pages->have_posts() ) {

            $elementor_pages->the_post();

            // Replace Demo with Current
            $site_url = get_site_url();
            $site_url = str_replace( '/', '\/', $site_url );
            $demo_site_url = 'https://demosites.royal-elementor-addons.com/'. get_option('wpr-import-kit-id');
            $demo_site_url = str_replace( '/', '\/', $demo_site_url );

            // Elementor Data
            $data = get_post_meta( get_the_ID(), '_elementor_data', true );

            if ( ! empty( $data ) ) {
                $data = preg_replace('/\\\{1}\/sites\\\{1}\/\d+/', '', $data);
                $data = str_replace( $demo_site_url, $site_url, $data );
                $data = json_decode( $data, true );
            }

            update_metadata( 'post', get_the_ID(), '_elementor_data', $data );

            // Elementor Page Settings
            $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
            $page_settings = json_encode($page_settings);

            if ( ! empty( $page_settings ) ) {
                $page_settings = preg_replace('/\\\{1}\/sites\\\{1}\/\d+/', '', $page_settings);
                $page_settings = str_replace( $demo_site_url, $site_url, $page_settings );
                $page_settings = json_decode( $page_settings, true );
            }

            update_metadata( 'post', get_the_ID(), '_elementor_page_settings', $page_settings );

        }
     
    }

    // Clear Elementor Cache
    Plugin::$instance->files_manager->clear_cache();
}

/**
** Fix Contact Form 7
*/
function fix_contact_form_7() {
    if ( class_exists('WPCF7_ContactForm') ) {
        $new_contact_form = WPCF7_ContactForm::get_template(
            array(
                'title' =>
                    /* translators: title of your first contact form. %d: number fixed to '1' */
                    sprintf( __( 'Contact form %d', 'contact-form-7' ), 1 ),
            )
        );

        // Get CF7s
        $contact_forms = get_posts(['post_type'=>'wpcf7_contact_form']);

        // Add new CF7
        if ( empty($contact_forms) ) {
            $new_contact_form->save();
        }
    }
}

/**
** Final Settings Setup
*/
function wpr_final_settings_setup() {
    $kit = !empty(get_option('wpr-import-kit-id')) ? esc_html(get_option('wpr-import-kit-id')) : '';

    // Elementor Site Settings
    import_elementor_site_settings($kit);

    // Setup WPR Templates
    setup_wpr_templates($kit);

    // Fix Elementor Images
    wpr_fix_elementor_images();

    // Fix Contact Form 7
    fix_contact_form_7();

    // Track Kit
    wpr_track_imported_kit( $kit );

    // Clear DB
    delete_option('wpr-import-kit-id');

    // Delete Hello World Post
    $post = get_page_by_path('hello-world', OBJECT, 'post');
    if ( $post ) {
        wp_delete_post($post->ID,true);
    }
}

/**
 *  Allow SVG Import - Add Mime Types
 */
function wpr_svgs_upload_mimes( $mimes = array() ) {

    // allow SVG file upload
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    // allow JSON file upload
    $mimes['json'] = 'text/plain';

    return $mimes;

}
add_filter( 'upload_mimes', 'wpr_svgs_upload_mimes', 99 );

/**
 * Check Mime Types
 */
function wpr_svgs_upload_check( $checked, $file, $filename, $mimes ) {

    if ( ! $checked['type'] ) {

        $check_filetype     = wp_check_filetype( $filename, $mimes );
        $ext                = $check_filetype['ext'];
        $type               = $check_filetype['type'];
        $proper_filename    = $filename;

        if ( $type && 0 === strpos( $type, 'image/' ) && $ext !== 'svg' ) {
            $ext = $type = false;
        }

        $checked = compact( 'ext','type','proper_filename' );
    }

    return $checked;

}
add_filter( 'wp_check_filetype_and_ext', 'wpr_svgs_upload_check', 10, 4 );

/**
 * Mime Check fix for WP 4.7.1 / 4.7.2
 *
 * Fixes uploads for these 2 version of WordPress.
 * Issue was fixed in 4.7.3 core.
 */
function wpr_svgs_allow_svg_upload( $data, $file, $filename, $mimes ) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' || $wp_version !== '4.7.2' ) {
        return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    return [
        'ext'               => $filetype['ext'],
        'type'              => $filetype['type'],
        'proper_filename'   => $data['proper_filename']
    ];

}
add_filter( 'wp_check_filetype_and_ext', 'wpr_svgs_allow_svg_upload', 10, 4 );

/**
** Search Query Results
*/
function wpr_search_query_results() {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }

    $search_query = isset($_POST['search_query']) ? sanitize_text_field(wp_unslash($_POST['search_query'])) : '';

    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/templates-kit-search/data', [
        'body' => [
            'search_query' => $search_query
        ]
    ] );
}

/**
** Search Query Results
*/
function wpr_track_imported_kit( $kit ) {
    // Freemius OptIn
    if ( ! ( wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed()  || wpr_fs()->is_pending_activation() ) ) {
        return;
    }
    
    wp_remote_post( 'http://reastats.kinsta.cloud/wp-json/templates-kit-import/data', [
        'body' => [
            'imported_kit' => $kit
        ]
    ] );
}