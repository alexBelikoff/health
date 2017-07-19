<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="health_measuring_type")
 */
class MeasuringType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100)
     */
    private $type;

    /**
     * One MeasuringType has Many Measuring.
     * @ORM\OneToMany(targetEntity="Measuring", mappedBy="type")
     */
    private $measuring;


    public function __construct() {
        $this->measuring = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MeasuringType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add measuring
     *
     * @param \AppBundle\Entity\Measuring $measuring
     *
     * @return MeasuringType
     */
    public function addMeasuring(\AppBundle\Entity\Measuring $measuring)
    {
        $this->measuring[] = $measuring;

        return $this;
    }

    /**
     * Remove measuring
     *
     * @param \AppBundle\Entity\Measuring $measuring
     */
    public function removeMeasuring(\AppBundle\Entity\Measuring $measuring)
    {
        $this->measuring->removeElement($measuring);
    }

    /**
     * Get measuring
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeasuring()
    {
        return $this->measuring;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MeasuringType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
