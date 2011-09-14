<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\AdminModule;

use Nette\Utils\Html,
	Nette\Environment,
	LohiniPlugins\Blog\Models\Entities\Post;

/**
 * Posts admin presenter
 *
 * @author Lopo <lopo@lohini.net>
 */
class PostsPresenter
extends \LohiniPlugins\Blog\Presenters\SecuredPresenter
{
	protected function startup()
	{
		parent::startup();
		$this->template->title='Blog posts administration';
	}

	public function actionAddPost()
	{
		$form=$this['formPost'];
		$form['update']->setDisabled();
		$form['id']->setDisabled();
	}

	/**
	 * @param srtring $name
	 * @return Form
	 */
	protected function createComponentFormPost()
	{
		return new \LohiniPlugins\Blog\Forms\FormPost($this, 'formPost');
	}

	/**
	 * @param string $texy
	 */
	public function actionPreview($texy)
	{
		echo \Lohini\Templating\Helpers::texy($texy);
		$this->terminate();
	}

	/**
	 * @param string $word_filter
	 */
	public function actionTagInputSuggestTags($word_filter)
	{
		$form=$this->getComponent('formPost');
		$form['tags']->renderResponse($this, $word_filter);
	}

	/**
	 * @return \Lohini\Components\DataGrid\DataGrid
	 */
	protected function createComponentGridPostsList()
	{
		$grid=new \Lohini\Components\DataGrid\DataGrid;
		$ds=new \Lohini\Database\DataSources\Doctrine\QueryBuilder(
				$this->context->sqldb->entityManager->createQueryBuilder()
					->select('p, i')
					->from('LP:Blog\Models\Entities\Post', 'p')
					->leftJoin('p.user', 'u')
					->leftJoin('u.identity', 'i')
				);
		$ds->setMapping(array(
			'id' => 'p.id',
			'slug' => 'p.slug',
			'title' => 'p.headline',
			'author' => 'i.displayName',
			'cmtCnt' => 'p.commentsCnt',
			'created' => 'p.created',
			'status' => 'p.status'
			));
		$grid->setDataSource($ds);
		$grid->keyName='id';
		$presenter=$this->presenter;
		$sarr=array(
			0 => 'Draft',
			1 => 'Published'
			);

		$grid->addColumn('title', 'Title')
				->addTextFilter();
		$grid->addColumn('author', 'Author')
				->addSelectboxFilter();
		$grid->addNumericColumn('cmtCnt', 'Comments')
				->addFilter();
		$grid->addDateColumn('created', 'Created')
				->addDateFilter();
		$grid->addColumn('status', 'Status')
				->addSelectboxFilter($sarr);

		$grid->addActionColumn('actions', 'Actions');
		$grid->addAction('edit', 'editPost');
		$grid->addAction('publish', 'postPublish!');

		$grid['title']->formatCallback[]=function($val, $row) use ($presenter) {
			return Html::el('a', array('href' => $presenter->link(':Blog:Default:post', $row['slug'])))->setText($val);
			};
		$grid['status']->replacement=$sarr;

		$grid->getRenderer()->onActionRender[]=callback($this, 'gridPostsListActionRender');

		$grid->defaultOrder='created';

		return $grid;
	}

	public function gridPostsListActionRender(Html $action, $data)
	{
		$ai=Html::el('span')->class('ui-icon')->style('display: block; float: left;');
		switch ($action->title) {
			case 'publish':
				if ($data['status']===Post::POSTSTATE_DRAFT) {
					$ai->addClass('ui-icon-extlink');
					$action->setName('a');
					}
				else {
					$ai->addClass('ui-icon-empty');
					$action->setName('span');
					}
				break;
			case 'edit':
				$ai->addClass('ui-icon-document');
				break;
			}
		$action->setHtml($ai);
		return $action;
	}

	/**
	 * @param int $id
	 */
	public function actionEditPost($id)
	{
		$form=$this['formPost'];
		$sqldb=Environment::getService('sqldb');
		if (($post=$sqldb->getRepository('LP:Blog\Models\Entities\Post')->findOneById($id))===NULL) {
			$this->redirect('default');
			}
		$form->setDefaults(array(
				'id' => $id,
				'headline' => $post->headline,
				'text' => $post->text,
				'tags' => implode(',', array_map(function($tag) {return $tag->name;}, $post->tags->toArray()))
				));
		$form['draft']->setDisabled();
		if ($post->status===Post::POSTSTATE_PUBLISHED) {
			$form['publish']->setDisabled();
			}
	}

	public function handlePostPublish($id)
	{
		$sqldb=Environment::getService('sqldb');
		if (($post=$sqldb->getRepository('LP:Blog\Models\Entities\Post')->findOneBy(array('id'=>$id, 'status'=>Post::POSTSTATE_DRAFT)))!==NULL) {
			$post->status=Post::POSTSTATE_PUBLISHED;
			$sqldb->entityManager->flush();
			}
		$this['gridPostsList']->invalidateControl();
	}
}
