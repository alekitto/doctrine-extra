<?php declare(strict_types=1);

namespace Fazland\DoctrineExtra\ORM;

use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\QueryBuilder;
use Fazland\DoctrineExtra\ObjectIterator;

/**
 * This class allows iterating a query iterator for a single entity query.
 */
class EntityIterator implements ObjectIterator
{
    use IteratorTrait;

    /**
     * @var IterableResult
     */
    private $internalIterator;

    public function __construct(QueryBuilder $queryBuilder)
    {
        if (1 !== \count($queryBuilder->getRootAliases())) {
            throw new \InvalidArgumentException('QueryBuilder must have exactly one root aliases for the iterator to work.');
        }

        $this->queryBuilder = clone $queryBuilder;
        $this->internalIterator = $this->queryBuilder->getQuery()->iterate();

        $this->apply();
        $this->_currentElement = $this->internalIterator->current()[0];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->_current = null;
        $this->_currentElement = $this->internalIterator->next()[0];

        return $this->current();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->internalIterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->internalIterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->_current = null;
        $this->internalIterator->rewind();
        $this->_currentElement = $this->internalIterator->current()[0];
    }
}
