<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TechnologyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class dashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function view(ProjectRepository $projectRepository, Request $request): Response
    {
        $searchForm = $this->createForm(SearchProjectType::class);
        $searchForm->handleRequest($request);

        if( $searchForm->isSubmitted() && $searchForm->isValid() ) {

            $search = $searchForm->get('title')->getData();
//            $program = $projectRepository->findBy(['title' => $search]);
            $program = $projectRepository->findTitleTag($search);
            if(empty($program)) {
                $this->addFlash('primary', 'No project fund');
                return $this->redirectToRoute('dashboard');
            }
            return $this->render('dashboard.html.twig', [
                'project' => $program,
                'form' => $searchForm->createView()]);
        }

        return $this->render('dashboard.html.twig', [
            'project' => $projectRepository->findAll(),
//            'project' => $projectRepository->findBy(['id' =>  ]),
            'form' => $searchForm->createView()

        ]);
    }
}
