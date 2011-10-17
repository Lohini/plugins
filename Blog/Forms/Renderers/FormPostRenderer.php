<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Forms\Rendering;

use Nette\Forms;

/**
 * Form renderer
 *
 * @author Lopo <lopo@lohini.net>
 */
class FormPostRenderer
extends \Lohini\Forms\Rendering\FormRenderer
{
	/**
	 * Renders form end.
	 * @return string
	 */
	public function renderEnd()
	{
		$basePath=rtrim($this->form->getPresenter(FALSE)->getContext()->httpRequest->getUrl()->getBasePath(), '/');
		$ldr=new \Lohini\WebLoader\JsLoader($this->form->parent, 'tagInput');
		$ldr->setSourcePath(APP_DIR.'/Plugins/Blog/htdocs/js');
		$ldr->setEnableDirect(FALSE);
		return parent::renderEnd()
			.\Nette\Utils\Html::el('script')
				->setText(
					"head.js(
						'".$ldr->getLink('tagInput.js')."'
						);");
	}
}