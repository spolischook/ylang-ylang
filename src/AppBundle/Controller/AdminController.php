<?php

namespace AppBundle\Controller;

use AppBundle\Form\Dto\LogSearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("", name="admin")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $repository = $this->get('app.repository.log');
        $paginator  = $this->get('knp_paginator');

        $form = $this->createForm('log_search_type', new LogSearch(), ['method' => 'GET']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $searchLogQuery = $repository->getLogsSearchQuery($form->getData());
        } else {
            $searchLogQuery = $repository->getQueryBuilder()->getQuery();
        }

        $pagination = $paginator->paginate(
            $searchLogQuery,
            $request->query->getInt('page', 1),
            $form->getData()->limit ?: 10
        );

        return $this->render('admin/index.html.twig', [
            'pagination' => $pagination,
            'form'       => $form->createView(),
            'logSearch'  => $form->getData(),
        ]);
    }
}
