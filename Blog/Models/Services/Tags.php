<?php // vim: ts=4 sw=4 ai:
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
	 * @return Entity
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
