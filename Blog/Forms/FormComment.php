<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Forms;

use Nette\Forms\Form;

/**
 * Comment form
 *
 * @author Lopo <lopo@lohini.net>
 */
class FormComment
extends \Lohini\Application\UI\Form
{
	public function __construct($parent, $name)
	{
		parent::__construct($parent, $name);
		if (!$this->presenter->getUser()->isLoggedIn()) {
			$this->addText('author', 'Author')
					->addRule(Form::FILLED, 'Enter name')
					->controlPrototype->placeholder='Your Name';
			$this->addText('email', 'Email')
					->addRule(Form::FILLED, 'Enter email')
					->addRule(Form::EMAIL, 'Not valid email')
					->controlPrototype->placeholder='Your Email';
			$this->addText('url', 'Website')
					->controlPrototype->placeholder='Your Website';
			}

		$this->addTextArea('comment', 'Comment', 20, 10)
				->addRule(Form::FILLED, 'Comment can\'t be empty')
				->labelPrototype->class='visuallyHidden';

		$this->addSubmit('send', 'Send');
		$this->onSuccess[]=array($this, 'formSubmitted');
		$this->onInvalidSubmit[]=array($this, 'formInvalid');
	}

	public function formSubmitted(Form $form)
	{
		try {
			$vals=$this->getValues();
			$user=$this->presenter->getUser();
			if ($user->isLoggedIn()) {
				$identity=$user->identity;
				$vals['author']=$identity->displayName;
				$vals['email']=$identity->email;
				$vals['url']=$this->presenter->link('//:Core:Default:');
				}
			$vals['url']=rtrim(\Nette\Utils\Strings::replace($vals['url'], '#^https?://#', ''), '/');
			$form->presenter->context->sqldb
				->getRepository('LP:Blog\Models\Entities\Comment')
				->insertNew(
					$vals,
					$this->presenter->getParam('slug')
					);
			}
		catch (Exception $e) {
			$this->presenter->flashMessage('Saving of new comment failed', 'error');
			$this->response(FALSE);
			}
		$this->presenter->flashMessage('Comment was successfuly saved', 'info');
		$this->response(TRUE);
	}

	public function formInvalid()
	{
		if ($this->presenter->isAjax()) {
			$this->response(FALSE);
			}
	}

	private function response($success=TRUE)
	{
		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this#comments');
			}
		else {
			if ($success) {
				$comments=$this->presenter->context->sqldb->getRepository('LP:Blog\Models\Entities\Comment')->getBySlug($this->presenter->getParam('slug'));
				$this->presenter->template->comments=$comments;

				$this->presenter->invalidateControl('comments');
				$this->presenter->invalidateControl('commentsLink');
				$this->setValues(array(), TRUE);
				}
			$this->presenter->invalidateControl('commentForm');
			}
	}
}
