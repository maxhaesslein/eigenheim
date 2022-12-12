<?php

class Text {

	public $content;


	function __construct( $text ) {
		$this->content = $text;

		return $this;
	}


	function cleanup() {
		return $this->remove_html_elements()->auto_a()->auto_p();
	}


	function remove_html_elements() {
		global $eigenheim;

		$allowed_html_elements = $eigenheim->config->get('allowed_html_elements');

		$this->content = strip_tags( $this->content, $allowed_html_elements );

		return $this;
	}


	function auto_p() {

		$text = $this->content;

		// this is based on the wpautop function from WordPress (but very much simplified). Thanks WordPress!
		// https://developer.wordpress.org/reference/functions/wpautop/
		// TODO: we may want to rewrite this based on our use case

		if ( trim( $text ) === '' ) {
			return '';
		}

		// Change multiple <br>'s into two line breaks, which will turn into paragraphs.
		$text = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $text );

		// Standardize newline characters to "\n".
		$text = str_replace( array( "\r\n", "\r" ), "\n", $text );

		// Remove more than two contiguous line breaks.
		$text = preg_replace( "/\n\n+/", "\n\n", $text );

		// Split up the contents into an array of strings, separated by double line breaks.
		$paragraphs = preg_split( '/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY );

		// Reset $text prior to rebuilding.
		$text = '';

		// Rebuild the content as a string, wrapping every bit with a <p>.
		foreach ( $paragraphs as $paragraph ) {
			$text .= '<p>' . trim( $paragraph, "\n" ) . "</p>\n";
		}

		// Normalize <br>
		$text = str_replace( array( '<br>', '<br/>' ), '<br>', $text );

		// Replace any new line characters that aren't preceded by a <br> with a <br>.
		$text = preg_replace( '|(?<!<br>)\s*\n|', "<br>\n", $text );

		// If a <br> tag is before a subset of opening or closing block tags, remove it.
		$text = preg_replace( "|\n</p>$|", '</p>', $text );

		// If there is a <br> tag after a </p> tag, remove it:
		$text = preg_replace( "/\<\/p\>\<br\>/", '</p>', $text );

		$this->content = $text;

		return $this;
	}


	function auto_a() {

		// TODO: at the moment, this will break existing a-tags and img-tags and iframes ...

		$regexp = '/(http|https)\:\/\/([a-zA-Z0-9\-\.]+)\.([a-zA-Z]+)(\/\S*)*/mix';

		$this->content = preg_replace( $regexp, '<a href="$1://$2.$3$4" target="_blank" rel="noopener" title="$1://$2.$3$4">$2.$3$4</a>', $this->content );

		return $this;
	}


	function get() {
		return $this->content;
	}


}