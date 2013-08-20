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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255)
     */
    private $number;

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
     * @ORM\JoinColumn(name="curriculum_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $curriculum;

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
     * @param string $number
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
     * @return string 
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
}