<?php

namespace App\Business\Gosat;

interface ConsultOfferCreditInterface
{
    public static function make(\App\Http\Resources\PersonResource $person): self;
    public function toString(): string;
    public function getId(): string;
    public function setId(int $id): void;
    public function getName(): string;
    public function setName(string $name): void;
    public function getEmail(): string;
    public function setEmail(string $email): void;
    public function getCpf(): string;
    public function setCpf(string $cpf): void;
}