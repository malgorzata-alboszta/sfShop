<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{

    /**
     * @Route("/list")
     * @Template()
     */
    public function listAction()
    {
        $qb = $this
                ->getDoctrine()
                ->getManager()
                ->createQueryBuilder();
        $qb
                ->select('c, p')
                ->from('AppBundle:Category', 'c')
                ->leftJoin('c.products', 'p');
//w encj category mamy atrybut products

        $categories = $qb
                ->getQuery()
                ->getResult();

//        $categories = $this->getDoctrine()
//                ->getRepository('AppBundle:Category')
//                ->findAll();

        return $this->render('Category/list.html.twig', [
                    'categories' => $categories,
        ]);
    }

}
