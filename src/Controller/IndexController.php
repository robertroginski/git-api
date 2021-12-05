<?php

namespace App\Controller;

use App\Form\GitCompareType;
use App\Repository\GitRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @var GitRepositoryInterface
     */
    private $gitRepository;

    /**
     * IndexController constructor.
     * @param GitRepositoryInterface $gitRepository
     */
    public function __construct(GitRepositoryInterface $gitRepository)
    {
        $this->gitRepository = $gitRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $gitCompareForm = $this->createForm(GitCompareType::class);
        $gitCompareForm->handleRequest($request);

        $firstRepoData = [];
        $secondRepoData = [];

        if($gitCompareForm->isSubmitted() && $gitCompareForm->isValid())
        {
            $firstRepoData = $this->gitRepository->getStatsData($gitCompareForm->get('first')->getData());
            $secondRepoData = $this->gitRepository->getStatsData($gitCompareForm->get('second')->getData());
        }

        return $this->render('git/index.html.twig', [
            'gitCompareForm' => $gitCompareForm->createView(),
            'firstRepoData' => $firstRepoData,
            'secondRepoData' => $secondRepoData,
        ]);
    }
}
