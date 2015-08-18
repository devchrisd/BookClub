<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Repository\MemberRepository;

final class SearchController extends Controller
{
    const ACTION_SEARCH = 'search/search';

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @param Request $request
     * @param int $page
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return Response
     */
    public function searchAction(Request $request, $page=1)
    {
        $em = $this->getDoctrine()->getManager();
        $this->memberRepository = $em->getRepository('AppBundle:Members');

        $keyword = $request->query->get('search');
        $response = new Response();
        $resultsPerPage = 10;
        $first = ($page - 1) * $resultsPerPage;

        if (empty($keyword)) {
            $members = $this->memberRepository->findAll($first, $resultsPerPage);
            $membersCount = count($members);

            $response->headers->clearCookie('keyword');
        } else {
            $membersCount = $this->memberRepository->getSearchCount($keyword);

            $members = $this->memberRepository->search($keyword, $first, $resultsPerPage);

            $response->headers->setCookie(new Cookie('keyword', $keyword));
        }

        $pages = ceil($membersCount / $resultsPerPage);

        if ($page > $pages && $page != 1) {
            throw new NotFoundHttpException('Page does not exist.');
        }

        return $this->render(
            'AppBundle:member:search.html.twig',
            [
                'members' => $members,
                'results' => $membersCount,
                'results_per_page' => $resultsPerPage,
                'pages' => $pages,
                'current_page' => $page,
                'keyword' => $keyword,
            ],
            $response
        );
    }
}
