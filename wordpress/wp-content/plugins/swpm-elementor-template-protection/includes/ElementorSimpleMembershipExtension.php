<?php

use Elementor\Elements_Manager;
use Elementor\Plugin;

/**
 * Class ElementorSimpleMembershipExtension
 */
final class ElementorSimpleMembershipExtension
{

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    public const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    public const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    public const MINIMUM_PHP_VERSION = '7.4';

    /**
     * @var null|ElementorSimpleMembershipExtension
     */
    private static $_instance = null;

    /**
     * @return ElementorSimpleMembershipExtension
     */
    public static function instance(): ElementorSimpleMembershipExtension
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'onPluginsLoaded']);
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n(): void
    {
        load_plugin_textdomain('swpm-elementor-template-protection');
    }

    /**
     *
     */
    public function includes(): void
    {
        if (! class_exists('SvilAppMembershipUtils')) {
            require_once __DIR__ . '/SvilAppMembershipUtils.php';
        }
    }

    /**
     * On Plugins Loaded
     *
     * Checks if Elementor has loaded, and performs some compatibility checks.
     * If All checks pass, inits the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function onPluginsLoaded(): void
    {
        if  ($this->isCompatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    /**
     * Compatibility Checks
     *
     * Checks if the installed version of Elementor meets the plugin's minimum requirement.
     * Checks if the installed PHP version meets the plugin's minimum requirement.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function isCompatible(): bool
    {
        // Check if Elementor installed and activated
        if (! did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'adminNoticeMissingMainPlugin']);
            return false;
        }

        // Check for required Elementor version
        if (! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'adminNoticeMinimumElementorVersion']);

            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'adminNoticeMinimumPhpVersion']);

            return false;
        }

        // Check plugin class
        if (! class_exists('SimpleWpMembership')) {
            add_action('admin_notices', [$this, 'adminNoticeSwpmFbUtilsCustomFields']);

            return false;
        }

        return true;

    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init(): void
    {
        $this->includes();

        $this->i18n();

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'initWidgets']);
        add_action('elementor/controls/controls_registered', [$this, 'initControls']);
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function initWidgets(): void
    {
        // Include Widget files
        require_once __DIR__ . '/widgets/ElementorSimpleMembershipWidget.php';

        // Register widget
        Plugin::instance()->widgets_manager->register_widget_type(new ElementorSimpleMembershipWidget());
    }

    /**
     * Init Controls
     *
     * Include controls files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function initControls(): void
    {
        // Include Control files
        //require_once( __DIR__ . '/controls/test-control.php' );

        // Register control
        //Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control());
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function adminNoticeMissingMainPlugin(): void
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'swpm-elementor-template-protection'),
            '<strong>' . esc_html__('SWPM - Elementor Template Protection Extension', 'swpm-elementor-template-protection') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'swpm-elementor-template-protection') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function adminNoticeMinimumElementorVersion(): void
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'swpm-elementor-template-protection'),
            '<strong>' . esc_html__('SWPM - Elementor Template Protection Extension', 'swpm-elementor-template-protection') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'swpm-elementor-template-protection') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function adminNoticeMinimumPhpVersion(): void
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'swpm-elementor-template-protection'),
            '<strong>' . esc_html__('SWPM - Elementor Template Protection Extension', 'swpm-elementor-template-protection') . '</strong>',
            '<strong>' . esc_html__('PHP', 'swpm-elementor-template-protection') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function adminNoticeSwpmFbUtilsCustomFields(): void
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required Plugin */
            esc_html__('"%1$s" requires "%2$s" plugin.', 'swpm-elementor-template-protection'),
            '<strong>' . esc_html__('SWPM - Elementor Template Protection Extension', 'swpm-elementor-template-protection') . '</strong>',
            '<strong>' . esc_html__('Simple Membership', 'swpm-elementor-template-protection') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

ElementorSimpleMembershipExtension::instance();
