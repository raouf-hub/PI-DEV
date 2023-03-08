<?php

namespace App\Controller;

use App\Entity\Constat;
use App\Form\ConstatType;
use App\Repository\ConstatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/admin/constat')]
class ConstController extends AbstractController
{
    #[Route('/', name: 'admin_constat_index', methods: ['GET'])]
    public function index(ConstatRepository $constatRepository,PaginatorInterface $paginator, Request $request): Response
    {
     
        $constat = $constatRepository->findAll();

        $constat= $paginator->paginate(
            $constat, /* query NOT result */
            $request->query->getInt('page', 1),
            3   
        );
        return $this->render('constat/index.html.twig', [
            'constats' =>   $constat,
        ]);
    }

    #[Route('/new', name: 'admin_constat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConstatRepository $constatRepository,NotifierInterface $notifier): Response
    {
        $constat= new Constat();
        $form = $this->createForm(ConstatType::class, $constat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $constatRepository->save($constat, true);
            $notifier->send(new Notification('A new constat is requested', ['browser']));

            return $this->redirectToRoute(
                'admin_constat_new',
                [],
                Response::HTTP_SEE_OTHER
            );

            return $this->redirectToRoute('admin_constat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('constat/new.html.twig', [
            'constat' => $constat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_constat_show', methods: ['GET'])]
    public function show(Constat $constat): Response
    {
        return $this->render('constat/show.html.twig', [
            'constat' => $constat,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_constat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Constat $constat, ConstatRepository $constatRepository): Response
    {
        $form = $this->createForm(ConstatType::class, $constat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $constatRepository->add($constat, true);

            return $this->redirectToRoute('admin_constat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('constat/edit.html.twig', [
            'constat' => $constat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_constat_delete', methods: ['POST'])]
    public function delete(Request $request, Constat $constat, ConstatRepository $constatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$constat->getId(), $request->request->get('_token'))) {
            $constatRepository->remove($constat, true);
        }

        return $this->redirectToRoute('admin_constat_index', [], Response::HTTP_SEE_OTHER);
    }
}
