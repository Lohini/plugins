<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Presenters;

/**
 * Base blog plugin presenter
 *
 * @author Lopo <lopo@lohini.net>
 */
class BasePresenter
extends \Lohini\Plugins\BasePresenter
{
	protected function startup()
	{
		parent::startup();
		$this->template->MTBlogName=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Setting')->findOneByName('blogName')->value;
		$this->template->MTBlogDescription=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Setting')->findOneByName('blogDescription')->value;

		$this->template->filesPath=$this->link(':TexylaFiles:listFiles');
		$this->template->filesUploadPath=$this->link(':TexylaFiles:upload');
	}

	/**
	 * @param string $class
	 * @return \Nette\Templating\ITemplate
	 */
	protected function createTemplate($class=NULL)
	{
		$tpl=parent::createTemplate($class);

		$tpl->registerHelperLoader('\LohiniPlugins\Blog\Templating\Helpers::loader');

		return $tpl;
	}
}
