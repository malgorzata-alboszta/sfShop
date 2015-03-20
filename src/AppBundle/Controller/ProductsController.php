<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ProductsControler
 *
 * @author gosia
 */
class ProductsController extends Controller
{
    /** 
     * @Route("/produkty", name="products_list")
     */
 //put your code here
    
    public function indexAction()
    {
        
        return $this->render('products/index.html.twig', [
            'products'=> $this->getProducts(), //render-wypelnia template wartosciami z tablicy
        ]);
    }
    
    
    private function getProducts()
    {
        $file = file('product.txt'); 
        $products = array(); 
        foreach ($file as $p) { 
            $e = explode(':', trim($p)); //explode - przerabia tekst na tablice; trim - wycina spacje na brzegach
            $products[$e[0]] = array( 
                'id' => $e[0], 
                'name' => $e[1],
                'price' => $e[2],
                'desc' => $e[3],
            ); 
        }
        
        return $products;
    }
    
}
