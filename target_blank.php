<?php
/**
 * Plugin Name: Target _blank
 * Description: Adds or creates <code>target = "_ blank"</code> in links on the_content() in single post
 * Version:     1.0.1
 * Plugin URI:  https://jetplugs.com
 * Author:      jetplugs.com
 * License:     GPL-2.0+
 * Text Domain: tmc_mm
 * Domain Path: /langugages
 *
*/


if ( ! defined( 'ABSPATH' ) ) {
	die( 'Die, silly human!' );
}


add_filter( 'the_content', 'modifyContent' );

function modifyContent( $content )
{

    $pattern =  '/<a.*?>.*?<\/a>/';


    if ( preg_match_all( $pattern, $content )  && class_exists('DOMDocument') && is_single()  )
    {

        libxml_use_internal_errors( true );

        $matches                   = array();
        $dom                       = new DOMDocument();
        $dom->strictErrorChecking  = false;
        $dom->validateOnParse      = false;


        preg_match_all( $pattern, $content, $matches );

        foreach ( $matches[0] as $match )
        {

            $dom->loadHTML( $match );

            foreach ( $dom->getElementsByTagName( 'a' ) as $item )
            {

                $item->setAttribute( 'target', '_blank' );
            }

            libxml_clear_errors();
        }


        $content = preg_replace( $pattern, $dom->saveHTML(), $content );
    }

    return $content;
}