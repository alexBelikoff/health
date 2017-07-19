<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="health_patient")
 */

class Patient
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
     * @ORM\Column(name="last_name", type="string", length=64)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=32)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="second_name", type="string", length=32, nullable=true)
     */
    private $secondName;


    /**
     * @var date
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;


    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="patient")
     */
    private $user;


    /**
     * One Patient has Many Measuring.
     * @ORM\OneToMany(targetEntity="Measuring", mappedBy="patient")
     */
    private $measuring;


    /**
     * Many Patient have Many Doctor.
     * @ORM\ManyToMany(targetEntity="Doctor", inversedBy="patients")
     * @ORM\JoinTable(name="patients_doctors")
     */
    private $doctors;


    public function __construct() {
        $this->measuring = new ArrayCollection();
        $this->doctors = new ArrayCollection();
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Patient
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Patient
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set secondName
     *
     * @param string $secondName
     *
     * @return Patient
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * Get secondName
     *
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Patient
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Patient
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add measuring
     *
     * @param \AppBundle\Entity\Measuring $measuring
     *
     * @return Patient
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
     * Add doctor
     *
     * @param \AppBundle\Entity\Doctor $doctor
     *
     * @return Patient
     */
    public function addDoctor(\AppBundle\Entity\Doctor $doctor)
    {
        $this->doctors[] = $doctor;

        return $this;
    }

    /**
     * Remove doctor
     *
     * @param \AppBundle\Entity\Doctor $doctor
     */
    public function removeDoctor(\AppBundle\Entity\Doctor $doctor)
    {
        $this->doctors->removeElement($doctor);
    }

    /**
     * Get doctors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoctors()
    {
        return $this->doctors;
    }
}
