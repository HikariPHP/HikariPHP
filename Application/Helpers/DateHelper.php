<?php
/**
 * Created by PhpStorm.
 * User: costin.urse
 * Date: 2014-10-29
 * Time: 10:31 AM
 */

namespace Application\Helpers;

class DateHelper {

	public static function formatDate( $string, $format = 'Y-m-d H:i:s' ) {
		$date = new \DateTime($string);
		if (CONFIG_USE_VISITOR_TIMEZONE ==  true) {
			$date->setTimezone( new \DateTimeZone( date_default_timezone_get() ) );
		}
		return  $date->format($format);
	}

	public static function dateToTimestamp( $string ) {
		$date = new \DateTime($string);
		if (CONFIG_USE_VISITOR_TIMEZONE ==  true) {
			$date->setTimezone( new \DateTimeZone( date_default_timezone_get() ) );
		}
		return  $date->getTimestamp();
	}

	public static function timestampToDate( $string, $format = 'Y-m-d H:i:s' ) {
		$date = new \DateTime();
		$date->setTimestamp($string);
		if (CONFIG_USE_VISITOR_TIMEZONE ==  true) {
			$date->setTimezone( new \DateTimeZone( date_default_timezone_get() ) );
		}
		return  $date->format($format);
	}

}