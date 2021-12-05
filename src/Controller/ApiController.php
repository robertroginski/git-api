<?php

namespace App\Controller;

use App\Repository\GitRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{

    /**
     * @var GitRepositoryInterface
     */
    private $gitRepository;


    /**
     * ApiController constructor.
     * @param GitRepositoryInterface $gitRepository
     */
    public function __construct(GitRepositoryInterface $gitRepository)
    {
        $this->gitRepository = $gitRepository;
    }

    /**
     * @Route("/api", name="api")
     */
    public function index(Request $request): JsonResponse
    {
        $firstRepoData = [];
        $firstValue = $request->get('first');

        $secondRepoData = [];
        $secondValue = $request->get('second');


        if($firstValue && $secondValue){
            $firstRepoData = $this->gitRepository->getStatsData($firstValue);
            $secondRepoData = $this->gitRepository->getStatsData($secondValue);
        }

        $response = new JsonResponse();

        if($firstRepoData && $secondRepoData)
        {
            $response->setData([
                'first' => $firstRepoData,
                'secound' => $secondRepoData,
            ]);
        }
        else {
            $response->setStatusCode(JsonResponse::HTTP_NOT_FOUND);
        }

        return $response;
    }
}
