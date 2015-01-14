<?php

    namespace application\addons\annotations\behaviours;

    use \Yii;
    use \CException;
    use \Doctrine\Common\Cache\Cache as CacheProvider;
    use Doctrine\Common\Annotations\AnnotationRegistry;
    use Doctrine\Common\Annotations\AnnotationReader;
    use Doctrine\Common\Annotations\CachedReader;
    use Doctrine\Common\Annotations\IndexedReader;

    class ControllerAnnotations extends \CBehavior
    {

        /**
         * @var     \Doctrine\Common\Cache\Cache $cache
         * @access  protected
         */
        protected $cache;

        /**
         * @var     boolean     $production
         * @access  protected
         */
        protected $production = true;


        /**
         * Constructor
         *
         * @access  public
         * @return  void
         */
        public function __construct()
        {
            // Define annotation namespaces.
            AnnotationRegistry::registerAutoloadNamespace(
                '\\application\\annotations',
                Yii::getPathOfAlias('application') . '/..'
            );
        }


        /**
         * Get: Cache Provider
         *
         * @access  public
         * @return  \Doctrine\Common\Cache\Cache
         */
        public function getCache()
        {
            return $this->cache;
        }


        /**
         * Set: Cache Provider
         *
         * @access  public
         * @param   \Doctrine\Common\Cache\Cache $cache
         * @return  void
         */
        public function setCache(CacheProvider $cache)
        {
            if($this->cache !== null) {
                throw new CException(
                    Yii::t('application', 'Cannot set the cache proider for controller annotations as one has already been set.')
                );
            }
            $this->cache = $cache;
        }


        /**
         * Set: Production?
         *
         * @access public
         * @param boolean $production
         * @return void
         */
        public function setProduction($production)
        {
            $this->production = (bool) $production;
        }


        /**
         * Register Events
         *
         * @access  public
         * @return  array
         */
        public function events()
        {
            return array(
                'onBeforeControllerAction' => 'runAnnotations',
            );
        }


        /**
         * Run Annotations
         *
         * @access  public
         * @return  void
         */
        public function runAnnotations($event)
        {
            $reader = new AnnotationReader;
            // Has a cache provider been specified? If so, combine it with the reader.
            if($this->cache !== null) {
                $reader = new CachedReader(
                    $reader,
                    $this->cache,
                    !$this->production
                );
            }
            $reader = new IndexedReader($reader);

            $action = $event->sender->getController()->createAction(
                $event->sender->getController()->getAction()
            );
            if($action === null || $action instanceof \CViewAction) {
                return;
            }
            $reflection = $action instanceof \CInlineAction
                ? new \ReflectionMethod($action->getController(), 'action' . ucwords($action->id))
                : new \ReflectionClass($action);

            $annotations = $action instanceof \CInlineAction
                ? $reader->getMethodAnnotations($reflection)
                : $reader->getClassAnnotations($reflection);
        }

    }
