<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of OrderController
 *
 * @author gosia
 */
class OrderController extends Controller
{

    /**
     * @Route("/zamowienia", name="order_list")
     * @Template()
     */
    public function listAction()
    {
        return array(
            'lista_zamowien' => $this->getDoctrine()->getRepository('AppBundle:Order')->findAll()
        );
    }
    
    /**
     * @Route("/zamowienie/{id}", name="order_details")
     * @Template("AppBundle:Order:order_details.html.twig")
     * @return type
     */
    public function detailsAction($id)
    {
        return array(
            'zamowienie' => $this->getDoctrine()->getRepository('AppBundle:Order')->find($id)
        );
    }

}
