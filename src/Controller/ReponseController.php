<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuestionRepository;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="reponse_index", methods={"GET"})
     */
    public function index( QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{question_id}", name="reponse_new", methods={"GET","POST"})
     */
    public function new($question_id, Request $request, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($question_id);
        if($question->getReponse() != null ){
            $this->addFlash('warning', 'Vous avez déja répondu à cette question !');
            return $this->render('question/show.html.twig', [
                'question' => $question
            ]);
        }
        $reponse = new Reponse();
        $reponse->setQuestion($question);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponse);
            $question->setStatus(true);
            $entityManager->persist($question);
            $entityManager->flush();
            $this->addFlash('success', "Merci d'avoir répondu, vas vérifier si t'as d'autres questions à répondre !");
            return $this->redirectToRoute('reponse_index');
        }

        return $this->render('reponse/new.html.twig', [
            'question' => $question,
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

}
