<?php

namespace Proxies\__CG__\Hris\AdminBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class JobLevel extends \Hris\AdminBundle\Entity\JobLevel implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', 'name', 'date_create', 'user_create'];
        }

        return ['__isInitialized__', 'id', 'name', 'date_create', 'user_create'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (JobLevel $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function toData()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toData', []);

        return parent::toData();
    }

    /**
     * {@inheritDoc}
     */
    public function getID()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getID', []);

        return parent::getID();
    }

    /**
     * {@inheritDoc}
     */
    public function dataHasGeneratedID(&$data)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'dataHasGeneratedID', [$data]);

        return parent::dataHasGeneratedID($data);
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', [$name]);

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function initHasName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'initHasName', []);

        return parent::initHasName();
    }

    /**
     * {@inheritDoc}
     */
    public function dataHasName(&$data)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'dataHasName', [$data]);

        return parent::dataHasName($data);
    }

    /**
     * {@inheritDoc}
     */
    public function initTrackCreate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'initTrackCreate', []);

        return parent::initTrackCreate();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateCreate(\DateTime $date)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateCreate', [$date]);

        return parent::setDateCreate($date);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateCreate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateCreate', []);

        return parent::getDateCreate();
    }

    /**
     * {@inheritDoc}
     */
    public function getDateCreateFormatted()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateCreateFormatted', []);

        return parent::getDateCreateFormatted();
    }

    /**
     * {@inheritDoc}
     */
    public function setUserCreate(\Quadrant\UserBundle\Entity\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUserCreate', [$user]);

        return parent::setUserCreate($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserCreate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUserCreate', []);

        return parent::getUserCreate();
    }

}
