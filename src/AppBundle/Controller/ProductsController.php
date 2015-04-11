<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends Controller
{

    /**
     * @Route("/produkty/{id}", name="products_list", defaults={"id" = false}, requirements={"id":"\d+"})
     */
    public function indexAction(Request $request, Category $category = null)
    {
        $getProductsQuery = $this->getDoctrine()
                ->getRepository('AppBundle:Product')
                ->getProductsQuery($category);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $getProductsQuery, $request->query->get('page', 1), 8
        );

        return $this->render('products/index.html.twig', [
                    'products' => $pagination,
        ]);
    }

//    /**
//     * @Route("/produkty/dodaj", name="products_add")
//     */
//    public function addAction(Request $request)
//    {
//        $form = $this->createForm(new ProductType());
//        $form->handleRequest($request);
//
//        return $this->render('products/add.html.twig', [
//                    'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/produkty/opis/{id}", name="products_desc")
     * @Template ("products/product_details.html.twig")
     */
    public function detailsAction(Product $product, Request $request)
    {
        $comment = new Comment();
        $comment->setProduct($product);

        $form = $this->createForm(new CommentType(), $comment);

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($comment);
            $em->flush();
            
            $this->addFlash('notice', "Komentarz został pomyślnie zapisany i oczekuje na weryfikację.");

            return $this->redirectToRoute('products_desc', ['id' => $product->getId()]);
        }

        return [
            'opis' => $product,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/szukaj", name="product_search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->query->get('query');
// validacja wartości przekazanej w parametrze
// $constraint = new NotBlank();
// $errors = $this->get('validator')->validate($query, $constraint);
// alternatywny sposób zapisu zapytania
// $products = $this->getDoctrine()
// ->getManager()
// ->createQueryBuilder()
// ->from('AppBundle:Product', 'p')
// ->select('p')
// ->where('p.name LIKE :query')
// ->setParameter('query', '%'.$query.'%')
// ->getQuery()
// ->getResult();
        $products = $this->getDoctrine()
                ->getRepository('AppBundle:Product')
                ->createQueryBuilder('p')
                ->select('p')
                ->where('p.name LIKE :query')
                ->orWhere('p.description LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();
        return $this->render('products/search.html.twig', [
                    'query' => $query,
                    'products' => $products
        ]);
    }

}
