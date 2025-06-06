<?php

namespace App\Http\Builder;

use App\Http\Builder\Data\BuilderMessageData;
use App\Http\Builder\Data\BuilderMetadataData;
use App\Http\Builder\Data\BuilderResultData;
use App\Http\Builder\Data\BuilderWarningsData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseBuilder implements ResponseBuilderInterface
{
    private string $message;
    private ?array $result;
    private ?array $warnings;
    private int $code;
    private array $metadata;

    public function __construct(
        string $message = '',
        ?array $result = null,
        ?array $warnings = null,
        int    $code = 200,
        array  $metadata = [],
    )
    {
        $this->message = $message;
        $this->result = $result;
        $this->warnings = $warnings;
        $this->code = $code;
        $this->metadata = $metadata;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setResult(array $result): self
    {
        $this->result = $result;
        return $this;
    }

    public function setWarnings(array $warnings): self
    {
        $this->warnings = $warnings;
        return $this;
    }

    public function setResultResource(JsonResource $resource): self
    {
        return $this->setResult($resource->toArray(request()));
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function setMetadata(string $attribute, mixed $value): self
    {
        $this->metadata[$attribute] = $value;
        return $this;
    }

    public function setListMetadata(array $listMedata): self
    {
        $this->metadata = array_merge($this->metadata, $listMedata);
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    public function getWarnings(): ?array
    {
        return $this->warnings;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function response(): JsonResponse
    {
        return response()->api($this);
    }

    public function getDataResponse(): array
    {
        $builderMessageData = new BuilderMessageData();

        $builderMessageData
            ->setNext(new BuilderResultData())
            ->setNext(new BuilderWarningsData())
            ->setNext(new BuilderMetadataData())
        ;

        return $builderMessageData->handle($this, []);
    }
}
