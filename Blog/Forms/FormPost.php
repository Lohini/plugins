<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Forms;

use Nette\Forms\Form,
	Nette\Utils\Strings,
	LohiniPlugins\Blog\Models\Entities\Post;

/**
 * Post form
 *
 * @author Lopo <lopo@lohini.net>
 */
class FormPost
extends \Lohini\Application\UI\Form
{
	/**
	 * @param \Nette\ComponentModel\IContainer $parent
	 * @param string $name
	 */
	public function __construct(\Nette\ComponentModel\IContainer $parent, $name)
	{
		parent::__construct($parent, $name);

		$this->addHidden('id');
		$this->addText('headline', 'Title', 100, 255)
				->addRule(Form::FILLED, 'Please fill title');
		$this->addTexyla('texy', 'Text', 100, 20)
				->addRule(Form::FILLED, 'Post text is empty');
		$this->addTag('tags', 'Tags')
				->setDelimiter('[,.;]+')
				->addRule(Controls\TagInput::UNIQUE, 'All tags must be unique.')
				->setSuggestCallback(function($filter, $limit) {
					return \Nette\Environment::getService('sqldb')->getRepository('LP:Blog\Models\Entities\Tag')->getBeginningWith($filter, $limit);
					});
		$this->addSubmit('draft', 'save draft');
		$this->addSubmit('update', 'update');
		$this->addSubmit('publish', 'publish');
		$this->onSuccess[]=array($this, 'submitted');

		$this->setRenderer(new Rendering\FormPostRenderer);
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @return FormPost
	 */
	private function addTag($name, $label=NULL)
	{
		$this[$name]=new Controls\TagInput($label);
		$this[$name]->renderName='tagInputSuggest'.ucfirst($name);
		return $this[$name];
	}

	/**
	 * @param array $values
	 * @param int $state
	 */
	public function postCreate($values, $state)
	{
		$sqldb=\Nette\Environment::getService('sqldb');
		$vals['headline']=$values['headline'];
		$vals['slug']=Strings::webalize(Strings::toAscii(Strings::truncate($values['headline'], 100, '')));
		$vals['text']=$values['texy'];
		$vals['status']=$state;
		$vals['created']=new \DateTime;
		$vals['tags']=$values['tags'];
		$vals['user']=$sqldb->getRepository('LE:User')->findOneById(\Nette\Environment::getUser()->getId());
		$post=$sqldb->getModelService('LohiniPlugins\Blog\Models\Entities\Post')->create($vals);
		$this->presenter->flashMessage('post created');
		$this->presenter->redirect('default');
	}

	/**
	 * @param array $values
	 */
	public function postUpdate($values)
	{
		$id=$values->id;
		$sqldb=\Nette\Environment::getService('sqldb');
		$post=$sqldb->getRepository('LP:Blog\Models\Entities\Post')->findOneById($id);
		$post->headline=$values['headline'];
		$post->slug=Strings::webalize(Strings::toAscii(Strings::truncate($values['headline'], 100, '')));
		$post->text=$values['texy'];
		$ptags=array();
		if (array_key_exists('tags', $values)) {
			$tagService=$sqldb->getModelService('LohiniPlugins\Blog\Models\Entities\Tag');
			$tags= is_string($values['tags'])? explode(',', $values['tags']) : $values['tags'];
			foreach ($tags as $tag) {
				$e=$tagService->repository->findOneByName($tag);
				if ($e!==NULL) {
					$ptags[]=$e;
					continue;
					}
				$ptags[]=$tagService->create(array('name' => $tag));
				}
			}
		$post->tags=$ptags;
		$sqldb->entityManager->flush();
		$this->presenter->flashMessage('post updated');
		$this->presenter->redirect('default');
	}

	/**
	 * @param Form $form
	 */
	public function submitted(Form $form)
	{
		if ($op=$form->isSubmitted()) {
			if ($form->isValid()) {
				if ($form['draft']->isSubmittedBy()) {
					return $this->postCreate($form->getValues(), Post::POSTSTATE_DRAFT);
					}
				if ($form['update']->isSubmittedBy()) {
					return $this->postUpdate($form->getValues());
					}
				if ($form['publish']->isSubmittedBy()) {
					return $this->postCreate($form->getValues(), Post::POSTSTATE_PUBLISHED);
					}
				}
			}
	}
}
