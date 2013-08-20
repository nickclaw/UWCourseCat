<?php

namespace CC\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Curriculum
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Curriculum
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
     * @ORM\Column(name="abbreviation", type="string", length=255)
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
     * @ORM\ManyToOne(targetEntity="College", inversedBy="curricula")
     * @ORM\JoinColumn(name="college_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $college;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="curriculum")
     **/
    private $courses;

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
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return Curriculum
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
     * @return Curriculum
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
     * @return Curriculum
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
     * Set college
     *
     * @param \CC\DataBundle\Entity\College $college
     * @return Curriculum
     */
    public function setCollege(\CC\DataBundle\Entity\College $college = null)
    {
        $this->college = $college;
    
        return $this;
    }

    /**
     * Get college
     *
     * @return \CC\DataBundle\Entity\College 
     */
    public function getCollege()
    {
        return $this->college;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add courses
     *
     * @param \CC\DataBundle\Entity\Course $courses
     * @return Curriculum
     */
    public function addCourse(\CC\DataBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;
    
        return $this;
    }

    /**
     * Remove courses
     *
     * @param \CC\DataBundle\Entity\Course $courses
     */
    public function removeCourse(\CC\DataBundle\Entity\Course $courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourses()
    {
        return $this->courses;
    }
}