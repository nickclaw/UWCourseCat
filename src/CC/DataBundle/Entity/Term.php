<?php

namespace CC\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Term
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Term
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="quarter", type="string", length=255)
     */
    private $quarter;

    /**
     * @ORM\OneToMany(targetEntity="Campus", mappedBy="term")
     **/
    private $campuses;

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
     * Set year
     *
     * @param integer $year
     * @return Term
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
     * @return Term
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
     * Constructor
     */
    public function __construct()
    {
        $this->campuses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add campuses
     *
     * @param \CC\DataBundle\Entity\Campus $campuses
     * @return Term
     */
    public function addCampus(\CC\DataBundle\Entity\Campus $campuses)
    {
        $this->campuses[] = $campuses;
    
        return $this;
    }

    /**
     * Remove campuses
     *
     * @param \CC\DataBundle\Entity\Campus $campuses
     */
    public function removeCampus(\CC\DataBundle\Entity\Campus $campuses)
    {
        $this->campuses->removeElement($campuses);
    }

    /**
     * Get campuses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCampuses()
    {
        return $this->campuses;
    }

    /**
     * Add campuses
     *
     * @param \CC\DataBundle\Entity\Campus $campuses
     * @return Term
     */
    public function addCampuse(\CC\DataBundle\Entity\Campus $campuses)
    {
        $this->campuses[] = $campuses;
    
        return $this;
    }

    /**
     * Remove campuses
     *
     * @param \CC\DataBundle\Entity\Campus $campuses
     */
    public function removeCampuse(\CC\DataBundle\Entity\Campus $campuses)
    {
        $this->campuses->removeElement($campuses);
    }
}