<?php

namespace App\Factory;

use App\Entity\DragonTreasure;
use App\Repository\DragonTreasureRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<DragonTreasure>
 *
 * @method        DragonTreasure|Proxy                              create(array|callable $attributes = [])
 * @method static DragonTreasure|Proxy                              createOne(array $attributes = [])
 * @method static DragonTreasure|Proxy                              find(object|array|mixed $criteria)
 * @method static DragonTreasure|Proxy                              findOrCreate(array $attributes)
 * @method static DragonTreasure|Proxy                              first(string $sortedField = 'id')
 * @method static DragonTreasure|Proxy                              last(string $sortedField = 'id')
 * @method static DragonTreasure|Proxy                              random(array $attributes = [])
 * @method static DragonTreasure|Proxy                              randomOrCreate(array $attributes = [])
 * @method static DragonTreasureRepository|ProxyRepositoryDecorator repository()
 * @method static DragonTreasure[]|Proxy[]                          all()
 * @method static DragonTreasure[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static DragonTreasure[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static DragonTreasure[]|Proxy[]                          findBy(array $attributes)
 * @method static DragonTreasure[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static DragonTreasure[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class DragonTreasureFactory extends PersistentProxyObjectFactory
{

   private const TREASURE_NAMES = ['pile of gold coins', 'diamond-encrusted throne', 'rare magic staff', 'enchanted sword', 'set of intricately crafted goblets', 'collection of ancient tomes', 'hoard of shiny gemstones', 'chest filled with priceless works of art', 'giant pearl', 'crown made of pure platinum', 'giant egg (possibly a dragon egg?)', 'set of ornate armor', 'set of golden utensils', 'statue carved from a single block of marble', 'collection of rare, antique weapons', 'box of rare, exotic chocolates', 'set of ornate jewelry', 'set of rare, antique books', 'giant ball of yarn', 'life-sized statue of the dragon itself', 'collection of old, used toothbrushes', 'box of mismatched socks', 'set of outdated electronics (such as CRT TVs or floppy disks)', 'giant jar of pickles', 'collection of novelty mugs with silly sayings', 'pile of old board games', 'giant slinky', 'collection of rare, exotic hats'];

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return DragonTreasure::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'coolFactor' => self::faker()->randomNumber(),
            'description' => self::faker()->text(),
            'isPublished' => self::faker()->boolean(),
            'name' => self::faker()->randomElement(self::TREASURE_NAMES),
            'plunderedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'value' => self::faker()->randomNumber(),
            'owner' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(DragonTreasure $dragonTreasure): void {})
        ;
    }
}
