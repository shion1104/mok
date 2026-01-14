<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

/**
 * Class ElementorSimpleMembershipWidget
 */
class ElementorSimpleMembershipWidget extends Widget_Base
{
    /**
     * @var array
     */
    public $templates = [];

    /**
     * @var array
     */
    public $memberships = [];

    /**
     * @return string
     */
    public function get_name(): string
    {
        return 'swpm-elementor-template-protection';
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return __('SWPM - Elementor Template Protection', 'swpm-elementor-template-protection');
    }

    /**
     * @return string
     */
    public function get_icon(): string
    {
        return 'fa fa-code';
    }

    /**
     * @return array
     */
    public function get_categories(): array
    {
        return apply_filters('svilapp/elementor/simple-membership/categories', [
            'general'
        ]);
    }

    /**
     * @return void
     */
    protected function _register_controls(): void
    {
        global $wpdb;

        do_action('svilapp/elementor/simple-membership/widget/register_controls/before', $this);

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'swpm-elementor-template-protection'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $library = get_posts([
            'post_type' => 'elementor_library',
            'numberposts' => -1
        ]);

        foreach ($library as $k => $value) {
            if (apply_filters('svilapp/elementor/simple-membership/widget/register_controls/template_type_condition', has_term('section', 'elementor_library_type', $value), $value)) {
                $this->templates[$value->ID] = sprintf("%s (%d)", $value->post_title, $value->ID);
            }
        }

        $this->add_control(
            'template',
            [
                'label' => __('Template', 'swpm-elementor-template-protection'),
                'type' => Controls_Manager::SELECT2,
                'options' => apply_filters('svilapp/elementor/simple-membership/template_results', $this->templates)
            ]
        );

        foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE  id !=1 ") as $item) {
            $this->memberships[$item->id] = $item->alias;
        }

        $this->add_control(
            'membership',
            [
                'label' => __('Membership', 'swpm-elementor-template-protection'),
                'type' => Controls_Manager::SELECT,
                'options' => apply_filters('svilapp/elementor/simple-membership/membership_results', $this->memberships)
            ]
        );

        $this->add_control(
            'try_pro',
            [
                'label' => __('Try PRO Version', 'elementor-simple-membership'),
                'type' => Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<a href="https://svilapp.it" target="_blank">' . __('Try PRO Version', 'swpm-elementor-template-protection') . '</a>',
                'separator' => 'before',
            ]
        );

        do_action('svilapp/elementor/simple-membership/widget/register_controls/content', $this);

        $this->end_controls_section();

        do_action('svilapp/elementor/simple-membership/widget/register_controls/before', $this);
    }

    /**
     * @return void
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        ob_start();
        echo '<div class="sm-elementor-widget">';
        echo do_shortcode(sprintf('[elementor-template id=%d]', $settings['template']));
        echo '</div>';

        $content = esc_html(apply_filters('svilapp/elementor/simple-membership/widget/render/content', ob_get_clean()));

        if (is_admin()) {
            echo $content;
        }

        if (apply_filters('svilapp/elementor/simple-membership/widget/render/condition', SwpmMemberUtils::get_logged_in_members_level() === $settings['membership'], $this)) {
            echo $content;
        }
    }
}
