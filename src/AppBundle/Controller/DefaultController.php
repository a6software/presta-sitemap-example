<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    // this is purely for reference, these routes will not work without further work, but are good enough to
    // see this bundle in action

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/posts", name="posts_list")
     */
    public function postListAction()
    {
        return $this->render('default/post_list.html.twig');
    }

    /**
     * @Route("/posts/{id}", name="posts_show")
     */
    public function postShowAction($id)
    {
        /** some stuff here to find a specific post */
        return $this->render('default/post_show.html.twig');
    }

    /**
     * @Route("/posts/{id}/comments", name="posts_comments")
     */
    public function commentListAction($id)
    {
        /** some stuff here to find all comments for a given post */
        return $this->render('default/comment_list.html.twig');
    }
}
