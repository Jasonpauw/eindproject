<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Entity\Score;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIController extends AbstractController
{

    //post body requires title, description, user ID
    #[Route('/api/achievement', name: 'app_api_achievement')]
    public function achievement(EntityManagerInterface $entityManager, UserRepository $userRepository, GameRepository $gameRepository, AchievementRepository $achievementRepository): Response
    {
        $entityBody = file_get_contents('php://input');
        $decoded = json_decode($entityBody);
        $game = $gameRepository->findOneByLink($decoded->link);
        $user = $userRepository->find($decoded->userid);

        if($user === null) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
        }

        if($game === null) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
        }

        if($game->getHash($user) !== $decoded->hash) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
            //return new Response(json_encode('something went wrong'),400,['Content-Type' => 'application/json;charset=UTF-8']);
        }
        $achievement = new Achievement();
        $achievement->setTitle($decoded->title);
        $achievement->setDescription($decoded->description);
        $achievement->setPicture($decoded->picture);
        $achievement->setGame($game);
        $achievement->addUser($user);
        $entityManager->persist($achievement);
        $entityManager->flush();

        return new JsonResponse(['message' => 'succes'], 200);
        //return new Response(json_encode('succesfull'),201,['Content-Type' => 'application/json;charset=UTF-8']);
    }

    #[Route('/api/score', name: 'app_api_score')]
    public function score(EntityManagerInterface $entityManager, UserRepository $userRepository, GameRepository $gameRepository, AchievementRepository $achievementRepository): Response
    {
        $entityBody = file_get_contents('php://input');
        $decoded = json_decode($entityBody);
        $game = $gameRepository->findOneByLink($decoded->link);
        $user = $userRepository->find($decoded->userid);

        if($user === null) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
        }

        if($game === null) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
        }

        if($game->getHash($user) !== $decoded->hash) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
            //return new Response(json_encode('something went wrong'),400,['Content-Type' => 'application/json;charset=UTF-8']);
        }

        $score = new Score();
        $score->setGame($game);
        $score->setUser($user);
        $score->setPoints($decoded->points);
        $score->setTime($decoded->time);
        $entityManager->persist($score);
        $entityManager->flush();

        return new JsonResponse(['message' => 'succes'], 200);
    }

    #[Route('/api/user', name: 'app_api_user')]
    public function user(EntityManagerInterface $entityManager, UserRepository $userRepository, GameRepository $gameRepository, AchievementRepository $achievementRepository): Response
    {
        $entityBody = file_get_contents('php://input');
        $decoded = json_decode($entityBody);
        $user = $userRepository->find($decoded->userid);

        if($user === null) {
            return new JsonResponse(['message' => 'something went wrong'], 400);
        }

        return new JsonResponse(['profilePicture' => $user->getPicture(),'username' => $user->getUsername()], 200);
    }
}
