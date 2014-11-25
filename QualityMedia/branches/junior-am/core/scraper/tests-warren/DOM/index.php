<?php
set_time_limit ( 0 );

define ( '_NL', '<br />' );

$url = 'http://www.yelp.com/biz/';
define ('D_UA', 'Googlebot/2.1 (http://www.googlebot.com/bot.html)');

extract($_GET);

$keyword = (isset ( $keyword ) && strlen ( $keyword ) > 0) ? $keyword : exit ();
$type = (isset ( $type ) && strlen ( $type ) > 0) ? $type : null;
$out = (isset ( $out ) && strlen ( $out ) > 0) ? $out : null;

$url .= $keyword;

/**
 * useSSL
 *
 * @param string $url        	
 * @return boolean
 */
function useSSL($url) {
	if (substr ( $url, 0, 8 ) == 'https://')
		return true;
	return false;
}

/**
 * fetchContent
 *
 * @param string $url        	
 * @return mixed
 */
function fetchContent($url) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_USERAGENT, D_UA );
	if (useSSL ( $url )) {
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSLVERSION, 3 );
	}
	$pageContent = curl_exec ( $ch );
	curl_close ( $ch );
	return $pageContent;
}

$pageContent = fetchContent ( $url );

$doc = new DOMDocument ();
$doc->loadHTML ( $pageContent );

if (isset ( $_REQUEST ['debug'] ) && $_REQUEST ['debug'] == 2) {
	echo $doc->saveHTML ();
	exit ();
}

$xpath = new DOMXPath ( $doc );

switch ($type) {
	case 'reviews-other' :
		$xp_query = "//div[@id='reviews-other']/ul/li";
		break;
	case 'reviews-other-rating' :
		$xp_query = "//div[@id='reviews-other']//div[@class='rating']";
		break;
	default :
		exit ();
}

$result_rows = $xpath->query ( $xp_query );

if (isset ( $_REQUEST ['debug'] ) && $_REQUEST ['debug'] == 2) {
	print_r ( $result_rows );
	exit ();
}

switch ($out) {
	case 'html' :
		foreach ( $result_rows as $result_object ) {
			$innerHTML = '';
			
			$children = $result_object->childNodes;
			foreach ( $children as $child ) {
				$tmp_doc = new DOMDocument ();
				$tmp_doc->appendChild ( $tmp_doc->importNode ( $child, true ) );
				$innerHTML .= $tmp_doc->saveHTML ();
			}
			
			echo trim ( $innerHTML ) . _NL;
		}
		break;
	default :
		foreach ( $result_rows as $result_object ) {
			echo $result_object->childNodes . _NL;
		}
		break;
}
