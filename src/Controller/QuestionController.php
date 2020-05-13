<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/liste", name="question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository,EntityManagerInterface $em,  Request $request): Response
    {
        $response = new Response();
        $response->headers->setCookie(Cookie::create('foo', 'bar'));
        
        // $paginator = new PaginatorInterface; 
        $paginator =  $this->get('knp_paginator');
        var_dump($paginator);
        // $properties = $paginator->paginate(
        //     $questionRepository->findAllpaginated(),
        //     $request->query->getInt('page', 1),
        //     12
        // );

        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findBy([], ["created_at" => "DESC" ]),
            
            // 'questions' => $properties,
            'action' => "list_question"

        ]);

    }

    /**
     * @Route("/", name="question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
            $this->addFlash('success', "Merci d'avoir participé, tu peut recommencer autant de fois que tu veut !");

            return $this->redirectToRoute('question_show', ["id" => $question->getId()]);
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'action' => "new_question"
            
        ]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     */
    public function show(Question $question, QuestionRepository $questionRepository): Response
    {
;
        return $this->render('question/show.html.twig', [
            'question' => $question,
            "next" => $questionRepository->find($question->getId() + 1),
            "prev" => $questionRepository->find($question->getId() - 1)
        ]);
    }

    // /**
    //  * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Question $question): Response
    // {
    //     $form = $this->createForm(QuestionType::class, $question);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('question_index');
    //     }

    //     return $this->render('question/edit.html.twig', [
    //         'question' => $question,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index');
    }
}
