<?php
/**
 * Class Tribe__Events__Integrations__WPML__WPML
 *
 * Handles anything relating to The Events Calendar and WPML integration
 *
 * This class is meant to be an entry point hooking specialized classes and not
 * a logic hub per se.
 */
class Tribe__Events__Integrations__WPML__WPML {

	/**
	 * @var Tribe__Events__Integrations__WPML__WPML
	 */
	protected static $instance;

	/**
	 * The class singleton constructor.
	 *
	 * @return Tribe__Events__Integrations__WPML__WPML
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hooks into The Events Calendar and WPML hooks to make the plugins play nice.
	 */
	public function hook() {
		// the WPML API is not included by default
		require_once ICL_PLUGIN_PATH . '/inc/wpml-api.php';

		$this->hook_actions();
		$this->hook_filters();
	}

	protected function hook_actions() {
		$this->setup_cache_expiration_triggers();
		$defaults = Tribe__Events__Integrations__WPML__Defaults::instance();
		if ( ! $defaults->has_set_defaults() ) {
			add_action( 'wpml_parse_config_file', array( $defaults, 'setup_config_file' ) );
		}
		$linked_posts = Tribe__Events__Integrations__WPML__Linked_Posts::instance();
		add_action( 'wpml_translation_update', array( $linked_posts, 'maybe_translate_linked_posts' ), 10, 1 );
	}

	protected function hook_filters() {
		$filters = Tribe__Events__Integrations__WPML__Filters::instance();
		add_filter( 'tribe_events_rewrite_i18n_slugs_raw', array( $filters, 'filter_tribe_events_rewrite_i18n_slugs_raw' ), 10, 3 );

		$linked_posts = Tribe__Events__Integrations__WPML__Linked_Posts::instance();
		add_filter( 'tribe_events_linked_posts_query', array( $linked_posts, 'filter_tribe_events_linked_posts_query' ), 10, 2 );
		add_filter( 'tribe_events_linked_post_create', array( $linked_posts, 'filter_tribe_events_linked_post_create' ), 20, 5 );

		$rewrites = Tribe__Events__Integrations__WPML__Rewrites::instance();
		add_filter( 'rewrite_rules_array', array( $rewrites, 'filter_rewrite_rules_array' ), 20, 1 );
		add_filter( 'tribe_events_rewrite_i18n_slugs_raw', array( $rewrites, 'filter_tax_base_slug' ), 10, 2 );

		$permalinks = Tribe__Events__Integrations__WPML__Permalinks::instance();
		add_filter( 'post_type_link', array( $permalinks, 'filter_post_type_link' ), 20, 2 );

		$language_switcher = Tribe__Events__Integrations__WPML__Language_Switcher::instance();
		add_filter( 'icl_ls_languages', array( $language_switcher, 'filter_icl_ls_languages' ), 5 );

		$meta = Tribe__Events__Integrations__WPML__Meta::instance();
		add_filter( 'get_post_metadata', array( $meta, 'translate_post_id' ), 10, 4);

		// Disable month view caching when WPML is activated for now, until we
		// fully implement multilingual support for the month view cache.
		add_filter( 'tribe_events_enable_month_view_cache', '__return_false' );

		if ( ! is_admin() ) {
			$category_translation = Tribe__Events__Integrations__WPML__Category_Translation::instance();
			add_filter( 'tribe_events_category_slug', array( $category_translation, 'filter_tribe_events_category_slug' ), 20, 2 );
		}
	}

	protected function setup_cache_expiration_triggers() {
		$cache_listener = Tribe__Cache_Listener::instance();
		add_action( 'wpml_cache_clear', array( $cache_listener, 'wpml_updates' ) );
		add_action( 'wpml_activated', array( $cache_listener, 'wpml_updates' ) );
		add_action( 'wpml_deactivated', array( $cache_listener, 'wpml_updates' ) );
		add_action( 'update_option_icl_sitepress_settings', array( $cache_listener, 'wpml_updates' ) );
		add_action( 'tribe_settings_save', array( $cache_listener, 'wpml_updates' ) );
	}
}
