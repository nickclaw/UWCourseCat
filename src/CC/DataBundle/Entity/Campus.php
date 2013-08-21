<?php

namespace CC\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campus
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Campus
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="shortName", type="string", length=255)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $shortName;

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
     * @ORM\ManyToOne(targetEntity="Term", inversedBy="campuses")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="term_year", referencedColumnName="year", onDelete="CASCADE"),
     *      @ORM\JoinColumn(name="term_quarter", referencedColumnName="quarter", onDelete="CASCADE")
     * });
     **/
    private $term;

    /**
     * @ORM\OneToMany(targetEntity="College", mappedBy="campus")
     **/
    private $colleges;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->colleges = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return Campus
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
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return Campus
     */
    public function setFulLName($fullName)
    {
        $this->fullName = $fullName;
    
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Campus
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
     * Add colleges
     *
     * @param \CC\DataBundle\Entity\College $colleges
     * @return Campus
     */
    public function addCollege(\CC\DataBundle\Entity\College $colleges)
    {
        $this->colleges[] = $colleges;
    
        return $this;
    }

    /**
     * Remove colleges
     *
     * @param \CC\DataBundle\Entity\College $colleges
     */
    public function removeCollege(\CC\DataBundle\Entity\College $colleges)
    {
        $this->colleges->removeElement($colleges);
    }

    /**
     * Get colleges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColleges()
    {
        return $this->colleges;
    }

    /**
     * Set term
     *
     * @param \CC\DataBundle\Entity\Term $term
     * @return Campus
     */
    public function setTerm(\CC\DataBundle\Entity\Term $term = null)
    {
        $this->term = $term;
    
        return $this;
    }

    /**
     * Get term
     *
     * @return \CC\DataBundle\Entity\Term 
     */
    public function getTerm()
    {
        return $this->term;
    }
}