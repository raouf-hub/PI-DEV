<?php

namespace App\Controller\FrontOffice;

use App\Entity\Remorquage;
use App\Form\RemorquageType;
use App\Repository\RemorquageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

#[Route('/remorquage')]
class RemorquageController extends AbstractController
{
    #[Route('/', name: 'app_remorquage_index', methods: ['GET'])]
    public function indexFront(
        RemorquageRepository $remorquageRepository
    ): Response {
        return $this->render('remorquage/front/index.html.twig', [
            'remorquages' => $remorquageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_remorquage_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        RemorquageRepository $remorquageRepository,
        NotifierInterface $notifier
    ): Response {
        $remorquage = new Remorquage();
        $form = $this->createForm(RemorquageType::class, $remorquage);
        $form->handleRequest($request);

        // $notifier->send(new Notification('A new remorquage is requested', ['browser']));

        if ($form->isSubmitted() && $form->isValid()) {
            $remorquageRepository->add($remorquage, true);
            $notifier->send(new Notification('A new remorquage is requested', ['browser']));

            return $this->redirectToRoute(
                'app_remorquage_new',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('remorquage/front/get-remorquage.html.twig', [
            'remorquage' => $remorquage,
            'form' => $form,
        ]);
    }
}
