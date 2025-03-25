<?php

namespace App\Dto;


use App\Enums\NoteableType;
use App\Models\Note;

class NoteDto extends Dto
{
    public function __construct(
        private string $note,
        private string $noteableType,
        private int $noteableId,
        private ?array $noteable = null
    ) {}

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getNoteableType(): string
    {
        return $this->noteableType;
    }

    public function setNotableType(string $noteableType): void
    {
        $this->noteableType = $noteableType;
    }


    public function getNoteableId(): int
    {
        return $this->noteableId;
    }

    public function setNotableId(int $noteableId): void
    {
        $this->noteableId = $noteableId;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            note: $data['note'],
            noteableType: $data['noteable_type'],
            noteableId: $data['noteable_id']
        );
    }

    public function toCreate(): array
    {
        return [
            'note' => $this->note,
            'noteable_type' => NoteableType::getModelClass($this->noteableType),
            'noteable_id' => $this->noteableId,
        ];
    }

    public function toUpdate(): array
    {
        return $this->toCreate();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'note' => $this->note,
            'noteable_type' => $this->noteableType,
            'noteable_id' => $this->noteableId,
        ];
    }

    public function toList(): array
    {
        return [
            'id' => $this->getId(),
            'note' => $this->note,
            'noteable_id' => $this->noteableId,
        ];
    }

    public static function fromModel(Note $note): self
    {
        $dto = new self(
            note: $note->note,
            noteableType: NoteableType::getSimpleType($note->noteable_type),
            noteableId: $note->noteable_id
        );

        $dto->setId($note->id);

        if ($note->relationLoaded('noteable') && $note->noteable) {
            $dto->noteable = $note->noteable->toArray();
        }

        return $dto;
    }
}
