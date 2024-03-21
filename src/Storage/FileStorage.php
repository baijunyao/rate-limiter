<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Storage;

/**
 * 文件存储
 */
class FileStorage extends Storage
{
    private string $dir;

    public function __construct(string $dir)
    {
        $this->dir = rtrim($dir, '/') . '/';

        if ((is_dir($this->dir) === false) && mkdir($this->dir, 0755, true) === false && is_dir($this->dir) === false) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->dir));
        }
    }

    public function get(string $key): ?array
    {
        $filePath = $this->getFilePath($key);

        // 初始情况下文件可能不存在
        if (file_exists($filePath) === false) {
            return null;
        }

        return json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);
    }

    public function set(string $key, array $content): void
    {
        file_put_contents($this->getFilePath($key), json_encode($content, JSON_THROW_ON_ERROR));
    }

    /**
     * 为 key 生成唯一文件路径
     */
    private function getFilePath(string $key): string
    {
        return $this->dir . $this->formatKey($key);
    }
}
