<?php

namespace App\Order\Application\DTO;

class AddressDTO
{
    private string $firstName;
    private string $lastName;
    private string $streetName;
    private string $city;
    private string $postalCode;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $streetName
     * @param string $city
     * @param string $postalCode
     */
    public function __construct(string $firstName, string $lastName, string $streetName, string $city, string $postalCode)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->streetName = $streetName;
        $this->city = $city;
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getStreetName(): string
    {
        return $this->streetName;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'street_name' => $this->streetName,
            'city_name' => $this->city,
            'postal_code' => $this->postalCode,
        ];
    }
}
