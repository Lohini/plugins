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
 * @version 201110171655
 * @author Lopo <lopo@lohini.net>
 */
class Version201110171655
extends \Doctrine\DBAL\Migrations\AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
	}

	/**
	 * @param Schema $schema
	 */
	public function postUp(Schema $schema)
	{
		$this->connection->insert('_blog_settings', array('name'=>'commentsInterval', 'value'=>5));
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
	}
}
