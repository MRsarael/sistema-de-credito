<?php

namespace App\Business\Gosat;

class ObjectConsultOfferCredit implements ConsultOfferCreditInterface
{
    private string $id;
    private string $name;
    private string $email;
    private string $cpf;

    public function __construct()
    {
        //
    }

    public static function make(\App\Http\Resources\PersonResource $person): self
    {
        $self = app(ObjectConsultOfferCredit::class);
        $self->setId($person['id']);
        $self->setName($person['name']);
        $self->setEmail($person['email']);
        $self->setCpf($person['cpf']);
        return $self;
    }

    public function toString(): string
    {
        return '{"id": "'.$this->id.'","name": "'.$this->name.'","email": "'.$this->email.'","cpf": "'.$this->cpf.'"}';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }
}
