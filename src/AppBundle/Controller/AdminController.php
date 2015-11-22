<?php

namespace AppBundle\Controller;

use AppBundle\Form\Dto\LogSearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    const PER_PAGE = 10;

    /**
     * @Route("/admin", name="admin")
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
            $searchLogQuery = $repository->getQueryBuilder();
        }

        $pagination = $paginator->paginate(
            $searchLogQuery,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', self::PER_PAGE)
        );

        return $this->render('admin/index.html.twig', [
            'pagination' => $pagination,
            'form'       => $form->createView(),
            'logSearch'  => $form->getData(),
        ]);
    }
}
