<?php

namespace Iris;

use Bernard\Consumer;
use Bernard\Driver\PredisDriver;
use Bernard\Producer;
use Bernard\QueueFactory\InMemoryFactory;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Serializer;
use DB;
use Iris\Credentials\Permissions;
use Iris\Queue\ConsumingProducer;
use Iris\Queue\EventDispatcherFactory;
use Iris\Model\Service\LoggerFactory;
use Iris\Queue\Router as QueueRouter;
use Iris\Storage\LocalSubdirectoryAdapter;
use Predis\Client;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;

class ServiceProvider
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    public function __construct()
    {
        $this->container = Iris::$app->getContainer();
    }

    public function register()
    {
        /**
         * Logger
         */
        $this->container
            ->register('logger.factory', LoggerFactory::class)
            ->addArgument(Iris::$app->getLogDir())
            ->addArgument(Iris::$app->config('sentry.dsn'));

        /**
         * DB
         */
        $this->container
            ->register('db_access', DB::class)
            ->setFactory([DB::class, 'getInstance']);

        /**
         * Universal router for queues
         */
        $this->container
            ->register('queue.router', QueueRouter::class)
            ->addArgument(new Reference('loader'));

        /**
         * Queue Event Dispatcher
         */
        $this->container
            ->register('queue.event_dispatcher')
            ->setFactory([EventDispatcherFactory::class, 'create']);

        /**
         * Predis Client
         */
        $this->container
            ->register('queue.redis_client', Client::class)
            ->addArgument(null)
            ->addArgument([
                'prefix' => Iris::$app->config('queue.prefix'),
                'host' => Iris::$app->config('queue.host') ?: '127.0.0.1',
                'port' => Iris::$app->config('queue.port') ?: 6379,
                'password' => Iris::$app->config('queue.password') ?: null,
                'database' => Iris::$app->config('queue.database') ?: 0,
            ]);

        /**
         * Predis Driver for Bernard queues
         */
        $this->container
            ->register('queue.driver', PredisDriver::class)
            ->addArgument(new Reference('queue.redis_client'));

        /**
         * Потребитель сообщений из очереди
         */
        $this->container
            ->register('queue.consumer', Consumer::class)
            ->addArgument(new Reference('queue.router'))
            ->addArgument(new Reference('queue.event_dispatcher'));

        /**
         * Queue Serializer
         */
        $this->container
            ->register('queue.serializer', Serializer::class);

        /**
         * In Memory Queue Factory
         */
        $this->container
            ->register('queue.factory', InMemoryFactory::class)
            ->addArgument(new Reference('queue.event_dispatcher'));

        /**
         * Поставщик-потребитель сообщений для очереди (синхронная обработка)
         */
        $this->container
            ->register('queue.producer', ConsumingProducer::class)
            ->addArgument(new Reference('queue.factory'))
            ->addArgument(new Reference('queue.event_dispatcher'))
            ->addArgument(new Reference('queue.consumer'));

//        /**
//         * Persistent Queue Factory
//         */
//        $this->container
//            ->register('queue.factory', PersistentFactory::class)
//            ->addArgument(new Reference('queue.driver'))
//            ->addArgument(new Reference('queue.serializer'));
//
//        /**
//         * Поставщик сообщений для очереди
//         */
//        $this->container
//            ->register('queue.producer', Producer::class)
//            ->addArgument(new Reference('queue.factory'))
//            ->addArgument(new Reference('queue.event_dispatcher'));

        /**
         * Запрос
         */
        $this->container
            ->register('http.request', Request::class)
            ->setFactory([Request::class, 'createFromGlobals']);
        /**
         * Полномочия
         */
        $this->container
            ->register('credentails.permissions', Permissions::class)
            ->addArgument(new Reference('db_access'));

        /**
         * Universal router for API
         */
        $this->container
            ->register('api.router', Api\Router::class)
            ->addArgument(new Reference('http.request'))
            ->addArgument(new Reference('loader'));

        /**
         * Universal router for custom pages
         */
        $this->container
            ->register('page.router', Page\Router::class)
            ->addArgument(new Reference('http.request'))
            ->addArgument(new Reference('loader'));

        /**
         * File storage adapter
         */
        $this->container
            ->register('storage.adapter', LocalSubdirectoryAdapter::class)
            ->addArgument(Iris::$app->getRootDir() . 'files/');

        /**
         * File storage
         */
        $this->container
            ->register('storage.service', Storage\Storage::class)
            ->addArgument(new Reference('storage.adapter'));
    }
}