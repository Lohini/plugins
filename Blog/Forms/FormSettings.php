<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Forms;

use Nette\Forms\Form,
	Nette\Utils\Strings;

/**
 * Settings form
 *
 * @author Lopo <lopo@lohini.net>
 */
class FormSettings
extends \Lohini\Application\UI\Form
{
	/**
	 * @param \Nette\ComponentModel\IContainer $parent
	 * @param string $name
	 */
	public function __construct(\Nette\ComponentModel\IContainer $parent, $name)
	{
		parent::__construct($parent, $name);
		$repo=\Nette\Environment::getService('sqldb')->getRepository('LP:Blog\Models\Entities\Setting');

		$this->addText('blogName', 'Blog name', 100, 255)
				->setDefaultValue($repo->findOneByName('blogName')->value);
		$this->addText('blogDescription', 'Blog description', 100, 255)
				->setDefaultValue($repo->findOneByName('blogDescription')->value);
		$this->addText('postsPerPage', 'Posts per page', 5, 2)
				->addRule(Form::INTEGER, 'value must be integer')
				->setDefaultValue($repo->findOneByName('postsPerPage')->value);
		$this->addText('commentsInterval', 'Comments interval (minutes, 0=disable)', 5, 2)
				->addRule(Form::INTEGER, 'value must be integer')
				->setDefaultValue($repo->findOneByName('commentsInterval')->value);

		$this->addSubmit('update', 'Update');

		$this->onSuccess[]=callback($this, 'submittedUpdate');
	}

	/**
	 */
	public function submittedUpdate()
	{
		$sqldb=\Nette\Environment::getService('sqldb');
		$fvals=$this->getValues();
		$repo=$sqldb->getRepository('LohiniPlugins\Blog\Models\Entities\Setting');

		$repo->findOneByName('blogName')->value=$fvals['blogName'];
		$repo->findOneByName('blogDescription')->value=$fvals['blogDescription'];
		$repo->findOneByName('postsPerPage')->value=$fvals['postsPerPage'];
		$repo->findOneByName('commentsInterval')->value=$fvals['commentsInterval'];

		$sqldb->entityManager->flush();
	}
}
