<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Post entity
 *
 * @author Lopo
 *
 * @entity(repositoryClass="LohiniPlugins\Blog\Models\Repositories\Post")
 * @table(name="_blog_posts")
 * @service(class="LohiniPlugins\Blog\Models\Services\Posts")
 */
class Post
extends \Lohini\Database\Doctrine\ORM\Entities\IdentifiedEntity
implements \Lohini\Database\Models\IEntity
{
	const POSTSTATE_DRAFT=0;
	const POSTSTATE_PUBLISHED=1;

	/**
	 * @column(type="string")
	 * @var string
	 */
	private $headline;
	/**
	 * @column(type="string", length=100, unique=true)
	 * @var string
	 */
	private $slug;
	/**
	 * @column(type="text")
	 * @var string
	 */
	private $text;
	/**
	 * @column(type="integer")
	 * @var int
	 */
	private $status=self::POSTSTATE_DRAFT;
	/**
	 * @column(type="integer")
	 * @var int
	 */
	private $commentsCnt=0;
	/**
	 * @column(type="datetime")
	 * @var DateTime
	 */
	private $created;
	/**
	 * @manyToOne(targetEntity="\Lohini\Database\Models\Entities\User")
	 * @joinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 * @var \Lohini\Database\Models\Entities\User
	 */
	private $user;
	/**
	 * @oneToMany(targetEntity="Comment", mappedBy="post")
	 * @var Comment[]
	 */
	private $comments;
	/**
	 * @manyToMany(targetEntity="Tag", inversedBy="posts")
	 * @joinTable(name="_blog_posts_tags",
	 *		joinColumns={@joinColumn(name="post_id", referencedColumnName="id")},
	 *		inverseJoinColumns={@joinColumn(name="tag_id", referencedColumnName="id")}
	 *		)
	 * @var Tag[]
	 */
	private $tags;


	public function __construct()
	{
		$this->tags=new ArrayCollection;
		$this->comments=new ArrayCollection;
	}

	/**
	 * @param string $headline
	 * @return Post (fluent)
	 */
	public function setHeadline($headline)
	{
		$this->headline=$this->sanitizeString($headline);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHeadline()
	{
		return $this->headline;
	}

	/**
	 * @param string $slug
	 * @return Post (fluent)
	 */
	public function setSlug($slug)
	{
		$this->slug=$this->sanitizeString($slug);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param string $text
	 * @return Post (fluent)
	 */
	public function setText($text)
	{
		$this->text=$this->sanitizeString($text);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param int $status
	 * @return Post (fluent)
	 */
	public function setStatus($status)
	{
		$this->status=$status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param int $cnt
	 * @return Post (fluent)
	 */
	public function setCommentsCnt($cnt)
	{
		$this->commentsCnt=intval($cnt);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCommentsCnt()
	{
		return $this->commentsCnt;
	}

	/**
	 * @param DateTime
	 * @return Post (fluent)
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

	/**
	 * @param \Lohini\Database\Entities\User $user
	 * @return Post (fluent)
	 */
	public function setUser($user)
	{
		$this->user=$user;
		return $this;
	}

	/**
	 * @return \Lohini\Database\Entities\User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param array
	 * @return Post (fluent)
	 */
	public function setTags($tags)
	{
		$this->tags=$tags;
		return $this;
	}

	/**
	 * @param array $comments
	 * @return Comment (fluent)
	 */
	public function setComments($comments)
	{
		$this->comments=$comments;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * @return array
	 */
	public function getTags()
	{
		return $this->tags;
	}
}
