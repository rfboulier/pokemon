<?php

namespace App\Controller;

use App\Entity\PokemonBase;
use App\Repository\PokemonBaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/pokemon', name: 'pokemon_')]
final class PokemonController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(PokemonBaseRepository $pokemonBaseRepository): Response
    {
        $pokemons = $pokemonBaseRepository->findAll();

        return $this->render('pokemon/list.html.twig', [
           'pokemons' => $pokemons,
        ]);
    }

    #[Route('/{id}', name: 'detail', requirements:['id' =>'\d+'])]
public function detail(PokemonBase $pokemon, PokemonBaseRepository $pokemonBaseRepository): Response
    {
        return $this->render('pokemon/detail.html.twig', ['pokemon' => $pokemon]);
    }

    #[Route('/tri/{param}', name: 'tri_param', methods: ['GET'])]
    public function triParam(string $param, PokemonBaseRepository $repo): Response
    {
        switch ($param) {


            case 'capture':
                $pokemons = $repo->sortByCapture(); // à créer dans ton repo
                break;

            case 'attack':
                $pokemons = $repo->sortByAttack();
                break;

            default:
                $pokemons = $repo->sortByAlphab(); // fallback au cas où le paramètre est invalide
        }

        return $this->render('pokemon/list.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

/*    #[Route('/capture/{id}', name: 'capture', methods: ['POST'], requirements:['id' =>'\d+'])]
    public function toggleCapture(PokemonBase $pokemon, EntityManagerInterface $em): Response
    {
        $pokemon->setEstCapture($pokemon->getEstCapture() ? 0 : 1);
        $em->persist($pokemon);
        $em->flush();

        // Redirection vers la liste
        return $this->redirectToRoute('pokemon_list');
    }*/
    #[Route('/capture/{id}', name: 'capture', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function toggleCapture(
        PokemonBase $pokemon,
        EntityManagerInterface $em,
        \Symfony\Component\HttpFoundation\Request $request
    ): Response {
        $pokemon->setEstCapture($pokemon->getEstCapture() ? 0 : 1);
        $em->persist($pokemon);
        $em->flush();

        $redirectUrl = $request->request->get('redirect');

        return $this->redirect($redirectUrl ?: $this->generateUrl('pokemon_list'));
    }


    #[Route('/captured', name: 'captured', methods: ['GET'])]
    public function captured(PokemonBaseRepository $pokemonBaseRepository): Response
    {
        $pokemons = $pokemonBaseRepository->filterCaptured();

        return $this->render('pokemon/my_list.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }
}
