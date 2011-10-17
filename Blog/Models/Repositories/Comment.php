<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Repositories;

/**
 * Comment repository
 *
 * @author Lopo <lopo@lohini.net>
 */
class Comment
extends \Lohini\Database\Doctrine\ORM\EntityRepository
{
	/**
	 * @return int
	 */
	public function countAll()
	{
		$res=$this->createQueryBuilder('c')
				->select('count(c.id) i')
				->getQuery()
				->getSingleResult();
		return $res['i'];
	}

	/**
	 * @return int
	 */
	public function countNotApproved()
	{
		return $this->countByAttribute('approved', FALSE);
	}

	/**
	 * @param string $slug
	 * @return array
	 */
	public function getBySlug($slug)
	{
		return $this->createQueryBuilder('c')
				->leftJoin('c.post', 'p')
				->where('p.slug = :slug')
				->orderBy('c.created', 'ASC')
				->setParameter('slug', $slug)
				->getQuery()
				->getResult();
	}

	/**
	 * @param \Nette\ArrayHash $data
	 * @param string $slug
	 * @param type $request
	 * @return Comment
	 */
	public function insertNew($data, $slug)
	{
		$data['ip']=\Lohini\Utils\Network::getRemoteIP();
		$data['text']=$data['comment'];
		$data['approved']=TRUE;
		$data['created']=new \DateTime;
		$data['post']=$this->getEntityManager()->getRepository('LP:Blog\Models\Entities\Post')->findOneBySlug($slug);
		return \Nette\Environment::getService('sqldb')->getModelService('LohiniPlugins\Blog\Models\Entities\Comment')->create($data);
	}

	/**
	 * @param string|int $ip
	 * @return \LohiniPlugins\Blog\Models\Entities\Comment
	 */
	public function getLastByIP($ip)
	{
		if (is_string($ip)) {
			$ip=ip2long($ip);
			}
		return $this->createQueryBuilder('c')
				->where('c.ip = :ip')
				->orderBy('c.created', 'DESC')
				->setParameter('ip', $ip)
				->getQuery()
				->setMaxResults(1)
				->getSingleResult();
	}
}
