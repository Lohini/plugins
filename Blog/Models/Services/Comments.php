<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Services;

/**
 * Comments service
 *
 * @author Lopo <lopo@lohini.net>
 */
class Comments
extends \Lohini\Database\Doctrine\ORM\BaseService
{
	/**
	 * @param array $values
	 * @param bool $withoutFlush
	 * @return LohiniPlugins\Blog\Models\Entities\Comment
	 */
	public function create($values, $withoutFlush=FALSE)
	{
		try {
			if ((!array_key_exists('post', $values) || $values['post']===NULL)
				&& array_key_exists('slug', $values)
				&& is_string($values['slug'])
				) {
				if (($post=$this->getContainer()->getRepository('LP:Blog\Models\Entities\Post')->findOneBySlug('slug'))===NULL) {
					throw new \Nette\InvalidArgumentException('Invalid slug - post not found');
					}
				$values['post']=$post;
				}
			$values['created']=new \DateTime;
			$entity=parent::create($values, TRUE);
			$em=$this->getEntityManager();
			$em->persist($entity);
			$values['post']->commentsCnt++;
			if (!$withoutFlush) {
				$em->flush();
				}
			return $entity;
			}
		catch (\PDOException $e) {
			$this->processPDOException($e);
			}
	}
}
