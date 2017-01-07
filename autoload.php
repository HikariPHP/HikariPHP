<?php

/**
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-0)
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md for more informations.
 * Code inspired from the SplClassLoader RFC
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */
spl_autoload_register( function ( $className ) {
	$className = ltrim( $className, '\\' );

	$fileName  = '';
	$namespace = '';
	if ( $lastNsPos = strrpos( $className, '\\' ) ) {
		$namespace = substr( $className, 0, $lastNsPos );
		$className = substr( $className, $lastNsPos + 1 );
		$fileName  = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace ) . DIRECTORY_SEPARATOR;
	}

	$fileAppName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
	$fileVendorName = __DIR__ . DIRECTORY_SEPARATOR . 'Vendor/' . $fileName . $className . '.php';

	//echo '<br/>';
	if ( is_file( $fileAppName ) ) {
		require $fileAppName;
		//echo $fileAppName.'<br>';

		return true;
	} elseif ( is_file( $fileVendorName ) ) {
		require $fileVendorName;
		//echo $fileVendorName.'<br>';

		return true;
	}
	return false;
} );