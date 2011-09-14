<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Services;

/**
 * Tags service
 *
 * @author Lopo <lopo@lohini.net>
 */
class Tags
extends \Lohini\Database\Doctrine\ORM\BaseService
{
	/**
	 * @param array $values
	 * @param bool $withoutFlush
	 * @return Tag entity
	 */
	public function create($values, $withoutFlush=FALSE)
	{
		try {
			$values['created']=new \DateTime;
			$entity=parent::create($values, TRUE);
			$em=$this->getEntityManager();
			$em->persist($entity);
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
