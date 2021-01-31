<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticleFormType;
use App\Manager\ArticleManager;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request, Response
};
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/{page}", name="article", requirements={"page"="\d+"})
     * @param ArticleRepository $articles
     * @param $page
     * @return Response
     */
    public function index(ArticleRepository $articles, $page = 1): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articles->findArticles(
                $page,
                $limit = $this->getParameter("table.articles_per_page")
            ),
            'currentPage' => $page,
            'totalPages' => ceil($articles->count([]) / $limit)
        ]);
    }

    /**
     * @Route("/create", name="article_create")
     * @param Request $request
     * @param ArticleManager $am
     * @return Response
     */
    public function create(Request $request, ArticleManager $am): Response
    {
        $form = $this
            ->createForm(ArticleFormType::class, $article = new Article())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $am->save($article);
            $this->addFlash("success", "Article has been successfully created");

            return $this->redirectToRoute("article");
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
            'header'=>  "Create article"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit")
     * @param Article $article
     * @param ArticleManager $am
     * @param Request  $request
     * @return Response
     */
    public function edit(Article $article, ArticleManager $am, Request  $request): Response
    {
        $form = $this
            ->createForm(ArticleFormType::class, $article)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $am->update($article);
            $this->addFlash("success", "Article has been successfully edited");

            return $this->redirectToRoute("article");
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
            'header'=>  "Edit article"
        ]);
    }

    /**
     * @Route("/{id}/delete", name="article_delete")
     * @param ArticleManager $am
     * @param Article $article
     * @return Response
     */
    public function delete(ArticleManager $am, Article $article): Response
    {
        $am->delete($article);
        $this->addFlash("success", "Article has been successfully deleted");

        return $this->redirectToRoute("article");
    }
}
