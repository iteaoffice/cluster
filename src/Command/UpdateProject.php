<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace Cluster\Command;

use Cluster\Options\ModuleOptions;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Program\Service\CallService;
use Project\Entity\Project;
use Project\Provider\ProjectProvider;
use Project\Service\ProjectService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateProject
 * @package Cluster\Command
 */
final class UpdateProject extends Command
{
    private const ENDPOINT = 'api/update/project';

    /** @var string */
    protected static $defaultName = 'cluster:update-project';
    private ModuleOptions $moduleOptions;
    private ProjectService $projectService;
    private CallService $callService;
    private ProjectProvider $projectProvider;

    public function __construct(ModuleOptions $moduleOptions, ProjectService $projectService, CallService $callService, ProjectProvider $projectProvider)
    {
        parent::__construct(self::$defaultName);

        $this->projectService  = $projectService;
        $this->callService     = $callService;
        $this->moduleOptions   = $moduleOptions;
        $this->projectProvider = $projectProvider;
    }


    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'Module name');
        $this->addArgument('call', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $call = $this->callService->findCallById((int)$input->getArgument('call'));

        if (null === $call) {
            $output->writeln(sprintf('Call with ID %d cannot be found', $input->getArgument('call')));

            return Command::FAILURE;
        }

        $amount = 0;

        /** @var Project $project */
        foreach ($this->projectService->findProjectsByCall($call)->getQuery()->getResult() as $project) {
            $this->sendProject($project, $output);
            $amount++;
        }

        $output->writeln(sprintf('Sending of %d projects was successful', $amount));

        return Command::SUCCESS;
    }

    private function sendProject(Project $project, OutputInterface $output): void
    {
        $output->writeln(sprintf('Sending project %s to Reporting Portal', $project->parseFullName()));

        //Create an new Guzzle Client
        $guzzle = new Client([
            'base_uri' => $this->moduleOptions->getReportingPortalApiUrl()
        ]);

        $guzzle->request(
            'POST',
            self::ENDPOINT,
            [
                RequestOptions::HEADERS     => [
                    'Authorization' => 'Bearer ' . $this->moduleOptions->getBearerToken(),
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json'
                ],
                RequestOptions::DEBUG       => false,
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::JSON        => $this->projectProvider->generateArray($project)
            ]
        );
    }
}
