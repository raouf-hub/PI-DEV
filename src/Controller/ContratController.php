<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Flex\Options as FlexOptions;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContratRepository $contratRepository,SluggerInterface $slugger): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
        
        $test=$request->get('contrat');
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new DateTime();
            $currentDate->format('Y-m-d ');
            $contrat->setDateDeb($currentDate);
            // $currentDate = new DateTimeImmutable();
            //     $currentDate->format('Y-m-d H:i:s');
            //     $currentDate->add(new DateInterval('P1D'));
            //     dd($currentDate);
            $date = new DateTime();
             $date->add(new \DateInterval('P'.$test['periode']));
            $contrat->setDateFin($date);
// partie image
$image = $form->get('image')->getData();
            
            

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('contrat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $contrat->setimage($newFilename);
            }
            $contratRepository->save($contrat, true);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);




         
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/pdf', name: 'app_contrat_pdf')]
    

           
        public function generatePdfAction($id, ContratRepository $cr)
        {
            // Récupérez les informations de la commande correspondant à l'ID
            $contrat = $cr->findContratById($id);
            
            // Générez le contenu HTML à partir des informations de la commande
            $html = $this->renderView('contrat/listcontrat.html.twig', ['contrat' => $contrat]);
    
            // Générez le PDF à partir du contenu HTML
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();
            
            // Renvoyez le PDF en tant que réponse HTTP
            $response = new Response();
            $response->headers->set('Content-Type', 'application/pdf');
            $response->setContent($pdfContent);
            return $response;
        }
        // $pdfOptions = new Options();
        // $pdfOptions->set('defaultFont', 'Arial');
        
        // // Instantiate Dompdf with our options
        // $dompdf = new Dompdf($pdfOptions);
        // $contrat =$contratRepository->findAll();
 
       
        
        // // Retrieve the HTML generated in our twig file
        // $html = $this->renderView('contrat/listcontrat.html.twig', [
        //     'contrat' => $contrat,
            
        // ]);
        
        // // Load HTML to Dompdf
        // $dompdf->loadHtml($html);
        
        // // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        // $dompdf->setPaper('A4', 'portrait');

        // // Render the HTML as PDF
        // $dompdf->render();

        // // Output the generated PDF to Browser (force download)
        // $dompdf->stream("mypdf.pdf", [
        //     "Attachment" => true
        // ]);
     
    

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $contratRepository->remove($contrat, true);
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}
