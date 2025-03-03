<?php

namespace App\Controller;

use App\Application\CsvProcessing\Command\UploadCsvFileCommand;
use App\Domain\CsvProcessing\Enum\CsvFileUploadStatusEnum;
use App\Validator\Constraints\CsvFile;
use App\Validator\Constraints\CsvFileEmpty;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class FileUploadController extends AbstractController
{
    public function __construct(
        private MessageBusInterface                        $bus,
        #[Autowire('%kernel.project_dir%')] private string $projectDir,
        private readonly RequestStack                      $requestStack,
        private readonly LoggerInterface                   $logger,
    ) {
    }

    #[Route('/upload/{uuid?}', name: 'file_upload')]
    public function upload(Request $request, ?string $uuid): Response
    {
        $form = $this->createUploadForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('csv_file')->getData();

            $uuid = uniqid();
            $session = $this->requestStack->getSession();
            $session->set("file_status_{$uuid}", CsvFileUploadStatusEnum::SENDING);

            $destinationPath = sprintf('%s/var/uploads', $this->projectDir);
            $newFilename = uniqid() . '.' . $file->guessExtension();

            try {
                $file->move($destinationPath, $newFilename);
                $session->set("file_status_{$uuid}", CsvFileUploadStatusEnum::COPIED);
            } catch (FileException $e) {
                $this->logger->error('Błąd podczas przesyłania pliku. ' . $e->getMessage());
                $this->addFlash('error', 'Błąd podczas przesyłania pliku.');

                return $this->redirectToRoute('file_upload');
            }

            // wysyłamy do procesowania cały plik
            try {
                $this->bus->dispatch(new UploadCsvFileCommand($newFilename, $destinationPath, $uuid));
                $session->set("file_status_{$uuid}", CsvFileUploadStatusEnum::PENDING);
                $this->addFlash('success', 'Plik jest przetwarzany!');
            } catch (\Exception $e) {
                $this->logger->error('Nie udało się przetworzyć pliku! ' . $e->getMessage());
                $this->addFlash('Error', 'Nie udało się przetworzyć pliku!');
            }

            return $this->redirectToRoute('file_upload', [
                'form' => $form,
                'uuid' => $uuid
            ]);
        }

        return $this->render('upload/csv_upload.html.twig', [
            'form' => $form,
            'uuid' => $uuid ?? null
        ]);
    }


    private function createUploadForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->add('csv_file', FileType::class, [
                'label' => 'Wybierz plik CSV',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new CsvFile(),
                    new CsvFileEmpty(),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Prześlij'])
            ->getForm();
    }
}
