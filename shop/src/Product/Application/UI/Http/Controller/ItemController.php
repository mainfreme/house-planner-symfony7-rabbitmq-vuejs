<?php

declare(strict_types=1);

namespace App\Product\Application\UI\Http\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/items', name: 'api_items_')]
class ItemController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ItemRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), Response::HTTP_OK);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $item = new Item();
        $item->setType($data['type']);
        $item->setName($data['name']);
        $item->setLatinName($data['latin_name'] ?? null);
        $item->setProductCategory($data['product_category'] ?? null);
        $item->setProducer($data['producer'] ?? null);
        $item->setPrice($data['price'] ?? null);
        $item->setQuantity($data['quantity'] ?? null);
        if (!empty($data['purchase_date'])) {
            $item->setPurchaseDate(new \DateTime($data['purchase_date']));
        }
        $item->setMeta($data['meta'] ?? null);

        $em->persist($item);
        $em->flush();

        return $this->json($item, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(ItemRepository $repo, int $id): JsonResponse
    {
        $item = $repo->find($id);
        return $item ? $this->json($item) : $this->json(['error' => 'Not found'], 404);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(ItemRepository $repo, EntityManagerInterface $em, Request $req, int $id): JsonResponse
    {
        $item = $repo->find($id);
        if (!$item) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($req->getContent(), true);

        foreach (['type', 'name', 'latin_name', 'product_category', 'producer', 'price', 'quantity'] as $field) {
            if (isset($data[$field])) {
                $method = 'set' . str_replace('_', '', ucwords($field, '_'));
                $item->$method($data[$field]);
            }
        }
        if (!empty($data['purchase_date'])) {
            $item->setPurchaseDate(new \DateTime($data['purchase_date']));
        }
        if (isset($data['meta'])) {
            $item->setMeta($data['meta']);
        }

        $item->setUpdatedAt(new \DateTime());
        $em->flush();

        return $this->json($item);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(ItemRepository $repo, EntityManagerInterface $em, int $id): JsonResponse
    {
        $item = $repo->find($id);
        if (!$item) return $this->json(['error' => 'Not found'], 404);

        $em->remove($item);
        $em->flush();

        return $this->json(null, 204);
    }
}
