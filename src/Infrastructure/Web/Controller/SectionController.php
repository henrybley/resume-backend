<?php

namespace App\Infrastructure\Web\Controller;

use App\Application\Command\AddFieldToSectionCommand;
use App\Application\Command\CreateSectionCommand;
use App\Application\QueryHandler\GetAllSections;
use App\Application\QueryHandler\GetSectionByIdHandler;
use App\Application\Query\GetSectionById;
use App\Application\UseCase\AddFieldToSectionUseCase;
use App\Application\UseCase\CreateSectionUseCase;
use App\Infrastructure\Web\Dto\FieldDto;
use App\Infrastructure\Web\Dto\SectionDto;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SectionController extends AbstractController
{
    private LoggerInterface $logger;
    private SerializerInterface $serializer;

    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    #[Route('/section', name: 'Get All Sections', methods: ['GET'])]
    public function getAllSections(
        GetAllSections $handler
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $this->logger->debug("Getting All Sections");

        $sections = $handler->execute();

        $sectionDtos = [];
        foreach ($sections as $section) {
            $sectionDtos[] = SectionDto::fromSection($section);
        }

        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($this->serializer->serialize($sectionDtos, 'json'));

        return $response;
    }

    #[Route('/section/{id}', name: 'Update Section', methods: ['PATCH'])]
    public function updateSection(
        GetSectionByIdHandler $handler,
        string $id,
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $this->logger->debug("Attempting to update Section with ID: {$id}");
        try {
            $section = $handler->execute(new GetSectionById($id));
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent($this->serializer->serialize(SectionDto::fromSection($section), 'json'));
            return $response;
        } catch (EntityNotFoundException | InvalidUuidStringException $e) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }

    #[Route('/section/{id}', name: 'Get Section By Id', methods: ['GET'])]
    public function getSectionById(
        GetSectionByIdHandler $handler,
        string $id,
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $this->logger->debug("Attempting to get Section with ID: {$id}");
        try {
            $section = $handler->execute(new GetSectionById($id));
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent($this->serializer->serialize(SectionDto::fromSection($section), 'json'));
            return $response;
        } catch (EntityNotFoundException | InvalidUuidStringException $e) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }

    #[Route('/section', name: 'Create Section', methods: ['POST'])]
    public function createSection(
        Request $request,
        CreateSectionUseCase $useCase,
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $requestData = json_decode($request->getContent());
        $this->logger->debug("Attempting to create Section with name: {$requestData->name}");
        $section = $useCase->execute(new CreateSectionCommand($requestData->name));

        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($this->serializer->serialize(SectionDto::fromSection($section), 'json'));

        return $response;

    }

    #[Route('/section/{sectionId}/field', name: 'Add Field To Section', methods: ['POST'])]
    public function addFieldToSection(
        Request $request,
        string $sectionId,
        AddFieldToSectionUseCase $useCase
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $requestData = json_decode($request->getContent());
        $this->logger->debug("Attempting to Add a Field({$requestData->type}) to Section: {$sectionId}");

        try {
            $field = $useCase->execute(new AddFieldToSectionCommand($sectionId, $requestData->type));

            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent($this->serializer->serialize(FieldDto::fromField($field), 'json'));

            return $response;
        } catch (EntityNotFoundException $e) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }
}
