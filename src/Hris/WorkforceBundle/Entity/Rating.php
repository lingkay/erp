<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;
use Catalyst\CoreBundle\Template\Entity\HasCode;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_rating_system")
 */
class Rating
{
    use HasGeneratedID;

    
    /** @ORM\Column(type="integer") */
    protected $range_start;

    /** @ORM\Column(type="integer") */
    protected $range_end;

    /** @ORM\Column(type="string") */
    protected $rating;

    /** @ORM\Column(type="text") */
    protected $description;


    /**
     * Set rangeStart
     *
     * @param integer $rangeStart
     *
     * @return Rating
     */
    public function setRangeStart($rangeStart)
    {
        $this->range_start = $rangeStart;

        return $this;
    }

    /**
     * Get rangeStart
     *
     * @return integer
     */
    public function getRangeStart()
    {
        return $this->range_start;
    }

    /**
     * Set rangeEnd
     *
     * @param integer $rangeEnd
     *
     * @return Rating
     */
    public function setRangeEnd($rangeEnd)
    {
        $this->range_end = $rangeEnd;

        return $this;
    }

    /**
     * Get rangeEnd
     *
     * @return integer
     */
    public function getRangeEnd()
    {
        return $this->range_end;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Rating
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
