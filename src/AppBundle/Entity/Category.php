<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection; 
//biblioteka pozwalajaca operowac na tablicach w formie oiektowej

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    //Product to Product.php, a 'category' private $category z Product.php czyli cecha klasy Product
    //mappedBy jest po stronie 'Jeden' która łaczy sie z 'wiele' lub 'jeden'. 
    //to co jest w mapped by to nazwa pola (cechy) z tej drugiej encji (target entity) pod która jest widoczna nasza encja Category w której jestesmy
    /**
     *
     * @ORM\OneToMany (targetEntity="Product", mappedBy="category") 
     */
    private $products;

    public function __construct() 
//wywoływany przy konstrukcji new Category(). Ustawia domysna wartosc dla cechy products
    {
        $this->products = new ArrayCollection();
        //zamienik dla zwyklej tablicy array. obiekt tablicowy, dostep do szeregu metod pomocniczych, dodawani i usuwanie szybciej
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
     * Set name
     *
     * @param string $name
     * @return Category
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
     * Add products
     *
     * @param \AppBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Product $products)
    {
        $this->products[] = $products; //dodaj nowy element do kolekcji (array collection)
//[] pod tym znaczkiem jest add.
        return $this;
    }

    /**
     * Remove products
     *
     * @param \AppBundle\Entity\Product $products
     */
    public function removeProduct(\AppBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
    
    public function __toString()
    {
        return $this->name;
    }
}

