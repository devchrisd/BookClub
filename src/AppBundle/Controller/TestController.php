<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function indexAction($foo)
    {
        $request = Request::createFromGlobals();

        // URI minus any query parameters
        $request->getPathInfo();

        // retrieve GET and POST
        $foo = $request->query->get('foo');

        return $this->render(
                'AppBundle:Test:index.html.twig',
                array('res' => $request, 'foo' => $foo)
            );
    }

    public function requestAction($foo)
    {
        $request = Request::createFromGlobals();

        // URI minus any query parameters
        $URI = $request->getPathInfo();

        // retrieve GET and POST
        $foo_g = $request->query->get('foo');
        $foo_p = $request->request->get('foo', 'default_for_post_bar');

        return $this->render(
            'AppBundle:Test:request.html.twig',
            array(
                'res' => $request,
                'uri' => $URI,
                'foo' => $foo,
                'get' => $foo_g,
                'post'=> $foo_p,
                'upload_file_foo' => $request->files->get('foo'),
                'cookie_session_id' => $request->cookies->get('PHPSESSID'),
                'header_host' => $request->headers->get('host'),
                'header_content_type' => $request->headers->get('content_type'),
                'method' => $request->getMethod(),
                'language' => implode(',', $request->getLanguages())
                )
            );
    }

    public function responseAction()
    {
        $response = new Response();
        $response->setContent('<html><body><h1>Hi ! Response from Test controller.</h1></body></html>');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}
