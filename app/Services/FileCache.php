<?php

namespace Godsu\Mvc\Services;

use Psr\SimpleCache\CacheInterface;

class FileCache implements CacheInterface
{
    private $cacheDir;

    public function __construct($cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?? __DIR__ . '/../../cache';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function get($key, $default = null)
    {
        $file = $this->getFileName($key);
        if (!file_exists($file)) {
            return $default;
        }

        $data = unserialize(file_get_contents($file));
        if ($data['expires'] < time()) {
            unlink($file);
            return $default;
        }

        return $data['value'];
    }

    public function set($key, $value, $ttl = null)
    {
        $file = $this->getFileName($key);
        $data = [
            'value' => $value,
            'expires' => time() + ($ttl ?? 3600)
        ];
        return file_put_contents($file, serialize($data)) !== false;
    }

    public function delete($key)
    {
        $file = $this->getFileName($key);
        if (file_exists($file)) {
            return unlink($file);
        }
        return true;
    }

    public function clear()
    {
        $files = glob($this->cacheDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    public function setMultiple($values, $ttl = null)
    {
        $result = true;
        foreach ($values as $key => $value) {
            $result = $result && $this->set($key, $value, $ttl);
        }
        return $result;
    }

    public function deleteMultiple($keys)
    {
        $result = true;
        foreach ($keys as $key) {
            $result = $result && $this->delete($key);
        }
        return $result;
    }

    public function has($key)
    {
        return $this->get($key) !== null;
    }

    private function getFileName($key)
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}