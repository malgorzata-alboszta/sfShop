<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Category;
/**
 * Description of ProductsControler
 *
 * @author gosia
 */
class ProductsController extends Controller
{
    /** 
     * @Route("/produkty/{id}", name="products_list", defaults={"id" = false})
     */
 //put your code here
    
    public function indexAction(Category $category=null)
    {
       
        if ($category){
            $products = $this->getDoctrine()
                    ->getRepository('AppBundle:Product')
                    ->findBy([
                        'category' => $category,
                    ]);
        } else {
        $products = $this->getDoctrine()
        -> getRepository('AppBundle:Product')
        ->findAll();


        }
        return $this->render('products/index.html.twig', [
            'products'=> $products, 
        ]);
    }
}