<?php

namespace CC\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * College
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class College
{

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=7)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $abbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortName", type="string", length=255)
     */
    private $shortName;

    /**
     * @ORM\ManyToOne(targetEntity="Campus", inversedBy="colleges")
     * @ORM\JoinColumn(name="campus_name", referencedColumnName="shortName", onDelete="CASCADE")
     **/
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity="Curriculum", mappedBy="college")
     **/
    private $curricula;

    /**
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return College
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;
    
        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string 
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return College
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    
        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return College
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
     * Set shortName
     *
     * @param string $shortName
     * @return College
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    
        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return College
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set quarter
     *
     * @param string $quarter
     * @return College
     */
    public function setQuarter($quarter)
    {
        $this->quarter = $quarter;
    
        return $this;
    }

    /**
     * Get quarter
     *
     * @return string 
     */
    public function getQuarter()
    {
        return $this->quarter;
    }

    /**
     * Set campus
     *
     * @param \CC\DataBundle\Entity\Campus $campus
     * @return College
     */
    public function setCampus(\CC\DataBundle\Entity\Campus $campus = null)
    {
        $this->campus = $campus;
    
        return $this;
    }

    /**
     * Get campus
     *
     * @return \CC\DataBundle\Entity\Campus 
     */
    public function getCampus()
    {
        return $this->campus;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->curricula = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add curricula
     *
     * @param \CC\DataBundle\Entity\Curriculum $curricula
     * @return College
     */
    public function addCurricula(\CC\DataBundle\Entity\Curriculum $curricula)
    {
        $this->curricula[] = $curricula;
    
        return $this;
    }

    /**
     * Remove curricula
     *
     * @param \CC\DataBundle\Entity\Curriculum $curricula
     */
    public function removeCurricula(\CC\DataBundle\Entity\Curriculum $curricula)
    {
        $this->curricula->removeElement($curricula);
    }

    /**
     * Get curricula
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurricula()
    {
        return $this->curricula;
    }
}