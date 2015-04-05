<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order
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
     * @var DateTime
     *
     * @ORM\Column(name="order_date", type="datetime")
     */
    private $orderDate;

    /**
     * @var float
     *
     * @ORM\Column(name="order_cost", type="float", scale=2)
     */
    private $orderCost;

    /**
     *
     * @var type 
     * @ORM\ManyToMany(targetEntity="Product")
     */
    private $orderProducts;

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
     * Set orderDate
     *
     * @param DateTime $orderDate
     * @return Orders
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return DateTime 
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set orderCost
     *
     * @param float $orderCost
     * @return Orders
     */
    public function setOrderCost($orderCost)
    {
        $this->orderCost = $orderCost;

        return $this;
    }

    /**
     * Get orderCost
     *
     * @return float 
     */
    public function getOrderCost()
    {
        return $this->orderCost;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
    }

    /**
     * Add orderProducts
     *
     * @param Product $orderProducts
     * @return Order
     */
    public function addOrderProduct(Product $orderProducts)
    {
        $this->orderProducts[] = $orderProducts;

        return $this;
    }

    /**
     * Remove orderProducts
     *
     * @param Product $orderProducts
     */
    public function removeOrderProduct(Product $orderProducts)
    {
        $this->orderProducts->removeElement($orderProducts);
    }

    /**
     * Get orderProducts
     *
     * @return Collection 
     */
    public function getOrderProducts()
    {
        return $this->orderProducts;
    }
}
