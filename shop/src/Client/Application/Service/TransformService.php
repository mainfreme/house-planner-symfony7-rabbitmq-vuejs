<?php

declare(strict_types=1);

namespace App\Client\Application\Service;

use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;

class TransformService
{
    private readonly array $columnName;

    private array $columnsTransform = [];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param string $className
     * @return $this
     */
    public function getColumnsName(string $className): self
    {
        $classMetadata = $this->entityManager->getClassMetadata($className);
        $this->columnName = $classMetadata->getColumnNames();

        return $this;
    }

    /**
     * @return $this
     */
    public function transformColumns(): self
    {
        foreach ($this->columnName as $column) {
            $this->columnsTransform[] = [
                'key' => $column,
                'label' => ucfirst(str_replace('_', ' ', $column))
            ];
        }

        return $this;
    }

    public function getColumnsTransformDto(string $dtoClass): array
    {
        if (!class_exists($dtoClass)) {
            throw new Exception("Class {$dtoClass} not exist!");
        }

        if (!method_exists($dtoClass, 'fromArray')) {
            throw new \Exception("Metoda 'fromArray' nie istnieje w klasie $dtoClass.");
        }
        $reflection = new \ReflectionMethod($dtoClass, 'fromArray');

        if ($reflection->isStatic()) {
            $this->columnsTransform = array_map(function ($dto) use ($dtoClass) {
                return $dtoClass::fromArray($dto);
            }, $this->columnsTransform);
        }

        return $this->columnsTransform;
    }


    /**
     * @return array
     */
    public function getColumnsTransformArray(): array
    {
        return $this->columnsTransform;
    }

}
