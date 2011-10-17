<?php // vim: ts=4 sw=4 ai:
/**
 * This file is part of Lohini plugin Blog
 *
 * @copyright (c) 2010, 2011 Lopo <lopo@lohini.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License Version 3
 */
namespace LohiniPlugins\Blog;

use Nette\Application\IRouter,
	Nette\Application\Routers\Route,
	Nette\Environment;

/**
 * Main class of plugin Blog
 *
 * @author Lopo <lopo@lohini.net>
 */
class Plugin
extends \Lohini\Plugins\Plugin
{
	const VERSION=201109140923;
	const AUTHOR='Lopo';
	const PREFIX='blog';

	public $dependencies=array(
		'php' => '5.3.2',
		'lohini' => '0.2.0-dev'
		);


	/**
	 * @param IRouter $router
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
		$blog[]=new Route(self::PREFIX.'[/<lang='.Environment::getVariable('lang').' [a-z]{2}>]', 'Default:');
	}

	/**
	 * @param IRouter $router
	 * @return RouteList
	 */
	private function routerInstalled(IRouter $router)
	{
		$router[]=new Route(self::PREFIX.'/admin[/<action=default>]', 'Admin:Default:');
		return $router;
	}

	/**
	 * @param IRouter $router
	 * @return RouteList
	 */
	private function routerEnabled(IRouter $router)
	{
		$router[]=new Route(self::PREFIX.'/tagcloud', 'Default:tagcloud');
		$router[]=new Route(self::PREFIX.'/rss[/<lang='.\Nette\Environment::getVariable('lang').' [a-z]{2}>]', 'Default:rss');
		$router[]=new Route(self::PREFIX.'/archive[/<lang='.Environment::getVariable('lang').' [a-z]{2}>][/<page=1 \d+>]', 'Default:archive');
		$router[]=new Route(self::PREFIX.'/tag[/<lang='.Environment::getVariable('lang').' [a-z]{2}>]/<tag [a-z0-9 _-]{3,}>[/<page=1 \d+>]', 'Default:tag');
		$router[]=new Route(self::PREFIX.'/[<lang='.Environment::getVariable('lang').' [a-z]{2}>/]<slug [a-z0-9_-]+>', 'Default:post');
		return $router;
	}
}
