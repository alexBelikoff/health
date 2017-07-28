<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="health_measuring_fix")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeasuringRepository")
 */
class Measuring
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
     * @var \DateTime
     *
     * @ORM\Column(name="measuring_date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="measuring_value", type="string", length=32)
     */
    private $value;


    /**
     * Many Measuring have One Patient.
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="measuring")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;


    /**
     * Many Measuring have One MeasuringType.
     * @ORM\ManyToOne(targetEntity="MeasuringType", inversedBy="measuring")
     * @ORM\JoinColumn(name="measuring_type_id", referencedColumnName="id")
     */
    private $type;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Measuring
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Measuring
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return Measuring
     */
    public function setPatient(\AppBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \AppBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\MeasuringType $type
     *
     * @return Measuring
     */
    public function setType(\AppBundle\Entity\MeasuringType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\MeasuringType
     */
    public function getType()
    {
        return $this->type;
    }
}
