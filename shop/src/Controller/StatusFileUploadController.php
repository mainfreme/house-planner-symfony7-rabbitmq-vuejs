<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class StatusFileUploadController extends AbstractController
{
    public function __construct(
        private CacheInterface $cache
    )
    {
    }

    #[Route('/summary/{uuid}', name: 'csv_summary', methods: ['GET'])]
    public function showSummary(string $uuid): Response
    {
        $data = $this->cache->get('csv_' . $uuid, function ($item) {
            $item->expiresAfter(3600);
            return [];
        });

        return $this->render('upload/list_upload.html.twig', [
            'uuid' => $uuid,
            'data' => $data
        ]);
    }

    #[Route('/status/{uuid}', name: 'file_status', methods: ['GET'])]
    public function status(RequestStack $requestStack, string $uuid): JsonResponse
    {
        $session = $requestStack->getSession();
        $statusData = $session->get("file_status_{$uuid}", []);

        return $this->json([
            'status' => $statusData
        ]);
    }

    #[Route('/file/processed/{uuid}', name: 'file_processed', methods: ['GET'])]
    public function processed(RequestStack $requestStack, string $uuid): JsonResponse
    {
        $session = $requestStack->getSession();
        $progress = $session->get("file_progress_{$uuid}", 0);

        return $this->json([
            'progress' => $progress
        ]);
    }

}
