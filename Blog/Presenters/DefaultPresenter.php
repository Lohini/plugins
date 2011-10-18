<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Presenters;

use Nette\Forms\Form,
	LohiniPlugins\Blog;

/**
 * Default plugin presenter
 *
 * @author Lopo <lopo@lohini.net>
 */
class DefaultPresenter
extends BasePresenter
{
	/**
	 * @var int
	 * @persistent
	 */
	public $page=1;
	/** @var int */
	private $perPage=10;


	protected function startup()
	{
		parent::startup();
		if ($this->page<1) {
			$this->page=1;
			}
		$this->perPage=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Setting')
				->findOneByName('postsPerPage')->value;
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->page=$this->page;
	}

	public function renderDefault()
	{
		$this->template->posts=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Post')
				->getPublishedPostsPage($this->page, $this->perPage);
	}

	/**
	 * @param string $slug
	 */
	public function renderPost($slug)
	{
		$user=$this->getUser();
		$post=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Post')->findOneBySlug($slug);
		if ($post===NULL
			|| ($post->status!==Blog\Models\Entities\Post::POSTSTATE_PUBLISHED && !$user->isLoggedIn())
			) {
			$this->redirect('default');
			}
		$this->template->post=$post;
		$this->template->comments= $post->commentsCnt? $post->comments : NULL;
		$this->template->formComment=$this['formComment'];
	}

	/**
	 * @param string $slug
	 */
	public function renderTag($tag)
	{
		$repo=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Post');
		$this->template->posts=$repo->getPostsByTag($tag, $this->page, $this->perPage);
		$this->template->tagName=$tag;
	}

	/**
	 * @param string $callback
	 */
	public function actionTagcloud($callback)
	{
		$tags=array();
		foreach ($this->context->sqldb->getRepository('LP:Blog\Models\Entities\Tag')->findAll() as $tag) {
			$tags[]=array(
				'tag' => $tag->name,
				'freq' => count($tag->posts)
				);
			}
		shuffle($tags);
		echo "$callback({tags:".\Nette\Utils\Json::encode($tags).'})';
		$this->terminate();
	}

	public function actionArchive()
	{
		$this->template->posts=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Post')
				->getPublishedPostsPage($this->page, $this->perPage);
	}

	/**
	 * @return Form
	 */
	protected function createComponentFormComment()
	{
		return new Blog\Forms\FormComment($this, 'formComment');
	}

	public function actionRss()
	{
		$this->template->posts=$this->context->sqldb->getRepository('LP:Blog\Models\Entities\Post')
				->getPublishedPostsPage();
	}
}
