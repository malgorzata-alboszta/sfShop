<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends Controller {

    /**
     * @Route("/koszyk", name="basket")
     * @Template() 
     */
    public function indexAction(Request $request) {
        $session = $request->getSession(); 
        $basket = $session->get('basket', array());

        $products = $this->getProducts();
        $productsInBasket = array();

        foreach ($basket as $id => $ilosc) {

            if (isset($products[$id]) && $ilosc > 0) { 
                $rzecz = $products[$id];  
                $rzecz['ilosc'] = $ilosc; 
                $productsInBasket[] = $rzecz;
            }
        }

        return array(
            'products_in_basket' => $productsInBasket,
        );
        
    }

    /**
     * @Route("/koszyk/{id}/dodaj", name ="basket_add")
     * @Template()
     */
    public function addAction($id, Request $request) {
        $session = $request->getSession();
        $basket = $session->get('basket', array());
        if (isset($basket[$id])) {

            $basket[$id] += 1; 
        } else {
            $products = $this->getProducts();
            if (isset($products[$id])) {
                $basket[$id] = 1;
            } else {
                $this->addFlash('warning', "Zglupiałes/as? Wstawiasz produkt o id ${id}");

                return $this->redirectToRoute('basket');
            }
        }

        $session->set('basket', $basket);
        $this->addFlash('notice', 'Produkt został dodany do koszyka'); 

        return $this->redirectToRoute('basket'); 
    }

    /**
     * @Route("/koszyk/{id}/usun", name="basket_remove")
     * @Template()
     */
    public function removeAction(Request $request, $id) {

        $session = $request->getSession();
        $basket = $session->get('basket', array());
        if (isset($basket[$id]) && $basket[$id] > 0) { 
            $basket[$id] -= 1;
        } else {
            $this->addFlash('warning', "Produktu o id: ${id} nie ma w koszyku"); 

            return $this->redirectToRoute('basket'); 
        }

        $session->set('basket', $basket);
        $this->addFlash('notice', "Produkt o id: ${id} został usuniety z koszyka");

        return $this->redirectToRoute('basket'); 
    }

    /**
     * @Route("/koszyk/{id}/zaktualizuj-ilosc/{quantity}")
     * @Template()
     */
    public function updateAction($id, $quantity) {
        return array(
                // ...
        );
    }

    /**
     * @Route("/koszyk/wyczysc", name="basket_clear")
     * @Template()
     */
    public function clearAction(Request $request) {

        $session = $request->getSession();

        $session->set('basket', array());
        $this->addFlash('notice', 'I pozamiatane :)'); 

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/koszyk/kup")
     * @Template()
     */
    public function buyAction() {
        return array(
                // ...
        );
    }

    private function getProducts() {
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
