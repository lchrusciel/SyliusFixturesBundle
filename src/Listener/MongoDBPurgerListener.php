<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\FixturesBundle\Listener;

use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class MongoDBPurgerListener extends AbstractListener implements BeforeSuiteListenerInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /** @param array{managers: string[]} $options */
    public function beforeSuite(SuiteEvent $suiteEvent, array $options): void
    {
        foreach ($options['managers'] as $managerName) {
            /** @var DocumentManager $manager */
            $manager = $this->managerRegistry->getManager($managerName);

            $purger = new MongoDBPurger($manager);
            $purger->purge();
        }
    }

    public function getName(): string
    {
        return 'mongodb_purger';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('managers')
                    ->defaultValue([null])
                    ->scalarPrototype()
        ;
    }
}
