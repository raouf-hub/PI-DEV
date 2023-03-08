<?php

namespace App\Controller\BackOffice;

use App\Entity\Remorquage;
use App\Entity\Service;
use App\Form\RemorquageType;
use App\Repository\RemorquageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf as Dompdf;
use Dompdf\Options;

#[Route('/adminremorquage')]
class RemorquageController extends AbstractController
{

    #[Route('/', name: 'admin_remorquage_index', methods: ['GET'])]
    public function index(RemorquageRepository $RemorquageRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $remorquages = $RemorquageRepository->findAll();

        $remorquages = $paginator->paginate(
            $remorquages, /* query NOT result */
            $request->query->getInt('page', 1),
            3   
        );

        $pieChart = new PieChart();

        $remorquagesData = $this->getDoctrine()->getRepository(Remorquage::class)->findAll();
        $servicesData = $this->getDoctrine()->getRepository(Service::class)->findAll();

        // dd($servicesData);

        $charts = array(['Remorquages', 'Number per Service']);
        // dd($charts);
        foreach ($servicesData as $s) {
            $servN = 0;
            foreach ($remorquagesData as $r) {
                if ($s == $r->getService()) {
                    $servN++;
                }
            }

            array_push($charts, [$s->getLibelleService(), $servN]);
        }


        // dd($charts);

        $pieChart->getData()->setArrayToDataTable(
            $charts
        );

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Remorquages Number per Service');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#07600');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);

        return $this->render('remorquage/index.html.twig', [
            'remorquages' => $remorquages,
            'piechart' => $pieChart
        ]);
    }

    #[Route('/new', name: 'admin_remorquage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RemorquageRepository $remorquageRepository): Response
    {
        $remorquage = new Remorquage();
        $form = $this->createForm(RemorquageType::class, $remorquage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remorquageRepository->add($remorquage, true);

            return $this->redirectToRoute('admin_remorquage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('remorquage/new.html.twig', [
            'remorquage' => $remorquage,
            'form' => $form,
        
        ]);
    }

    #[Route('/{id}', name: 'admin_remorquage_show', methods: ['GET'])]
    public function show(Remorquage $remorquage): Response
    {
        return $this->render('remorquage/show.html.twig', [
            'remorquage' => $remorquage,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_remorquage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Remorquage $remorquage, RemorquageRepository $remorquageRepository): Response
    {
        $form = $this->createForm(RemorquageType::class, $remorquage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remorquageRepository->add($remorquage, true);

            return $this->redirectToRoute('admin_remorquage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('remorquage/edit.html.twig', [
            'remorquage' => $remorquage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_remorquage_delete', methods: ['POST'])]
    public function delete(Request $request, Remorquage $remorquage, RemorquageRepository $remorquageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remorquage->getId(), $request->request->get('_token'))) {
            $remorquageRepository->remove($remorquage, true);
        }

      
        return $this->redirectToRoute('admin_remorquage_index', [], Response::HTTP_SEE_OTHER);
    }
    


}
