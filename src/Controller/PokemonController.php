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
            case 'nom':
                $pokemons = $repo->sortByAlphab();
                break;

            case 'capture':
                $pokemons = $repo->sortByCapture(); // à créer dans ton repo
                break;

            default:
                $pokemons = $repo->findAll(); // fallback au cas où le paramètre est invalide
        }

        return $this->render('pokemon/list.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    #[Route('/capture/{id}', name: 'capture', methods: ['POST'], requirements:['id' =>'\d+'])]
    public function toggleCapture(PokemonBase $pokemon, EntityManagerInterface $em): Response
    {
        $pokemon->setEstCapture($pokemon->getEstCapture() ? 0 : 1);
        $em->persist($pokemon);
        $em->flush();

        // Redirection vers la liste
        return $this->redirectToRoute('pokemon_list');
    }
}
