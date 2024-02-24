<?php

namespace Sidalex\Gateway\Classes\Cache;

use Psr\SimpleCache\CacheInterface;

class MemoryCacheRequest implements CacheInterface, CacheCyclicValidateInterface
{

    private \stdClass $repository;

    public function __construct()
    {
        $this->repository = new \stdClass();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->repository->{$key}->value;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        if ($ttl instanceof \DateInterval) {
            $ttl = $ttl->s;
        }
        if (is_null($ttl)) {
            $ttl = 9999999;
        }
        $this->repository->{$key}->value = $value;
        $this->repository->{$key}->time = (time()) + $ttl;
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->repository->{$key});
        return true;
    }

    public function clear(): bool
    {
        unset($this->repository);
        $this->repository = new \stdClass();
        return true;
    }

    /**
     * @param iterable<string> $keys
     * @param mixed|null $default
     * @return iterable<mixed>
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            if (!empty($this->repository->{$keys})) {
                $result[$key] = $this->repository->{$keys};
            } else {
                $result[$key] = $default;
            }
        }
        return $result;
    }

    /**
     * @param iterable<mixed> $values
     * @param \DateInterval|int|null $ttl
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has(string $key): bool
    {
        if (property_exists($this->repository, $key)) {

            if ($this->repository->{$key}->time > time()) {
                return true;
            }
        }
        return false;
    }

    public function validateCache(): void
    {
        $now = time();
        /** @phpstan-ignore-next-line */
        foreach ($this->repository as $key => $value) {
            if ($this->repository->{$key}->time < $now) {
                unset($this->repository->{$key});
            }
        }
    }
}