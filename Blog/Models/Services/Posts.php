<?php // vim: ts=4 sw=4 ai:
namespace LohiniPlugins\Blog\Models\Services;

/**
 * Posts service
 *
 * @author Lopo <lopo@lohini.net>
 */
class Posts
extends \Lohini\Database\Doctrine\ORM\BaseService
{
	/**
	 * @param array $values
	 * @param bool $withoutFlush
	 * @return Post entity
	 */
	public function create($values, $withoutFlush=FALSE)
	{
		try {
			if (array_key_exists('tags', $values)) {
				$ptags=array();
				if (is_string($values['tags'])) {
					$values['tags']=explode(',', $values['tags']);
					}
				if (is_array($values['tags'])) {
					$tagService=$this->getContainer()->getModelService('LohiniPlugins\Blog\Models\Entities\Tag');
					foreach ($values['tags'] as $tag) {
						if ($tag instanceof \LohiniPlugins\Blog\Models\Entities\Tag) {
							$ptags[]=$tag;
							continue;
							}
						$e=$tagService->repository->findOneByName($tag);
						if ($e!==NULL) {
							$ptags[]=$e;
							continue;
							}
						$ptags[]=$tagService->create(array('name'=>$tag));
						}
					}
					$values['tags']=$ptags;
				}
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
