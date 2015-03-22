<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;

class BasketController extends Controller {

    /**
     * @Route("/koszyk", name="basket")
     * @Template() 
     */
    public function indexAction(Request $request) 
    {
//        $session = $request->getSession(); 
//        $basket = $session->get('basket', array());
//
//        $products = $this->getProducts();
//        $productsInBasket = array();
//
//        foreach ($basket as $id => $ilosc) {
//
//            if (isset($products[$id]) && $ilosc > 0) { 
//                $rzecz = $products[$id];  
//                $rzecz['ilosc'] = $ilosc; 
//                $productsInBasket[] = $rzecz;
//            }
//        }
//
//        return array(
//            'products_in_basket' => $productsInBasket,
//        );
        return array(
            'basket' =>$this->get('basket'),
        );
    }

    /**
     * @Route("/koszyk/{id}/dodaj", name ="basket_add")
     * @Template()
     */
    public function addAction(Product $product = null)
    {
        if (is_null($product)){
        $this->addFlash('notice', 'Produkt, który prubujesz dodac niezostał znaleziony');
        return $this->redirectToRoute('basket');
        }
        $basket=$this->get('basket');
        $basket->add($product);
        
        $this->addFlash('notice', sprintf('Produkt "%s"został dodany', $product->getName()));
        return $this->redirectToRoute('basket');
  
//        $session = $request->getSession();
//        $basket = $session->get('basket', array());
//        if (isset($basket[$id])) {
//
//            $basket[$id] += 1; 
//        } else {
//            $products = $this->getProducts();
//            if (isset($products[$id])) {
//                $basket[$id] = 1;
//            } else {
//                $this->addFlash('warning', "Zglupiałes/as? Wstawiasz produkt o id ${id}");
//
//                return $this->redirectToRoute('basket');
//            }
//        }
//
//        $session->set('basket', $basket);
//        $this->addFlash('notice', 'Produkt został dodany do koszyka'); 


    }

    /**
     * @Route("/koszyk/{id}/usun", name="basket_remove")
     * @Template()
     */
    public function removeAction(Product $product)
     {
        $basket = $this->get('basket');
        try {
        
        $basket->remove($product);
        $this->addFlash('notice', sprintf('Produkt został %s został usuniety z koszyka', $product->getName()));
        } catch (\Exception $ex) {
            $this->addFlash('notice', $ex->getMessage());
        }
        return $this->redirectToRoute('basket');
        //usuniecie usuwa calkowicie!
//        $session = $request->getSession(); //$session = $request->get('session')
//        $basket = $session->get('basket', array());
//        if (!array_key_exists($id, $basket)) { //sprawdza czy taki klucz nieistnieje bo jest '!'
//        $this->addFlash('notice', 'Produkt nieistnieje'); 
//        return $this->redirectToRoute('basket');
//        }
//        unset($basket[$id]);
//        $session->set('basket',$basket);
//        $product = $this->getProduct($id);
//        $this->addFlash('notice','Produkt' . $product['name'] . ' został usuniety z koszyka');
//        return $this->redirectToRoute('basket');
            
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

    private function getProduct($id) 
            {
        $products = $this->getProducts();
        return $products[$id];
        }
    }
