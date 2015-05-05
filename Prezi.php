<?php
/**
 * Prezi extension -- allows embedding presentations from Prezi.com
 *
 * Adapted from the MediaWikiWidget with the same name.
 * @see http://www.mediawikiwidgets.org/Prezi
 *
 * Example usage:
 * <prezi id="whatever" width="700" height="500" bgcolor="#000000" color="#FFA500" linktext="Jack Phoenix on MediaWiki development and its challenges" />
 *
 * @file
 * @ingroup Extensions
 * @date 1 November 2014
 * @author Jack Phoenix <jack@countervandalism.net>
 * @license https://en.wikipedia.org/wiki/Public_domain Public domain (code portions written by Jack Phoenix)
 * @link https://www.mediawiki.org/wiki/Extension:Prezi Documentation
 * @see http://bugzilla.shoutwiki.com/show_bug.cgi?id=213
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Prezi',
	'version' => '1.1',
	'author' => 'Jack Phoenix',
	'descriptionmsg' => 'prezi-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Prezi',
);

// Autoload the class & internationalization (i18n) and set up the <prezi> tag
$wgAutoloadClasses['PreziTag'] = __DIR__ . '/PreziTag.class.php';
$wgMessagesDirs['Prezi'] = __DIR__ . '/i18n';

$wgHooks['ParserFirstCallInit'][] = 'PreziTag::registerHook';