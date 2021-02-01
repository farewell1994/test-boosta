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
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param TranslatorInterface $t
     * @return Response
     */
    public function create(Request $request, ArticleManager $am, TranslatorInterface $t): Response
    {
        $form = $this
            ->createForm(ArticleFormType::class, $article = new Article())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $am->save($article, $form->get('file')->getData());
            $this->addFlash("success", $t->trans("article.success_created"));

            return $this->redirectToRoute("article");
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
            'header' => $t->trans("article.create_header")
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit")
     * @param Article $article
     * @param ArticleManager $am
     * @param Request $request
     * @param TranslatorInterface $t
     * @return Response
     */
    public function edit(Article $article, ArticleManager $am, Request $request, TranslatorInterface $t): Response
    {
        $form = $this
            ->createForm(ArticleFormType::class, $article)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $am->update($article, $form->get('file')->getData());
            $this->addFlash("success", $t->trans("article.success_edited"));

            return $this->redirectToRoute("article");
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
            'header' => $t->trans("article.edit_header")
        ]);
    }

    /**
     * @Route("/{id}/delete", name="article_delete")
     * @param ArticleManager $am
     * @param Article $article
     * @param TranslatorInterface $t
     * @return Response
     */
    public function delete(ArticleManager $am, Article $article, TranslatorInterface $t): Response
    {
        $am->delete($article);
        $this->addFlash("success", $t->trans("article.success_deleted"));

        return $this->redirectToRoute("article");
    }
}
