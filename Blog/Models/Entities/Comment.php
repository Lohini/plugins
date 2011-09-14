<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Entities;

/**
 * Comment entity
 *
 * @author Lopo <lopo@lohini.net>
 * @entity(repositoryClass="LohiniPlugins\Blog\Models\Repositories\Comment")
 * @table(name="_blog_comments")
 * @service(class="LohiniPlugins\Blog\Models\Services\Comments")
 */
class Comment
extends \Lohini\Database\Doctrine\ORM\Entities\IdentifiedEntity
implements \Lohini\Database\Models\IEntity
{
	/**
	 * @column(type="string", length=100)
	 * @var string
	 */
	private $author;
	/**
	 * @column(type="string", length=100)
	 * @var string
	 */
	private $email;
	/**
	 * @column(type="string", length=100, nullable=true)
	 * @var string
	 */
	private $url;
	/**
	 * @column(type="integer")
	 * @var int
	 */
	private $ip;
	/**
	 * @column(type="text")
	 * @var string
	 */
	private $text;
	/**
	 * @column(type="boolean")
	 * @var bool
	 */
	private $approved;
	/**
	 * @column(type="datetime")
	 * @var DateTime
	 */
	private $created;
	/**
	 * @manyToOne(targetEntity="Post", inversedBy="comments")
	 * @var Post
	 */
	private $post;


	/**
	 * @param string $author
	 * @return Comment (fluent)
	 */
	public function setAuthor($author)
	{
		$this->author=$this->sanitizeString($author);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @param string $email
	 * @return Comment (fluent)
	 */
	public function setEmail($email)
	{
		$this->email=$this->sanitizeString($email);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $url
	 * @return Comment (fluent)
	 */
	public function setUrl($url)
	{
		$this->url=$this->sanitizeString($url);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $ip
	 * @return Comment (fluent)
	 */
	public function setIp($ip)
	{
		$this->ip=ip2long($ip);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIp()
	{
		return long2ip($this->ip);
	}

	/**
	 * @param string $text
	 * @return Comment (fluent)
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
	 * @param bool $approved
	 * @return Comment (fluent)
	 */
	public function setApproved($approved)
	{
		$this->approved=(bool)$approved;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getApproved()
	{
		return $this->approved;
	}

	/**
	 * @param DateTime $created
	 * @return Comment (fluent)
	 */
	public function setCreated($created)
	{
		$this->created=\Nette\DateTime::from($created);
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
	 * @param Post $post
	 * @return Comment (fluent)
	 */
	public function setPost($post)
	{
		$this->post=$post;
		return $this;
	}

	/**
	 * @return Post
	 */
	public function getPost()
	{
		return $this->post;
	}
}
