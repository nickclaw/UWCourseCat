<?php

namespace CC\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Course
{
    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $curAbbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="longTitle", type="string", length=255)
     */
    private $longTitle;

    /**
     * @ORM\ManyToOne(targetEntity="Curriculum", inversedBy="courses")
     * @ORM\JoinColumn(name="curriculum_abbr", referencedColumnName="abbreviation", onDelete="CASCADE")
     **/
    private $curriculum;

    /**
     * @ORM\OneToMany(targetEntity="Section", mappedBy="course")
     **/
    private $sections;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="credit_control", type="string", length=255)
     */
    private $creditControl;

    /**
     * @var string
     *
     * @ORM\Column(name="grading_system", type="string", length=255)
     */
    private $gradingSystem;

    // private $firstTerm;

    // private $lastTerm;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_credits", type="integer")
     */
    private $maxCredits;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_term_credits", type="integer")
     */
    private $maxTermCredits;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_term_credits", type="integer")
     */
    private $minTermCredits;

    /**
     * @var array
     *
     * @ORM\Column(name="gen_ed", type="array")
     */
    private $genEd;

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
     * Set number
     *
     * @param integer $number
     * @return Course
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Course
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set longTitle
     *
     * @param string $longTitle
     * @return Course
     */
    public function setLongTitle($longTitle)
    {
        $this->longTitle = $longTitle;
    
        return $this;
    }

    /**
     * Get longTitle
     *
     * @return string 
     */
    public function getLongTitle()
    {
        return $this->longTitle;
    }

    /**
     * Set curriculum
     *
     * @param \CC\DataBundle\Entity\Curriculum $curriculum
     * @return Course
     */
    public function setCurriculum(\CC\DataBundle\Entity\Curriculum $curriculum = null)
    {
        $this->curriculum = $curriculum;
        $this->curAbbreviation = $curriculum->getAbbreviation();
    
        return $this;
    }

    /**
     * Get curriculum
     *
     * @return \CC\DataBundle\Entity\Curriculum 
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Course
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

    /**
     * Set creditControl
     *
     * @param string $creditControl
     * @return Course
     */
    public function setCreditControl($creditControl)
    {
        $this->creditControl = $creditControl;
    
        return $this;
    }

    /**
     * Get creditControl
     *
     * @return string 
     */
    public function getCreditControl()
    {
        return $this->creditControl;
    }

    /**
     * Set gradingSystem
     *
     * @param string $gradingSystem
     * @return Course
     */
    public function setGradingSystem($gradingSystem)
    {
        $this->gradingSystem = $gradingSystem;
    
        return $this;
    }

    /**
     * Get gradingSystem
     *
     * @return string 
     */
    public function getGradingSystem()
    {
        return $this->gradingSystem;
    }

    /**
     * Set maxCredits
     *
     * @param integer $maxCredits
     * @return Course
     */
    public function setMaxCredits($maxCredits)
    {
        $this->maxCredits = $maxCredits;
    
        return $this;
    }

    /**
     * Get maxCredits
     *
     * @return integer 
     */
    public function getMaxCredits()
    {
        return $this->maxCredits;
    }

    /**
     * Set maxTermCredits
     *
     * @param integer $maxTermCredits
     * @return Course
     */
    public function setMaxTermCredits($maxTermCredits)
    {
        $this->maxTermCredits = $maxTermCredits;
    
        return $this;
    }

    /**
     * Get maxTermCredits
     *
     * @return integer 
     */
    public function getMaxTermCredits()
    {
        return $this->maxTermCredits;
    }

    /**
     * Set minTermCredit
     *
     * @param integer $minTermCredit
     * @return Course
     */
    public function setMinTermCredits($minTermCredits)
    {
        $this->minTermCredits = $minTermCredits;
    
        return $this;
    }

    /**
     * Get minTermCredit
     *
     * @return integer 
     */
    public function getMinTermCredits()
    {
        return $this->minTermCredits;
    }

    /**
     * Set genEd
     *
     * @param array $genEd
     * @return Course
     */
    public function setGenEd($genEd)
    {
        $this->genEd = $genEd;
    
        return $this;
    }

    /**
     * Get genEd
     *
     * @return array 
     */
    public function getGenEd()
    {
        return $this->genEd;
    }

    /**
     * Set curAbbreviation
     *
     * @param string $curAbbreviation
     * @return Course
     */
    public function setCurAbbreviation($curAbbreviation)
    {
        $this->curAbbreviation = $curAbbreviation;
    
        return $this;
    }

    /**
     * Get curAbbreviation
     *
     * @return string 
     */
    public function getCurAbbreviation()
    {
        return $this->curAbbreviation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add sections
     *
     * @param \CC\DataBundle\Entity\Section $sections
     * @return Course
     */
    public function addSection(\CC\DataBundle\Entity\Section $sections)
    {
        $this->sections[] = $sections;
    
        return $this;
    }

    /**
     * Remove sections
     *
     * @param \CC\DataBundle\Entity\Section $sections
     */
    public function removeSection(\CC\DataBundle\Entity\Section $sections)
    {
        $this->sections->removeElement($sections);
    }

    /**
     * Get sections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSections()
    {
        return $this->sections;
    }
}