<?php

namespace Jeffpereira\RealEstate\Interfaces\Address;

interface InterfaceAbstractAddress
{
    public const COLUMNS_ADDRESS = ['address', 'number', 'not_number', 'complement', 'cep', 'longitude', 'latitude'];
    public const COLUMNS_CREATE_UPDATE_NEIGHBORHOOD = ['neighborhood', 'city', 'state', 'initials', 'country'];
}
