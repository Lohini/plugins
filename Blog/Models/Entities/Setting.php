<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Entities;

/**
 * Setting entity
 *
 * @author Lopo
 *
 * @entity
 * @table(name="_blog_settings")
 */
class Setting
extends \Lohini\Database\Doctrine\ORM\Entities\BaseEntity
implements \Lohini\Database\Models\IEntity
{
	/**
	 * @column(type="string", length=50)
	 * @id
	 * @var string
	 */
	private $name;
	/**
	 * @column(type="string")
	 * @var string
	 */
	private $value;


	/**
	 * @param mixed $name
	 * @return Setting (fluent)
	 */
	public function setName($name)
	{
		$this->name=$name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $value
	 * @return Setting (fluent)
	 */
	public function setValue($value)
	{
		$this->value=$value;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
}
