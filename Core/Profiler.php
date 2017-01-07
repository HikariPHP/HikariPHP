<?php
namespace Core;

/**
 * Class Profiler
 */
class Profiler extends FullProfiler {
	public function __tostring() {
		return "<pre>" . $this->getResults() . "</pre>";
	}

	public function display( $hideAsHtml = 0 ) {
		if ( $hideAsHtml ) {
			echo "\n" . '<!--' . "\n";
		}
		echo $this;
		if ( $hideAsHtml ) {
			echo "\n" . '-->' . "\n";
		}
	}
}

class Benchmark {
	var $startSec;
	var $startUsec;

	var $stopSec;
	var $stopUsec;

	function start() {
		list( $this->startUsec, $this->startSec ) = explode( " ", microtime() );
	}

	function stop() {
		list( $this->stopUsec, $this->stopSec ) = explode( " ", microtime() );
	}

	// microseconds variable looks like 0.123456
	// strings are formated as in 1,234.567
	// for getSec, getMsec, getUsec. getUsecNF always returns non-formated integer
	function getSec() {
		return number_format( ( $this->stopSec - $this->startSec ) + ( $this->stopUsec - $this->startUsec ), 3 );
	}

	function getMsec() {
		return number_format( ( $this->stopSec - $this->startSec ) * 1000 + ( $this->stopUsec - $this->startUsec ) * 1000, 3 );
	}

	function getUsec() {
		return number_format( ( $this->stopSec - $this->startSec ) * 1000000 + ( $this->stopUsec - $this->startUsec ) * 1000000 );
	}

	function getUsecNF() {
		return round( ( $this->stopSec - $this->startSec ) * 1000000 + ( $this->stopUsec - $this->startUsec ) * 1000000 );
	}

	function getTime() {
		if ( $this->getSec() >= 1 ) {
			return $this->getSec() . " sec";
		}
		if ( $this->getMsec() >= 1 ) {
			return $this->getMsec() . " msec";
		}
		if ( $this->getUsec() >= 1 ) {
			return $this->getUsec() . " usec";
		}

		return 'strange: ss:' . $this->startSec . ' es:' . $this->stopSec . ' su:' . $this->startUsec . ' eu:' . $this->stopUsec;
	}

	function getTimeValue() {
		if ( $this->getSec() >= 1 ) {
			return $this->getSec();
		}
		if ( $this->getMsec() >= 1 ) {
			return $this->getMsec();
		}
		if ( $this->getUsec() >= 1 ) {
			return $this->getUsec();
		}
	}

	function getRange() {
		if ( $this->getSec() >= 1 ) {
			return " sec";
		}
		if ( $this->getMsec() >= 1 ) {
			return " msec";
		}
		if ( $this->getUsec() >= 1 ) {
			return " usec";
		}

		return 'strange: ss:' . $this->startSec . ' es:' . $this->stopSec . ' su:' . $this->startUsec . ' eu:' . $this->stopUsec;
	}
}

/*

usage:
	you can just call profile('name') successively to profile parts of the code
	you can call stop() anytime when profiling to stop the current profile
*/

class FullProfiler {
	/*
	echo $dat["ru_nswap"];        // number of swaps
	echo $dat["ru_majflt"];        // number of page faults
	echo $dat["ru_utime.tv_sec"];  // user time used (seconds)
	echo $dat["ru_utime.tv_usec"]; // user time used (microseconds)
	*/

	var $benchmarks;
	var $memUsageStart;
	var $memUsageStop;
	var $rusageStart;
	var $rusageStop;
	var $currentBenchmark;
	var $currentKey;

	protected $intBenchMaxKeyLength = 0;

	function SpeedProfiler() {
		$this->benchmarks = array();
	}

	function profile( $key ) {

		if ( is_object( $this->currentBenchmark ) ) {
			$this->currentBenchmark->stop();
			$this->memUsageStop[ $this->currentKey ] = memory_get_usage();
			$this->rusageStop[ $this->currentKey ]   = getrusage();
		}
		$this->intBenchMaxKeyLength  = ( strlen( $key ) > $this->intBenchMaxKeyLength ? strlen( $key ) : $this->intBenchMaxKeyLength );
		$this->currentKey            = $key;
		$this->benchmarks[ $key ]    = new Benchmark();
		$this->memUsageStart[ $key ] = memory_get_usage();
		$this->rUsageStart[ $key ]   = getrusage();
		$this->currentBenchmark      = &$this->benchmarks[ $key ];
		$this->currentBenchmark->start();
	}

	function stop() {
		$this->currentBenchmark->stop();
		$this->memUsageStop[ $this->currentKey ] = memory_get_usage();
		$this->rusageStop[ $this->currentKey ]   = getrusage();
	}

	function getResults() {
		$output  = "\n\nProfiler results        time          percent    memalloc    memtotal\n---------------------------------------------------------------------\n";
		$sumusec = 0;
		foreach ( $this->benchmarks as $benchName => $bench ) {
			$sumusec += $bench->getUsecNF();
		}

		$intMinIndent = 24;

		if ( $intMinIndent <= $this->intBenchMaxKeyLength ) {
			$intMinIndent = $this->intBenchMaxKeyLength + 1;
		}

		foreach ( $this->benchmarks as $benchName => $bench ) {
			$memdiffk  = number_format( ( $this->memUsageStop[ $benchName ] - $this->memUsageStart[ $benchName ] ) / 1024, 2 );
			$memafterk = number_format( $this->memUsageStop[ $benchName ] / 1024, 2 );
			$usec      = $bench->getUsecNF();
			$uftime    = $bench->getTime();
			$percent   = number_format( ( $usec / $sumusec ) * 100, 2 );
			$output .= $benchName . str_repeat( ' ', $intMinIndent - strlen( $benchName ) ) . $uftime . str_repeat( ' ', 18 - strlen( $uftime ) - strlen( $percent ) ) . "  " . $percent . "% " . str_repeat( ' ', 10 - strlen( $memdiffk ) ) . $memdiffk . "k" . str_repeat( ' ', 11 - strlen( $memafterk ) ) . $memafterk . "k\n";
		}
		$output .= "---------------------------------------------------------------------\n";
		$totalBench            = new Benchmark();
		$keys                  = array_keys( $this->benchmarks );
		$totalBench->startSec  = $this->benchmarks[ ( $keys[0] ) ]->startSec;
		$totalBench->startUsec = $this->benchmarks[ ( $keys[0] ) ]->startUsec;
		$totalBench->stopSec   = $this->benchmarks[ ( $keys[ count( $keys ) - 1 ] ) ]->stopSec;
		$totalBench->stopUsec  = $this->benchmarks[ ( $keys[ count( $keys ) - 1 ] ) ]->stopUsec;
		$output .= "Total:                  " . $totalBench->getTime() . "\n\n";

		return $output;
	}
}