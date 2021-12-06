<?php


namespace Drupal\custom_hook\Plugin\Block;

use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;
use \Drupal\taxonomy\Entity\Term;
use \Drupal\Core\Database\Database;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "custom_hook_block",
 *   admin_label = @Translation("Custom Hook block"),
 * )
 */

class LatestAmericanNode extends BlockBase implements ContainerFactoryPluginInterface{

	/**
	  * The Module Handler.
	  * 
	  * @var Drupal\Core\Extension\ModuleHandlerInterface
	  */
	protected $moduleHandler;

	/**
	  * Construct LatestAmerican Node.
	  *
	  * @param array $configuration
	  *		Plugin Configuration.
	  * @param  string $plugin_id
	  *     Plugin id.
	  * @param mixed $plugin_defination
	  *		Plugin Defination.
	  * @param Drupal\Core\Extension\ModuleHandlerInterface $module_handler
	  * 	The Module Handler.
	  */

	public function __construct(array $configuration, $plugin_id, $plugin_defination, ModuleHandlerInterface $module_handler){
		parent::__construct($configuration, $plugin_id, $plugin_defination,);
		$this->moduleHandler = $moduleHandler;
	}

	public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_defination){
		return new static(
			$configuration,
			$plugin_id,
			$plugin_defination,
			$container->get('module_handler')
		);
	}

	/**
   * {@inheritdoc}
   */
	public function build() {

		$tid = 1260;
		$query = \Drupal::entityQuery('node')
				->condition('field_conference', $tid)
				->sort('created', 'DESC')
				->range(0,5);
		$list = $query->execute();

		// $this->moduleHandler->invokeAll('mycustom_hook', [$list]);
		// $this->moduleHandler->alter('mycustom_hook', $list);

		$list_to_string = implode(",", $list);

		return [
			'#markup' => '<marquee>Latest American Node:' . $list_to_string . '</marquee>',
			'#allowed_tags' => ['marquee'],
		];
	
	}
}

