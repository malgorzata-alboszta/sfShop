<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends Controller
{

    /**
     * @Route("/koszyk", name="basket_index")
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
            'service_basket' => $this->get('sf_basket'),
        );
    }

    /**
     * @Route("/koszyk/{id}/dodaj", name ="basket_add")
     * @Template()
     */
    public function addAction(Request $request, Product $product = null)
    {
        if (is_null($product)) {
            $this->addFlash('warning', 'Produkt, który probujesz dodac niezostał znaleziony');
            
            return $this->redirectToRoute('basket_index');
        }
        $basket = $this->get('sf_basket');
        try {
            $basket->add($product);
            $this->addFlash('notice', sprintf('Produkt "%s"został dodany', $product->getName()));
        } catch (Exception $ex) {
            $this->addFlash('warning', 'Jest jakis bład w czasie dodawania produktu:' . $ex->getMessage());
            return $this->redirect($request-> headers->get('referer'));
        }

        return $this->redirectToRoute('basket_index');

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
//                return $this->redirectToRoute('basket_index');
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
        $basket = $this->get('sf_basket');
        try {

            $basket->remove($product);
            $this->addFlash('notice', sprintf('Produkt został %s został usuniety z koszyka', $product->getName()));
        } catch (Exception $ex) {
            $this->addFlash('notice', $ex->getMessage());
        }
        return $this->redirectToRoute('basket_index');
        //usuniecie usuwa calkowicie!
//        $session = $request->getSession(); //$session = $request->get('session')
//        $basket = $session->get('basket', array());
//        if (!array_key_exists($id, $basket)) { //sprawdza czy taki klucz nieistnieje bo jest '!'
//        $this->addFlash('notice', 'Produkt nieistnieje'); 
//        return $this->redirectToRoute('basket_index');
//        }
//        unset($basket[$id]);
//        $session->set('basket',$basket);
//        $product = $this->getProduct($id);
//        $this->addFlash('notice','Produkt' . $product['name'] . ' został usuniety z koszyka');
//        return $this->redirectToRoute('basket_index');
    }

    /**
     * @Route("/koszyk/{id}/zaktualizuj-ilosc/{quantity}")
     * @Template()
     */
    public function updateAction($id, $quantity)
    {
        return array(
                // ...
        );
    }

    /**
     * @Route("/koszyk/wyczysc", name="basket_clear")
     * 
     */
    public function clearAction()
    {
        $this->get('sf_basket')
                ->clear();

        $this->addFlash('notice', 'I pozamiatane :)');
        return $this->redirectToRoute('basket_index');
    }

//    public function clearAction(Request $request) {
//
//        $session = $request->getSession();
//
//        $session->set('basket', array());
//        $this->addFlash('notice', 'I pozamiatane :)'); 
//
//        return $this->redirectToRoute('basket_index');
//    }

    /**
     * @Route("/koszyk/kup")
     * @Template()
     */
    public function buyAction()
    {
        return array(
                // ...
        );
    }

//    private function getProducts() {
//        $file = file('product.txt');
//        $products = array();
//        foreach ($file as $p) {
//            $e = explode(':', trim($p));
//            $products[$e[0]] = array(
//                'id' => $e[0],
//                'name' => $e[1],
//                'price' => $e[2],
//                'desc' => $e[3],
//            );
//        }
//
//        return $products;
//    }
//
//    private function getProduct($id)
//    {
//        $products = $this->getProducts();
//        return $products[$id];
//    }
    /**
     * @Template
     * @return array
     */
    public function listAction()
    {
        return array(
            'wiecejcokolwiek' => $this->get('sf_basket')->getProducts(),
            'iloscproduktow' => $this->get('sf_basket')->countQuantity(),
            'doZaplaty' => $this->get('sf_basket')->countPrice(),
        );
    }

    /**
     * @Route ("/zamow", name="basket_order")
     * 
     */
    public function orderAction()
    {
        $order = new Order();
        $order->setOrderDate(new DateTime());
        $order->setOrderCost($this->get('sf_basket')->countPrice());
        foreach ($this->get('sf_basket')->getProducts() as $basketProduct) {
            $DBProduct = $this->getDoctrine()->getRepository('AppBundle:Product')
                    ->find($basketProduct['id']);
            $order->addOrderProduct($DBProduct);
        }
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        $this->get('sf_basket')->clear();
        $this->addFlash('notice', 'Twoje zamówienie zostało złożone. Dziekujemy');
        return $this->redirectToRoute('basket_index');
    }

}
