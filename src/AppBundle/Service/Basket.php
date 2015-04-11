<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Basket
 *
 * @author gosia
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Product;

class Basket
{

    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getProducts()
    {
        return $this->session->get('basket', array());
    }

    public function add(Product $product, $quantity = 1)
    {
        if (!$product->getAmount()) {
            throw new \Exception('brak produktu w magazynie', 300);
        }
        $products = $this->getProducts();
        if (!array_key_exists($product->getId(), $products)) {
            $products[$product->getId()] = array(
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => 0
            );
        }

        $products[$product->getId()]['quantity']+= $quantity;

        $this->session->set('basket', $products);

        return $this;
    }

    // TODO: przemyśleć czy nie przekazywać produktu zamiast $id
    // TODO: usuwać dla quantity = 0
    public function updateQuantity($id, $quantity)
    {
        $products = $this->getProducts();

        // aktualizujemy ilość produktów w koszyku
        $products[$id]['quantity'] = $quantity;

        // zapisujemy dane do sesji
        $this->session->set('basket', $products);
        return $this;
    }

    public function remove(Product $product)
    {
        $products = $this->getProducts();

        if (!array_key_exists($product->getId(), $products)) {
            throw new\Exception(sprintf('Produkt "%s" nie znajduje sie w Twoim koszyku', $product->getName()));
        }

        unset($products[$product->getId()]);
        $this->session->set('basket', $products);

        return $this;
    }

    public function clear()
    {
        $this->session->remove('basket');
        //$this->$session->set('basket', array());

        return $this;
    }

    public function countQuantity()
    {
        $iloscSzt = 0;
        foreach ($this->getProducts() as $calyRowek) {
            $iloscSzt += $calyRowek['quantity'];
        }
        return $iloscSzt;
    }

    public function countPrice()
    {
        $koszt = 0;
        foreach ($this->getProducts() as $calyrowek) {
            $koszt += $calyrowek['price'] * $calyrowek['quantity'];
        }
        return $koszt;
    }

}
