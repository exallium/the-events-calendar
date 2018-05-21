<?php
/**
 * @internal This class may be removed or changed without notice
 */
class Tribe__Events__Admin__Notice__Marketing {
	/**
	 * Register marketing notices.
	 *
	 * @since TBD
	 */
	public function hook() {
		tribe_notice(
			'tribe-events-upcoming-survey',
			array( $this, 'notice' ),
			array(
				'dismiss' => 1,
				'type'    => 'info',
				'wrap'    => 'p',
			),
			array( $this, 'should_display' )
		);
	}

	/**
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_display() {
		return tribe( 'admin.helpers' )->is_screen()
			&& date_create()->format( 'Y-m-d' ) < '2018-06-08';
	}

	/**
	 * HTML for the notice for sites using UTC Timezones.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function notice() {
		$link = sprintf(
			'<a href="%1$s" target="_blank">%2$s</a>',
			esc_url( 'https://m.tri.be/1a3l' ),
			esc_html_x( 'take the survey now', '2018 user survey', 'the-events-calendar' )
		);

		return sprintf(
			_x( '<strong>The Events Calendar Annual Survey:</strong> share your feedback with our team—%1$s!', '2018 user survey', 'the-events-calendar' ),
			$link
		);
	}
}
