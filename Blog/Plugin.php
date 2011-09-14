<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog;

use Nette\Application\IRouter,
	Nette\Application\Routers\Route;

class Plugin
extends \Lohini\Plugins\Plugin
{
	const VERSION=201109140923;
	const AUTHOR='Lopo';
	const PREFIX='blog';


	/**
	 * @param RouteList $router
	 * @return RouteList
	 */
	public function injectRouter(IRouter $router)
	{
		$router[]= $blog= new \Nette\Application\Routers\RouteList('Blog');
		switch ($this->entity->state) {
			case self::STATE_INSTALLED:
				$this->routerInstalled($blog);
				break;
			case self::STATE_ENABLED:
				$this->routerInstalled($blog);
				$this->routerEnabled($blog);
				break;
			default:
				return;
			}
		$blog[]=new Route(self::PREFIX, 'Default:');
	}

	/**
	 * @param RouteList $router
	 * @return RouteList
	 */
	private function routerInstalled(IRouter $router)
	{
		$router[]=new Route(self::PREFIX.'/admin[/<action=default>]', 'Admin:Default:');
		return $router;
	}

	/**
	 * @param RouteList $router
	 * @return RouteList
	 */
	private function routerEnabled(IRouter $router)
	{
		$router[]=new Route(self::PREFIX.'/tagcloud', 'Default:tagcloud');
		$router[]=new Route(self::PREFIX.'/archive', 'Default:archive');
		$router[]=new Route(self::PREFIX.'/tag/<tag [a-z0-9 _-]+>', 'Default:tag');
		$router[]=new Route(self::PREFIX.'/<slug [a-z0-9_-]+>', 'Default:post');
		return $router;
	}

	/**
	 * @throws \Lohini\Plugins\PluginException
	 */
	public function checkDependencies()
	{
		$php_ver='5.3.2';
		if (!version_compare(phpversion(), $php_ver, '>=')) {
			throw \Lohini\Plugins\PluginException::outdatedDependency('PHP', $php_ver, phpversion());
			}
		$lohini_ver='0.2.0-dev';
		if (!version_compare(\Lohini\Core::VERSION, $lohini_ver, '>=')) {
			throw \Lohini\Plugins\PluginException::outdatedDependency('Lohini', $lohini_ver, \Lohini\Core::VERSION);
			}
	}

	public function preInstall()
	{
		$this->checkDependencies();
	}
}
