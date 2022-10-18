<?php
/**
 * Plugin Name: Target _blank
 * Description: Adds or creates <code>target = "_ blank"</code> in links on the_content() in single post
 * Version:     1.0.3
 * Plugin URI:  https://jetplugs.com
 * Author:      jetplugs.com
 * License:     GPL-2.0+
 * Text Domain: tmc_tb
 * Domain Path: /langugages
 *
 */


if ( ! defined( 'ABSPATH' ) )
{
    die( 'Die, silly human!' );
}

add_filter( 'the_content', 'modifyContent' );

function modifyContent( $content )
{

    $pattern =  '/<a.*?>.*?<\/a>/';

    if ( preg_match_all( $pattern, $content )  && class_exists( 'DOMDocument' ) && is_single()  )
    {

        $content = preg_replace_callback( $pattern, function( $matches )
        {

            libxml_use_internal_errors( true );
            $dom                       = new DOMDocument( '1.0', 'UTF-8' );
            $dom->strictErrorChecking  = false;
            $dom->validateOnParse      = false;
            $dom->loadHTML( mb_convert_encoding( $matches[0], 'HTML-ENTITIES', 'UTF-8' ) );

            foreach ( $dom->getElementsByTagName( 'a' ) as $item )
            {
                $item->setAttribute( 'target', '_blank' );
            }

            libxml_clear_errors();

            return $dom->saveHTML();

        } ,$content);
    }

    return $content;
}