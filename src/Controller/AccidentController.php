<?php

namespace App\Controller;

use App\Entity\Accident;
use App\Form\AccidentType;
use App\Repository\AccidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/accident')]
class AccidentController extends AbstractController
{
    #[Route('/', name: 'app_accident_index', methods: ['GET'])]
    public function index(AccidentRepository $accidentRepository): Response
    {
        
        return $this->render('accident/index.html.twig', [
            'accidents' => $accidentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_accident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccidentRepository $accidentRepository): Response
    {
        $accident = new Accident();
        $form = $this->createForm(AccidentType::class, $accident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accidentRepository->save($accident, true);

            return $this->redirectToRoute('app_accident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('accident/new.html.twig', [
            'accident' => $accident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accident_show', methods: ['GET'])]
    public function show(Accident $accident): Response
    {
        return $this->render('accident/show.html.twig', [
            'accident' => $accident,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_accident_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accident $accident, AccidentRepository $accidentRepository): Response
    {
        $form = $this->createForm(AccidentType::class, $accident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accidentRepository->save($accident, true);

            return $this->redirectToRoute('app_accident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('accident/edit.html.twig', [
            'accident' => $accident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accident_delete', methods: ['POST'])]
    public function delete(Request $request, Accident $accident, AccidentRepository $accidentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accident->getId(), $request->request->get('_token'))) {
            $accidentRepository->remove($accident, true);
        }

        return $this->redirectToRoute('app_accident_index', [], Response::HTTP_SEE_OTHER);
    }
}
