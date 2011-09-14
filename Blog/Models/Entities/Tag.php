<?php // vim: ts=4 sw=4 ai:
namespace LohiniPlugins\Blog\Models\Entities;

/**
 * Tag entity
 *
 * @author Lopo
 *
 * @entity(repositoryClass="LohiniPlugins\Blog\Models\Repositories\Tag")
 * @table(name="_blog_tags")
 * @service(class="LohiniPlugins\Blog\Models\Services\Tags")
 */
class Tag
extends \Lohini\Database\Doctrine\ORM\Entities\IdentifiedEntity
implements \Lohini\Database\Models\IEntity
{
	/**
	 * @column(type="string", length=100, unique=true)
	 * @var string
	 */
	private $name;
	/**
	 * @manyToMany(targetEntity="Post", mappedBy="tags")
	 * @var Post[]
	 */
	private $posts;
	/**
	 * @column(type="datetime")
	 * @var DateTime
	 */
	private $created;


	public function __construct()
	{
		$this->posts=new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param string $name
	 * @return Tag (fluent)
	 */
	public function setName($name)
	{
		$this->name=$this->sanitizeString($name);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param array
	 * @return Tag (fluent)
	 */
	public function setPosts($posts)
	{
		$this->posts=$posts;
	}

	/**
	 * @return array
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * @param DateTime
	 * @return Tag (fluent)
	 */
	public function setCreated($datetime)
	{
		$this->created=\Nette\DateTime::from($datetime);
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}
}
