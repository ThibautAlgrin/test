<?php

namespace Algrin\ArticleBundle\Controller;

use Algrin\ArticleBundle\Entity\Article;
use Algrin\ArticleBundle\Form\ArticleType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;


/**
 * Article controller.
 * @RouteResource("Article")
 */
class ArticleRESTController extends FOSRestController
{
    /**
     * Get a Article entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *   resource=true,
     *   description="Get a Article entity"
     * )
     * @param Request $request
     * @return Response
     * @Get("/article/{slug}", name="show_article", options={ "method_prefix" = false })
     * @Get("/articles/{id}", name="get_article_api", options={ "method_prefix" = false })
     */
    public function getAction(Request $request)
    {
        $term = $request->get('slug', null);
        if ($term == NULL) {
            $term = $request->get('id', null);
        }
        $entity = $this->getDoctrine()->getRepository('AlgrinArticleBundle:Article')->getByIdOrSlug($term);
        $delete = $this->get('form.factory')->createNamed(
            ''
            , 'form'
            , array(

            )
            , array(
                'method' => 'DELETE' ,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            )
        )->add('submit', 'submit', array('label' => 'Delete'));
        if ($request->get('_format', 'html') != 'html') {
            return $this->view($entity);
        }
        return $this->view(['article' => $entity, 'deleteForm' => $delete->createView()]);
    }

    /**
     * Get all Article entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @ApiDoc(
     *   resource=true,
     *   description="Get all Article entities"
     * )
     * @Get("/", name="homepage", options={ "method_prefix" = false })
     * @Get("/articles", name="get_articles_api", options={ "method_prefix" = false })
     * ---
     * @param ParamFetcherInterface $paramFetcher
     * @return Response
     *
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $order_by = $paramFetcher->get('order_by');

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AlgrinArticleBundle:Article')->findBy([], $order_by, $limit, $offset);
        if ($entities) {
            return $entities;
        }
        return $this->createNotFoundException();
    }

    /**
     * Create a Article entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *   resource=true,
     *   description="Create a Article entity"
     * )
     * ---
     * @param Request $request
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Article();
        $form = $this->createForm(ArticleType::class, $entity, array("method" => $request->getMethod()));
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            if ($request->get('_format') == 'html') {
                return $this->redirectToRoute('show_article', ['slug' => $entity->getSlug()]);
            } else {
                return $this->view($entity);
            }
        }

        return $this->view(array('errors' => $form->getErrors()));
    }

    /**
     * Delete a Article entity.
     *
     * @View(statusCode=204)
     * @ApiDoc(
     *   resource=true,
     *   description="Delete a Article entity"
     * )
     * ---
     * @param $entity
     * @param $request
     * @return Response
     */
    public function deleteAction(Article $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        if ($request->get('_format') == 'html') {
            return $this->redirectToRoute('homepage');
        } else {
            return $this->view([]);
        }
    }

    /**
     * @View()
     * @Get("/creer", name="new_article", options={ "method_prefix" = false })
     * ---
     * @return \FOS\RestBundle\View\View
     */
    public function newAction() {
        $form = $this->createForm(new ArticleType());

        return $this->view($form->createView());
    }
}
