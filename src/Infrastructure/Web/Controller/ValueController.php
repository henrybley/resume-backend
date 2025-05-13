<?php

namespace App\Infrastructure\Web\Controller;

use App\Application\Command\UpdateValueCommand;
use App\Application\UseCase\UpdateValueUseCase;
use App\Infrastructure\Web\Dto\ValueDto;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValueController extends AbstractController
{
    private LoggerInterface $logger;
    private SerializerInterface $serializer;

    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    #[Route('/value/{id}', name: 'Update Value', methods: ['PATCH'])]
    public function updateValue(
        string $id,
        Request $request,
        UpdateValueUseCase $useCase,
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $this->logger->debug("Attempting to Update Value: {$id}");
        $requestData = json_decode($request->getContent());
        try {
            $value = $useCase->execute(new UpdateValueCommand($id, $requestData->content));

            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent($this->serializer->serialize(ValueDto::fromValue($value), 'json'));


            return $response;
        } catch (EntityNotFoundException | InvalidUuidStringException $e) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }
}
