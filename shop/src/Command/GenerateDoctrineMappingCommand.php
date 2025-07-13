<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'app:doctrine:generate-mapping',
    description: 'Generates doctrine mapping configuration for all contexts in the Domain layer.',
)]
class GenerateDoctrineMappingCommand extends Command
{
    private string $projectDir;

    public function __construct(ParameterBagInterface $params)
    {
        parent::__construct();
        // Get the project root directory from container parameters
        $this->projectDir = $params->get('kernel.project_dir');
    }

    protected function configure(): void
    {
        $this->setHelp('This command scans the src/Domain directory and creates a doctrine/mapping.yaml file with the correct ORM mappings.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $domainDir = $this->projectDir . '/src';
        $filesystem = new Filesystem();

        if (!$filesystem->exists($domainDir)) {
            $io->error(sprintf('The Domain directory does not exist at: %s', $domainDir));
            return Command::FAILURE;
        }

        $io->title('Generating Doctrine ORM Mapping');

        $finder = new Finder();
        // Find all directories at depth 0 in the src/Domain directory
        $finder->directories()->in($domainDir)->depth('== 0');

        $mappings = [];

        foreach ($finder as $contextDir) {
            $contextName = $contextDir->getRelativePathname();
            $entityDir = $contextDir->getRealPath() . '/Entity';

            if ($filesystem->exists($entityDir)) {
                $io->text(sprintf('Found context with entities: <info>%s</info>', $contextName));


                $mappings[$contextName] = [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/' . $contextName . '/Domain/Entity',
                    'prefix' => 'App\\' . $contextName . '\\Domain\\Entity',
                ];
            }
        }

        if (empty($mappings)) {
            $io->warning('No contexts with an "Entity" directory were found. No mapping file was generated.');
            return Command::SUCCESS;
        }

        $yamlContent = [
            'doctrine' => [
                'orm' => [
                    'mappings' => $mappings,
                ],
            ],
        ];


        $yaml = Yaml::dump($yamlContent, 4, 4);

        $outputFile = $this->projectDir . '/config/packages/doctrine/mapping.yaml';

        // Ensure the target directory exists
        $filesystem->mkdir(dirname($outputFile));
        // Write the generated YAML to the file
        $filesystem->dumpFile($outputFile, $yaml);

        $io->success(sprintf('Doctrine mapping file has been successfully generated at: %s', $outputFile));

        $io->note('Remember to import this file in your main doctrine configuration (e.g., config/packages/doctrine.yaml)');
        $io->writeln("<info>imports:</info>\n    - { resource: '../../src/doctrine/mapping.yaml' }");


        return Command::SUCCESS;
    }
}
