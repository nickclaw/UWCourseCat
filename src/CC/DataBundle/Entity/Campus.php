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
     * @ORM\ManyToOne(targetEntity="Term", inversedBy="campuses")
     * @ORM\JoinColumn(name="term_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $term;

    /**
     * @ORM\OneToMany(targetEntity="College", mappedBy="campus")
     **/
    private $colleges;


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
     * Set fullName
     *
     * @param string $fullName
     * @return Campus
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
     * Constructor
     */
    public function __construct()
    {
        $this->colleges = new \Doctrine\Common\Collections\ArrayCollection();
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