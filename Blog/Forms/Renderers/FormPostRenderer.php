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
extends \Nette\Forms\Rendering\DefaultFormRenderer
{
	/**
	 * Renders form end.
	 * @return string
	 */
	public function renderEnd()
	{
		$basePath=rtrim($this->form->getPresenter(FALSE)->getContext()->httpRequest->getUrl()->getBasePath(), '/');
		$fnTA='';
		foreach ($this->form->getControls() as $control) {
			if ($control instanceof Forms\Controls\TextArea && $fnTA=='') {
				$fid=$this->form->getElementPrototype()->id;
				$fnTA="$('#$fid textarea').ctrlEnter('button', function() { $('#$fid').submit();});";
				continue;
				}
			}
		$ldr=new \Lohini\WebLoader\JsLoader($this->form->parent, 'tagInput');
		$ldr->setSourcePath(APP_DIR.'/Plugins/Blog/htdocs/js');
		$ldr->setEnableDirect(FALSE);
		return parent::renderEnd()
			.\Nette\Utils\Html::el('script')
				->setText(
					"head.js(
						'$basePath/js/netteForms.js',
						'$basePath/js/lohiniForms.js',
						'$basePath/js/jquery.ajaxform.js',
						'$basePath/js/nette.ajax.js',
						'".$ldr->getLink('tagInput.js')."',
						function() {
							$fnTA"
							/*TagInput.create('#frm{$this->form->name}-{$this->form['tags']->name}');*/
							."}
						);");
	}
}