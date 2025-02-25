<?php

namespace App\Controller;

use App\Application\CsvProcessing\Command\UploadCsvFileCommand;
use App\Validator\Constraints\CsvFile;
use App\Validator\Constraints\CsvFileEmpty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class FileUploadController extends AbstractController
{

    public function __construct(private MessageBusInterface $commandBus, #[Autowire('%kernel.project_dir%')] private string $projectDir)
    {
    }

    #[Route('/upload', name: 'file_upload')]
    public function upload(Request $request): Response
    {
        $form = $this->createUploadForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('csv_file')->getData();


            $projectDir = sprintf('%s/var/uploads', $this->projectDir);

            $newFilename = uniqid() . '.' . $file->guessExtension();

            try {
                $file->move($projectDir, $newFilename);
                $this->addFlash('success', 'Plik został zapisany!');
            } catch (FileException $e) {
                $this->addFlash('error', 'Błąd podczas przesyłania pliku.');
                return $this->redirectToRoute('file_upload');
            }

            try {
                $this->commandBus->dispatch(new UploadCsvFileCommand($newFilename, $projectDir));

                $this->addFlash('success', 'Plik jest przetwarzany!');
            } catch (\Exception $e) {

                $this->addFlash('Error', 'Nie udało się przetworzyć pliku! ' . $e->getMessage());
            }


            return $this->redirectToRoute('file_upload');
        }

        return $this->render('upload/csv_upload.html.twig', [
            'form' => $form,
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
