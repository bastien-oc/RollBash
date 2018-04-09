<?php

function translate( $text, $destLang = 'es', $srcLang = 'en' ) {

$text = urlencode( $text );
$destLang = urlencode( $destLang );
$srcLang = urlencode( $srcLang );

$trans = @file_get_contents( "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q={$text}&langpair={$srcLang}|{$destLang}" );
$json = json_decode( $trans, true );

if( $json['responseStatus'] != '200' ) return false; else return $json['responseData']['translatedText'];

}
?>