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
     * @return Response
     *
     */
    public function getAction(Article $entity)
    {
        return $this->view($entity);
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

            return $this->view($entity);
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
     * @param Request $request
     * @param $entity
     * @return Response
     */
    public function deleteAction(Request $request, Article $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        return $this->view([]);
    }
}
