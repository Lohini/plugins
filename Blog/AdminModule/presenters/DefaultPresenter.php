<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\AdminModule;

/**
 * Description of DefaultPresenter
 *
 * @author Lopo
 */
class DefaultPresenter
extends \LohiniPlugins\Blog\Presenters\SecuredPresenter
{
	protected function startup()
	{
		parent::startup();
		$this->template->title='Blog administration';
	}
}
