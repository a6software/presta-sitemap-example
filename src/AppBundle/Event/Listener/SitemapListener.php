<?php

namespace AppBundle\Event\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Validator\Constraints\DateTime;

class SitemapListener implements SitemapListenerInterface
{
    private $event;
    private $router;
    private $entityManager;

    public function __construct(RouterInterface $router, EntityManager $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $this->event = $event;

        $section = $this->event->getSection();

        if (is_null($section) || $section == 'default') {

            //get absolute homepage url
            $url = $this->router->generate('homepage', array(), true);

            //add homepage url to the urlset named default
            $this->createSiteMapEntry($url, new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1);

            $posts = $this->entityManager->getRepository('AppBundle:Post')->findAll();
            foreach ($posts as $post) {
                $url = $this->router->generate('posts_show', array('id'=>$post->getId()), true);

                $this->createSiteMapEntry(
                    $url,
                    $post->getUpdatedAt(),
                    UrlConcrete::CHANGEFREQ_DAILY,
                    0.7
                );

                $comments = $this->entityManager->getRepository('AppBundle:Comment')->findBy(array(
                    'post' => $post->getId(),
                ));

                foreach ($comments as $comment) {
                    $url = $this->router->generate('posts_comments_show', array(
                        'postId' => $post->getId(),
                        'commentId'=>$comment->getId()
                    ), true);

                    $this->createSiteMapEntry(
                        $url,
                        $comment->getUpdatedAt(),
                        UrlConcrete::CHANGEFREQ_WEEKLY,
                        0.5
                    );
                }

            }
        }
    }

    private function createSiteMapEntry($url, $modifiedDate, $changeFrequency, $priority)
    {
        $this->event->getGenerator()->addUrl(
            new UrlConcrete(
                $url,
                $modifiedDate,
                $changeFrequency,
                $priority
            ),
            'default'
        );
    }
}