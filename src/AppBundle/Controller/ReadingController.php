<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReadingController extends Controller
{

    public function SummaryAction()
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository('AppBundle:BookReview')->getReadingSummary();

        $book = $em->getRepository('AppBundle:BookReview')->getBookByReviewId($res['last']['id']);

        return $this->render('AppBundle:Books:Summary.html.twig', array('res' => $res, 'date' => date('Y年m月d日'), 'book' => $book));
    }

    public function listAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $rpp = $this->container->getParameter('readings_per_page');
        $repo = $em->getRepository('AppBundle:BookHeadline');

        list($res, $totalcount) = $repo->getResultAndCount($page, $rpp);

        $paginator = new \AppBundle\Utility\Paginator($page, $totalcount, $rpp);
        $pagelist = $paginator->getPagesList();

        return $this->render('AppBundle:Books:ReadingList.html.twig', array('page' => $page, 'res' => $res, 'paginator' => $pagelist, 'cur' => $page, 'total' => $paginator->getTotalPages()));
    }

    public function reviewListAction($hid)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:BookReview');
        $res = $repo->getReviewsById($hid);

        return $this->render('AppBundle:Books:ReviewList.html.twig', array('res' => $res));
    }

}
