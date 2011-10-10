<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Versions;

use Doctrine\DBAL\Schema\Schema;

/**
 * Version file
 *
 * @version 201109140923
 * @author Lopo <lopo@lohini.net>
 */
class Version201109140923
extends \Doctrine\DBAL\Migrations\AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		$set=$schema->createTable('_blog_settings');
		$set->addColumn('name', 'string', array('length'=>50, 'notnull'=>TRUE));
		$set->addColumn('value', 'string', array('length'=>255, 'notnull'=>TRUE));
		$set->setPrimaryKey(array('name'));

		$cmts=$schema->createTable('_blog_comments');
		$cmts->addColumn('id', 'integer', array('notnull'=>TRUE, 'autoincrement'=>TRUE));
		$cmts->addColumn('author', 'string', array('length'=>100, 'notnull'=>TRUE));
		$cmts->addColumn('email', 'string', array('length'=>100, 'notnull'=>TRUE));
		$cmts->addColumn('url', 'string', array('length'=>100, 'notnull'=>FALSE));
		$cmts->addColumn('ip', 'integer', array('notnull'=>TRUE));
		$cmts->addColumn('text', 'text', array('notnull'=>TRUE));
		$cmts->addColumn('approved', 'boolean', array('notnull'=>TRUE));
		$cmts->addColumn('created', 'datetime', array('notnull'=>TRUE));
		$cmts->addColumn('post_id', 'integer', array('default'=>NULL));
		$cmts->addIndex(array('post_id'));
		$cmts->setPrimaryKey(array('id'));

		$tags=$schema->createTable('_blog_tags');
		$tags->addColumn('id', 'integer', array('notnull'=>TRUE, 'autoincrement'=>TRUE));
		$tags->addColumn('name', 'string', array('length'=>100, 'notnull'=>TRUE));
		$posts->addColumn('created', 'datetime', array('notnull'=>TRUE));
		$tags->addUniqueIndex(array('name'));
		$tags->setPrimaryKey(array('id'));

		$posts=$schema->createTable('_blog_posts');
		$posts->addColumn('id', 'integer', array('notnull'=>TRUE, 'autoincrement'=>TRUE));
		$posts->addColumn('user_id', 'integer', array('notnull'=>TRUE));
		$posts->addColumn('headline', 'string', array('length'=>255, 'notnull'=>TRUE));
		$posts->addColumn('slug', 'string', array('length'=>100, 'notnull'=>TRUE));
		$posts->addColumn('text', 'text', array('notnull'=>TRUE));
		$posts->addColumn('status', 'string', array('length'=>45, 'notnull'=>TRUE));
		$posts->addColumn('commentsCnt', 'integer', array('notnull'=>TRUE));
		$posts->addColumn('created', 'datetime', array('notnull'=>TRUE));
		$posts->addUniqueIndex(array('slug'));
		$posts->addIndex(array('user_id'));
		$posts->setPrimaryKey(array('id'));

		$pt=$schema->createTable('_blog_posts_tags');
		$pt->addColumn('post_id', 'integer', array('notnull'=>TRUE));
		$pt->addColumn('tag_id', 'integer', array('notnull'=>TRUE));
		$pt->addIndex(array('post_id'));
		$pt->addIndex(array('tag_id'));
		$pt->setPrimaryKey(array('post_id', 'tag_id'));

		$cmts->addForeignKeyConstraint($posts, array('post_id'), array('id'));
		$posts->addForeignKeyConstraint($schema->getTable('users'), array('user_id'), array('id'));
		$pt->addForeignKeyConstraint($posts, array('post_id'), array('id'));
		$pt->addForeignKeyConstraint($tags, array('tag_id'), array('id'));
	}

	/**
	 * @param Schema $schema
	 */
	public function postUp(Schema $schema)
	{
		$s=$schema->getTable('_blog_settings');
		$this->connection->insert($schema->getTable('_blog_settings'), array('name'=>'blogName'));
		$this->connection->insert($schema->getTable('_blog_settings'), array('name'=>'blogDescription'));
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('_blog_posts_tags');
		$schema->dropTable('_blog_posts');
		$schema->dropTable('_blog_tags');
		$schema->dropTable('_blog_comments');
		$schema->dropTable('_blog_settings');
	}
}
