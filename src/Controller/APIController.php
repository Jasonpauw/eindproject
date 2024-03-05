<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIController extends AbstractController
{

    //post body requires title, description, user ID
    #[Route('/api', name: 'app_api')]
    public function index(EntityManager $entityManager, UserRepository $userRepository, GameRepository $gameRepository, AchievementRepository $achievementRepository): Response
    {
        $entityBody = file_get_contents('php://input');
        $decoded = json_decode($entityBody);
        $game = $gameRepository->findOneBy($decoded->link);
        $user = $userRepository->findOneBy($decoded->id);
        if($game->getHash($user) !== $decoded->hash) {
            return new Response(json_encode('something went wrong'),400,['Content-Type' => 'application/json;charset=UTF-8']);
        }
        $achievement = new Achievement();
        $achievement->setTitle($decoded->title);
        $achievement->setDescription($decoded->description);
        $achievement->setGame($game);
        $entityManager->persist($achievement);
        $entityManager->flush();

        return new Response(json_encode('succesfull'),200,['Content-Type' => 'application/json;charset=UTF-8']);
    }
}
