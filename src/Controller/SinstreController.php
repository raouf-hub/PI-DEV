<?php

namespace App\Controller;

use App\Entity\Sinstre;
use App\Form\SinstreType;
use App\Repository\SinstreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf as Dompdf;
use Dompdf\Options;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Knp\Component\Pager\PaginatorInterface;




#[Route('/sinstre')]
class SinstreController extends AbstractController
{
    
    #[Route('/', name: 'app_sinstre_index', methods: ['GET'])]
    public function index(SinstreRepository $sinstreRepository,PaginatorInterface $paginator, Request $request): Response
    {

        $sinstre = $sinstreRepository->findAll();

        $sinstre = $paginator->paginate(
            $sinstre, /* query NOT result */
            $request->query->getInt('page', 1),
            3   
        );
                 
       
        return $this->render('sinstre/index.html.twig', [
            'sinstres' =>  $sinstre,

        
        ]);
    }

  
   
  
    
    #[Route('/new', name: 'app_sinstre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SinstreRepository $sinstreRepository,NotifierInterface $notifier): Response
    {
        $em=$this->getDoctrine()->getManager();
        $sinstre = new Sinstre();
        $form = $this->createForm(SinstreType::class, $sinstre);
        $form->handleRequest($request);
        
            

        if ($form->isSubmitted() && $form->isValid()) {
            $sinstreRepository->save($sinstre, true);
            $notifier->send(new Notification('A new sinstre is requested', ['browser']));

            return $this->redirectToRoute(
                'app_sinstre_new',
                [],
                Response::HTTP_SEE_OTHER
            );
            $em->persist($sinstre);
            $em->flush();
            
       

            return $this->redirectToRoute('app_sinstre_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('sinstre/new.html.twig', [
            'sinstre' => $sinstre,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}', name: 'app_sinstre_show', methods: ['GET'])]
    public function show(Sinstre $sinstre): Response
    {
        return $this->render('sinstre/show.html.twig', [
            'sinstre' => $sinstre,


        ]);
    }

   

    #[Route('/{id}/edit', name: 'app_sinstre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sinstre $sinstre, SinstreRepository $sinstreRepository): Response
    {
        $form = $this->createForm(SinstreType::class, $sinstre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sinstreRepository->save($sinstre, true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_sinstre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sinstre/edit.html.twig', [
            'sinstre' => $sinstre,
            'form' => $form,
        ]);
    }
   
    



    #[Route('/sinstre/download', name: 'app_sinstre_download', methods: ['GET'])]
    public function download()
    {
       // Configure Dompdf according to your needs
       $pdfOptions=new Options();

       $pdfOptions->set('defaultfont','Arial');
       $pdfOptions->setIsRemoteEnabled(true);
       $dompdf = new Dompdf( $pdfOptions);
       $context = stream_context_create([
           'ssl'=> [
               'verify_peer'=>  FALSE,
               'verify_peer_name'=> FALSE,
               'allow_self_signed'=> TRUE,

       ]
           ]);
           $dompdf->setHttpcontext($context);
           $html= $this->renderView('sinstre/pdf.html.twig');
           $dompdf->loadhtml($html);
           $dompdf->setPaper('A4','portrait');
           $dompdf->render();
           $fichier='sinstre-data'.'pdf';

           $dompdf->stream($fichier,[
           'Attachment'=>  true
           
           
           ]);




           return new response();
            
        
    }


    #[Route('/{id}', name: 'app_sinstre_delete', methods: ['POST'])]
    public function delete(Request $request, Sinstre $sinstre, SinstreRepository $sinstreRepository, $id): Response
    {
        $sinstre = $this->getDoctrine()->getRepository(Sinstre::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sinstre);
            $entityManager->flush();

        

        return $this->redirectToRoute('app_sinstre_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/sinstre/Recherche', name: 'app_sinstre_recherche', methods: ['GET'])]
    public function recherchenum(Request $request, CarteRepository $repository)
    {
        // Trouver tous les articles
        $sinstre= $repository->findAll();

        //recherche
        $searchForm = $this->createForm(RecherchenumType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $firstname= $searchForm['']->getData();
            $resultOfSearch = $repository->recherchenum($firstname);
            return $this->render('sinstre/recherchenum.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "recherchenum" => $searchForm->createView()));
        }
        return $this->render('sinstre/recherchenum.html.twig', array(
            "sinstre" => $sinstre,
            "recherchenum" => $searchForm->createView()));
    }


    


}
