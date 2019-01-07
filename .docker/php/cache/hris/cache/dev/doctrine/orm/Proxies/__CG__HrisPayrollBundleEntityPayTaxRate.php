<?php

namespace Proxies\__CG__\Hris\PayrollBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class PayTaxRate extends \Hris\PayrollBundle\Entity\PayTaxRate implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', 'bracket', 'amount_from', 'amount_to', 'amount_tax', 'percent_of_excess', 'status', 'period', 'id'];
        }

        return ['__isInitialized__', 'bracket', 'amount_from', 'amount_to', 'amount_tax', 'percent_of_excess', 'status', 'period', 'id'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (PayTaxRate $proxy) {
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
    public function setBracket($bracket)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBracket', [$bracket]);

        return parent::setBracket($bracket);
    }

    /**
     * {@inheritDoc}
     */
    public function getBracket()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBracket', []);

        return parent::getBracket();
    }

    /**
     * {@inheritDoc}
     */
    public function setMinimum($amt_from)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMinimum', [$amt_from]);

        return parent::setMinimum($amt_from);
    }

    /**
     * {@inheritDoc}
     */
    public function getMinimum()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMinimum', []);

        return parent::getMinimum();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaximum($amt_to)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaximum', [$amt_to]);

        return parent::setMaximum($amt_to);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximum()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaximum', []);

        return parent::getMaximum();
    }

    /**
     * {@inheritDoc}
     */
    public function setTax($amt_tax)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTax', [$amt_tax]);

        return parent::setTax($amt_tax);
    }

    /**
     * {@inheritDoc}
     */
    public function getTax()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTax', []);

        return parent::getTax();
    }

    /**
     * {@inheritDoc}
     */
    public function setExcess($excess)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setExcess', [$excess]);

        return parent::setExcess($excess);
    }

    /**
     * {@inheritDoc}
     */
    public function getExcess()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExcess', []);

        return parent::getExcess();
    }

    /**
     * {@inheritDoc}
     */
    public function setPeriod(\Hris\PayrollBundle\Entity\PayPeriod $period)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPeriod', [$period]);

        return parent::setPeriod($period);
    }

    /**
     * {@inheritDoc}
     */
    public function getPeriod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPeriod', []);

        return parent::getPeriod();
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxStatus(\Hris\PayrollBundle\Entity\PayTaxStatus $status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTaxStatus', [$status]);

        return parent::setTaxStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxStatus', []);

        return parent::getTaxStatus();
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

}
