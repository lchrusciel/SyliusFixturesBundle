<?php

declare(strict_types=1);

namespace AppBundle\Fixture;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class SampleFixture extends AbstractFixture implements FixtureInterface
{
    /** @var ObjectManager */
    private $countryManager;

    public function __construct(ObjectManager $countryManager)
    {
        $this->countryManager = $countryManager;
    }

    public function getName(): string
    {
        return 'sample';
    }

    public function load(array $options): void
    {
        foreach ($options['countries'] as $countryCode) {
            $country = new Country($countryCode);

            $this->countryManager->persist($country);
        }

        $this->countryManager->flush();
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('samples')
                ->prototype('scalar')
        ;
    }

}
