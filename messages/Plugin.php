<?php
/**
 * For licensing information, please see the LICENSE file accompanied with this file.
 *
 * @author Gerard van Helden <drm@melp.nl>
 * @copyright 2012 Gerard van Helden <http://melp.nl>
 */

namespace Zicht\Tool\Plugin\Messages;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Zicht\Tool\Container\Container;
use Zicht\Tool\Plugin as BasePlugin;

/**
 * Messages plugin
 */
class Plugin extends BasePlugin
{
    /**
     * Appends messages configuration options
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode
     * @return mixed|void
     */
    public function appendConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('messages')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('languages')->performNoDeepMerging()->prototype('scalar')->end()->defaultValue(array('nl', 'en', 'fr'))->end()
                        ->booleanNode('overwrite_compatibility')->defaultTrue()->end()
                        ->scalarNode('yaz_cleanup')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @{inheritDoc}
     */
    public function setContainer(Container $container)
    {
        if (!$container->resolve(array('messages', 'yaz_cleanup'))) {
            $container->decl(
                array('messages', 'yaz_cleanup'),
                function (Container $c) {
                    return realpath(__DIR__ . '/yaz-cleanup');
                }
            );
        }
    }
}