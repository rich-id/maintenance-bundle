<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Driver;

use Lexik\Bundle\MaintenanceBundle\Drivers\AbstractDriver;
use Lexik\Bundle\MaintenanceBundle\Drivers\DriverTtlInterface;
use Symfony\Component\Cache\Traits\RedisTrait;

class RedisDriver extends AbstractDriver implements DriverTtlInterface
{
    use RedisTrait;

    const VALUE_TO_STORE = 'maintenance';

    /** @param array<string, mixed> $options */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!isset($options['key_name'])) {
            throw new \InvalidArgumentException('$options[\'key_name\'] must be defined if Driver Redis configuration is used');
        }

        if (!isset($options['redis_dsn'])) {
            throw new \InvalidArgumentException('$options[\'redis_dsn\'] must be defined if Driver Redis configuration is used');
        }

        if (isset($options['ttl']) && !\is_int($options['ttl'])) {
            throw new \InvalidArgumentException('$options[\'ttl\'] should be an integer if Driver Redis configuration is used');
        }

        $this->redis = self::createConnection($options['redis_dsn']);
    }

    protected function createLock()
    {
        if (!isset($this->options['ttl']) || $this->options['ttl'] <= 0) {
            return $this->redis->set($this->options['key_name'], self::VALUE_TO_STORE);
        }

        return $this->redis->setex($this->options['key_name'], $this->options['ttl'], self::VALUE_TO_STORE);
    }

    protected function createUnlock()
    {
        return $this->redis->del($this->options['key_name']) > 0;
    }

    public function isExists()
    {
        return $this->redis->exists($this->options['key_name']) > 0;
    }

    public function getMessageLock($resultTest)
    {
        $key = $resultTest ? 'lexik_maintenance.success_lock_redis' : 'lexik_maintenance.not_success_lock';

        return $this->translator->trans($key, [], 'maintenance');
    }

    public function getMessageUnlock($resultTest)
    {
        $key = $resultTest ? 'lexik_maintenance.success_unlock' : 'lexik_maintenance.not_success_unlock';

        return $this->translator->trans($key, [], 'maintenance');
    }

    public function setTtl($value): void
    {
        $this->options['ttl'] = $value;
    }

    public function getTtl()
    {
        return $this->options['ttl'];
    }

    public function hasTtl()
    {
        return isset($this->options['ttl']);
    }
}
