<?php

namespace App\Controller\Admin;

use App\Entity\Social\Comment;
use App\Entity\Social\Picture;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @var CommentRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminCommentController constructor.
     * @param CommentRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(CommentRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/comments", name="admin.comments.index")
     * @return Response
     */
    public function index(): Response
    {
        $comments = $this->repository->findAll();

        return $this->render("admin/comment/index.html.twig", [
            'comments' => $comments
        ]);
    }


    /**
     * @Route("/admin/comments/edit/{id}", name="admin.comments.delete", methods={"DELETE"})
     * @param Comment $comment
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Comment $comment, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
            $this->em->remove($comment);
            //$comment->setIsVisible(0);
            $this->em->flush();
        }
        return $this->redirectToRoute("admin.comments.index", [], 302);
    }

}