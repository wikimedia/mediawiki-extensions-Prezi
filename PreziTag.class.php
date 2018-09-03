<?php
/**
 * @file
 */

class PreziTag {

	/**
	 * Register the <prezi> tag with the Parser.
	 *
	 * @param Parser $parser
	 * @return bool
	 */
	public static function registerHook( $parser ) {
		$parser->setHook( 'prezi', array( __CLASS__, 'render' ) );
		return true;
	}

	/**
	 * Callback function for PreziTag::registerHook().
	 * This does all the heavy lifting.
	 *
	 * @param string $input User-supplied input; unused
	 * @param array $argv Arguments supplied to the parser tag (i.e. <prezi width="620" ...>)
	 * @param Parser $parser
	 * @return string HTML
	 */
	public static function render( $input, $argv, $parser ) {
		$id = htmlspecialchars( $argv['id'], ENT_QUOTES );
		$height = ( isset( $argv['height'] ) ? intval( $argv['height'] ) : 400 );
		$width = ( isset( $argv['width'] ) ? intval( $argv['width'] ) : 550 );

		// Colors are a bit trickier to validate...
		$backgroundColor = $color = '#FFFFFF';
		if ( isset( $argv['bgcolor'] ) ) {
			$isValidBgColor = self::validateColor( $argv['bgcolor'] );
			if ( $isValidBgColor ) {
				$backgroundColor = $argv['bgcolor'];
			}
		}

		if ( isset( $argv['color'] ) ) {
			$isValidColor = self::validateColor( $argv['color'] );
			if ( $isValidColor ) {
				// Apparently this one shouldn't include the hash...so let's take
				// it out, then!
				$color = str_replace( '#', '', $argv['color'] );
			}
		}

		$linkText = ( isset( $argv['linktext'] ) ? $argv['linktext'] : wfMessage( 'prezi-presentation' )->plain() );
		// Nothing like creative and descriptive variable names, eh?
		// Originally, in the widget that this extension is based on, there was
		// a title parameter to the <a> element but MW doesn't really allow setting
		// that from the wikitext, but frankly, who gives a fuck? Especially since
		// now the actual link text is more descriptive than the widget's "Click to open"
		// which, no doubt, would've made the i18n people cringe
		$linkGobbledyGoo = wfMessage(
			'prezi-link',
			$id,
			$linkText
		)->parse();

		$colorEncoded = htmlspecialchars( rawurlencode( $color ) );
		return '<div class="prezi-player" style="width: ' . $width . 'px">
	<object id="prezi_' . $id . '" name="prezi_' . $id . '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $width . '" height="'. $height . '"><param name="movie" value="http://prezi.com/bin/preziloader.swf"/><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always"/><param name="bgcolor" value="' . htmlspecialchars( $backgroundColor ) . '"/><param name="flashvars" value="prezi_id=' . $id . '&amp;lock_to_path=0&amp;color=' . $colorEncoded . '&amp;autoplay=no&amp;autohide_ctrls=0"/><embed id="preziEmbed_' . $id . '" name="preziEmbed_' . $id . '" src="http://prezi.com/bin/preziloader.swf" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="' . $width . '" height="' . $height . '" bgcolor="' . htmlspecialchars( $backgroundColor ) . '" flashvars="prezi_id=' . $id . '&amp;lock_to_path=0&amp;color=' . $colorEncoded . '&amp;autoplay=no&amp;autohide_ctrls=0"></embed></object><div class="prezi-player-links" style="text-align: center;"><p>' . $linkGobbledyGoo . '</p></div></div>';
	}

	/**
	 * Validate a CSS hex color.
	 *
	 * @param string $color User-supplied input
	 * @return bool True if it's a valid color, otherwise false
	 */
	public static function validateColor( $color ) {
		// Check that it passed Sanitizer validation
		if ( !preg_match( '/\/\*(.*)\*\//', Sanitizer::checkCss( $color ) ) ) {
			// The following if() has been copied from http://w4dev.com/tutorial/validate-hex-color-by-php/
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $color ) ) {
				// It's a valid CSS hex color code, accept it
				return true;
			} else {
				// Nope, it's invalid
				// Named colors aren't supported because I couldn't find a
				// half-decent way of validating 'em
				return false;
			}
		}
		return false;
	}
}
