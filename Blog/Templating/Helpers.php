<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Templating;

/**
 * Helpers
 *
 * @author Lopo <lopo@lohini.net>
 */
class Helpers
{
	/** @var array */
	private static $helpers=array(
//		'perex' => 'LohiniPlugins\Blog\Templating\Helpers::perex'
		);


	/**
	 * Try to load the requested helper.
	 * @param string $helper name
	 * @return callback
	 */
	public static function loader($helper)
	{
		if (method_exists(__CLASS__, $helper)) {
			return callback(__CLASS__, $helper);
			}
		if (isset(self::$helpers[$helper])) {
			return self::$helpers[$helper];
			}
		// fallback
		return \Nette\Templating\DefaultHelpers::loader($helper);
	}

	/**
	 * @param string $subject
	 * @return string
	 */
	public static function perex($subject)
	{
		$pos=strpos($subject, '<!--break-->');

		return $pos!==FALSE
			? substr($subject, 0, $pos)
			: $subject;
	}
}
