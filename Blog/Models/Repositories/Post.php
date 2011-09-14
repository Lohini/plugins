<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Repositories;

use DoctrineExtensions\Paginate\Paginate,
	LohiniPlugins\Blog\Models\Entities\Post as PEntity;

/**
 * Post repository
 *
 * @author Lopo <lopo@lohini.net>
 */
class Post
extends \Lohini\Database\Doctrine\ORM\EntityRepository
{
	/**
	 * @return int
	 */
	public function countAll()
	{
		return Paginate::getTotalQueryResults($this->createQueryBuilder('p')->getQuery());
	}

	/**
	 * 
	 */
	public function getPostsByTag($tag, $page=1)
	{
		$q=$this->createQueryBuilder('p')
				->leftJoin('p.tags', 't')
				->where("p.status= :status")
				->andWhere('t.name = :tag')
				->orderBy('p.created', 'DESC')
				->setParameter('status', PEntity::POSTSTATE_PUBLISHED)
				->setParameter('tag', $tag)
				->getQuery();
		$cnt=Paginate::getTotalQueryResults($q);
		return $q->setFirstResult($page-1)->setMaxResults(10)->getResult();
	}

	public function getPublishedPosts()
	{
		$q=$this->createQueryBuilder('p')
				->where("p.status= :status")
				->orderBy('p.created', 'DESC')
				->setParameter('status', PEntity::POSTSTATE_PUBLISHED)
				->getQuery();
		return $q->getResult();
	}

	public function getPublishedPostsPage($page=1)
	{
		$q=$this->createQueryBuilder('p')
				->where("p.status= :status")
				->orderBy('p.created', 'DESC')
				->setParameter('status', PEntity::POSTSTATE_PUBLISHED)
				->getQuery();
		$cnt=Paginate::getTotalQueryResults($q);
		return $q->setFirstResult($page-1)->setMaxResults(10)->getResult();
	}
}
