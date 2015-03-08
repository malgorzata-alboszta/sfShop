<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends Controller
{
    /**
     * @Route("/koszyk", name="basket")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $session =$request->getSession();
        
        $basket = $session->get('basket', array());
        $products = $this->getProducts();
        
        $productsInBasket = array();
        foreach ($basket as $id=> $b) {
            $productsInBasket[]= $products[$id];
        }
        
        return array(
                'products_in_basket'=>$productsInBasket,
            );    }

    /**
     * @Route("/koszyk/{id}/dodaj", name ="basket_add")
     * @Template()
     */
    public function addAction($id, Request $request)
    {
        $session = $request->getSession();
        
        $basket = $session->get('basket', array());
        
        $basket[$id] = 1; //w tej chwili mozemy dodac tylko produkt w ilosc 1. PD- zmienic to
        
        $session->set('basket', $basket); //zapis koszyku w sesji
        $this->addFlash('notice', 'Produkt został dodany do koszyka'); //dajemy wiadomosc userowi
        return $this->redirectToRoute('basket'); //zwrocenie odpowiedzi przekierowania
        
            
    }

    /**
     * @Route("/koszyk/{id}/usun")
     * @Template()
     */
    public function removeAction($id)
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/koszyk/{id}/zaktualizuj-ilosc/{quantity}")
     * @Template()
     */
    public function updateAction($id, $quantity)
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/koszyk/wyczysc")
     * @Template()
     */
    public function clearAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/koszyk/kup")
     * @Template()
     */
    public function buyAction()
    {
        return array(
                // ...
            );    }
            
             private function getProducts()
    {
        $file = file('product.txt'); 
        $products = array(); 
        foreach ($file as $p) { 
            $e = explode(':', trim($p)); 
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
