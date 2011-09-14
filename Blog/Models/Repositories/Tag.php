<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Repositories;

use DoctrineExtensions\Paginate\Paginate;

/**
 * Tag repository
 *
 * @author Lopo <lopo@lohini.net>
 */
class Tag
extends \Lohini\Database\Doctrine\ORM\EntityRepository
{
	/**
	 * @return int
	 */
	public function countAll()
	{
		return Paginate::getTotalQueryResults($this->createQueryBuilder('t')->getQuery());
	}

	/**
	 * @param string $tag
	 * @param int $limit
	 * @return array
	 */
	public function getBeginningWith($tag, $limit)
	{
		$q=$this->createQueryBuilder('t')
				->select('t.name')
				->where("t.name LIKE :tag")
				->orderBy('t.name', 'DESC')
				->setParameter('tag', "$tag%")
				->getQuery();
		Paginate::getTotalQueryResults($q);
		return array_map(
				function($row) {
					return $row['name'];
					},
				$q->setMaxResults($limit)->getArrayResult()
				);
	}
}
