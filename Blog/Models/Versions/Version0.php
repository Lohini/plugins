<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog\Models\Versions;

/**
 * Version1
 *
 * @author Lopo <lopo@lohini.net>
 */
class Version0
extends \Doctrine\DBAL\Migrations\AbstractMigration
{
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(\Doctrine\DBAL\Schema\Schema $schema)
	{
	}

	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(\Doctrine\DBAL\Schema\Schema $schema)
	{
	}
}
