<?php

namespace CC\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Section
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
     * @var string
     *
     * @ORM\Column(name="sectionId", type="string")
     */
    private $sectionID;

    /**
     * @var integer
     *
     * @ORM\Column(name="sln", type="integer")
     */
    private $sln;


    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="sections")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="crs_number", referencedColumnName="number", onDelete="CASCADE"),
     *      @ORM\JoinColumn(name="cur_abbr", referencedColumnName="abbreviation", onDelete="CASCADE")
     *  })
     **/
    private $course;

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
     * Set sln
     *
     * @param integer $sln
     * @return Section
     */
    public function setSln($sln)
    {
        $this->sln = $sln;
    
        return $this;
    }

    /**
     * Get sln
     *
     * @return integer 
     */
    public function getSln()
    {
        return $this->sln;
    }

    /**
     * Set sectionID
     *
     * @param string $sectionID
     * @return Section
     */
    public function setSectionID($sectionID)
    {
        $this->sectionID = $sectionID;
    
        return $this;
    }

    /**
     * Get sectionID
     *
     * @return string 
     */
    public function getSectionID()
    {
        return $this->sectionID;
    }

    /**
     * Set course
     *
     * @param \CC\DataBundle\Entity\Course $course
     * @return Section
     */
    public function setCourse(\CC\DataBundle\Entity\Course $course = null)
    {
        $this->course = $course;
    
        return $this;
    }

    /**
     * Get course
     *
     * @return \CC\DataBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }
}