<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository
{
    protected Model $model;
    protected string $cachePrefix;
    protected int $cacheTtl = 3600; // 1 hour default

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->cachePrefix = strtolower(class_basename($model)) . '_';
    }

    protected function getCacheKey(string $key): string
    {
        return $this->cachePrefix . $key;
    }

    protected function remember(string $key, $callback)
    {
        return Cache::remember(
            $this->getCacheKey($key),
            $this->cacheTtl,
            $callback
        );
    }

    protected function forget(string $key): bool
    {
        return Cache::forget($this->getCacheKey($key));
    }

    protected function flush(): bool
    {
        // Clear all cache keys for this model
        $keys = Cache::get($this->getCacheKey('keys'), []);
        foreach ($keys as $key) {
            Cache::forget($this->getCacheKey($key));
        }
        Cache::forget($this->getCacheKey('keys'));
        return true;
    }

    protected function addCacheKey(string $key): void
    {
        $keys = Cache::get($this->getCacheKey('keys'), []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put($this->getCacheKey('keys'), $keys, $this->cacheTtl);
        }
    }

    protected function logError(\Throwable $e, string $context = ''): void
    {
        Log::error($context . ': ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
    }

    protected function optimizeQuery($query)
    {
        return $query->select($this->model->getTable() . '.*');
    }
}
