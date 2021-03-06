<?php

namespace Acme\Bundle\PeriodBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\Bundle\PeriodBundle\Entity\Creneau;

/**
 * Period
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\Bundle\PeriodBundle\Entity\PeriodRepository")
 */
class Period
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
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="time")
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
   	* @ORM\ManyToOne(targetEntity="Sylius\Component\Shipping\Model\ShippingCategory")
   	* @ORM\JoinColumn(nullable=false)
   	*/
    protected $category;
	
	/**
   	* @ORM\ManyToOne(targetEntity="Acme\Bundle\PeriodBundle\Entity\ShippingMethod")
   	* @ORM\JoinColumn(nullable=true)
   	*/
    protected $method;

    /**
   	 * @ORM\OneToMany(targetEntity="Acme\Bundle\PeriodBundle\Entity\Creneau", mappedBy="period")
   	 */
    protected $creneaus;
    
	/**
     * Constructor.
     */
    public function __construct()
    {

        $this->creneaus = new ArrayCollection();
    }
    
	public function addCreneau(Creneau $creneau)
	{
	    $this->creneaus[] = $creneau;
	
	    return $this;
	}

	public function removeCreneau(Creneau $creneau)
	{
	   	$this->creneaus->removeElement($creneau);
	}
	
	public function getCreneaus() 
	{
	    return $this->creneaus;
	}
	

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->name;
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
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Period
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Period
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Period
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
     * Set shipping_category
     *
     * @param Sylius\Component\Shipping\Model\ShippingCategory $shippingCategory
     * @return Period
     */
    public function setCategory(\Sylius\Component\Shipping\Model\ShippingCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get shipping_category
     *
     * @return \Sylius\Bundle\ShippingBundle\Model\ShippingCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
	
	/**
     * Set shipping_method
     *
     * @param \Acme\Bundle\PeriodBundle\Entity\ShippingMethod
     * @return method
     */
    public function setMethod(\Acme\Bundle\PeriodBundle\Entity\ShippingMethod $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get shipping_method
     *
     * @return \Acme\Bundle\PeriodBundle\Entity\ShippingMethod
     */
    public function getMethod()
    {
        return $this->method;
    }
}
