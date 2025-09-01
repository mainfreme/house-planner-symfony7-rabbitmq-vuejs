<?php

declare(strict_types=1);

namespace App\Infrastructure\Helper;

use App\Shared\Application\Dto\FilterDtoInterface;
use App\Shared\Application\Dto\ResponseDtoInterface;
use App\Shared\Application\ValueObject\Sort;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CacheHelper
{
    public function __construct(
        private readonly \Redis              $cache,
        private readonly SerializerInterface $serializer,
        private readonly NormalizerInterface $normalizer,
        private int                          $ttl = 3600,
    )
    {
    }

    public function generateKey(string $cacheKey, FilterDtoInterface $dto, ?Sort $sort = null): string
    {
        try {
            $string = $this->generateKeyByDto($dto);
            if (NULL !== $sort) {
                $string .= $this->generateKeyByDto($sort);
            }

            return $cacheKey . '_' . $string;
        } catch (\Exception|ExceptionInterface $e) {
            return $cacheKey;
        }
    }

    /**
     * @param FilterDtoInterface|Sort $dto
     * @return string
     * @throws ExceptionInterface
     * @throws \JsonException
     */
    private function generateKeyByDto(FilterDtoInterface|Sort $dto): string
    {
        $data = $this->normalizer->normalize($dto);
        return sha1(json_encode($data, JSON_THROW_ON_ERROR));
    }

    /**
     * @param string $class
     * @param string $cacheKey
     * @return false|mixed
     * @throws \RedisException
     */
    public function getCache(string $class, string $cacheKey)
    {
        $data = $this->cache->get($cacheKey);

        if ($data !== false) {
            return $this->serializer->deserialize($data, $class, 'json');
        }

        return false;
    }

    public function forget(string $cacheKey): void
    {
        $this->cache->unlink($cacheKey);
    }


    /**
     * @throws \RedisException
     */
    public function setCache(ResponseDtoInterface $data, string $cacheKey): bool
    {
        $serializeData = $this->serializer->serialize($data->getArray(), 'json');
        return $this->cache->set($cacheKey, $serializeData, $this->getTtl());
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     * @return CacheHelper
     */
    public function setTtl(int $ttl): CacheHelper
    {
        $this->ttl = $ttl;
        return $this;
    }


}
