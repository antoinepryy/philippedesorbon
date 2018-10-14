<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GoogleController extends Controller
{
    public function robots($template = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render('robots.txt.twig', array(), $response);
    }

    public function sitemap(){
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        return $this->render('sitemap.xml.twig', array(), $response);
    }
}