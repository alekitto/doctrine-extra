<?php declare(strict_types=1);

namespace Fazland\DoctrineExtra\Tests\Mock;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\Common\Persistence\Mapping\RuntimeReflectionService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata as ORMClassMetadata;

class FakeMetadataFactory implements ClassMetadataFactory
{
    private $metadata = [];
    private $reflectionService;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->reflectionService = new RuntimeReflectionService();
    }

    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
    }

    public function setDocumentManager($documentManager): void
    {
    }

    public function setConfiguration(): void
    {
    }

    public function setCacheDriver(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMetadata(): array
    {
        return \array_values($this->metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($className): ClassMetadata
    {
        if (! isset($this->metadata[$className])) {
            throw new MappingException('Cannot find metadata for "'.$className.'"');
        }

        return $this->metadata[$className];
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($className): bool
    {
        return isset($this->metadata[$className]);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataFor($className, $class): void
    {
        $this->metadata[$className] = $class;

        if ($class instanceof ORMClassMetadata) {
            $class->initializeReflection($this->reflectionService);
            $class->wakeupReflection($this->reflectionService);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isTransient($className): bool
    {
        return false;
    }
}