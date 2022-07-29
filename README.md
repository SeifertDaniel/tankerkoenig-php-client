# Tankstellenkönig API client

Simple client for tankstellenkoenig.de json api. 

Inspired by https://github.com/tankerkoenig/tankerkoenig-php-client/

Forked from https://github.com/lxlang/tankerkoenig-php-client

## Install

```
composer require daniels/tankerkoenig-php-client
```
## API-KEY
The free Tankerkönig-Spritpreis-API is under creative commons.

Get your own API-Key here: 
https://creativecommons.tankerkoenig.de

## Usage
### Init API client
```
use DanielS\Tankerkoenig\ApiClient;
$apiClient = new ApiClient("your-api-key-here");
```

### Search for gas stations by location
``` 
// get over
$petrolStations = $apiClient->search(
    50.538472, //lat
    8.649647, //lng
    $apiClient::TYPE_E10 //your type of fuel
);

//echo results for testing
print_r($petrolStations);
```

### Gas station details
```  
//unique id of a petrol station
$petrolStationUuid = '51d4b6a2-a095-1aa0-e100-80009459e03a';

// returns an object of type \Lang\Tankerkoenig\PetrolStation
$petrolStation = $apiClient->detail($petrolStationUuid);
 
print_r($petrolStation);
```

### Current prices by station list
```
$prices = $apiClient->prices([
    '51d4b6a2-a095-1aa0-e100-80009459e03a'
]);

//echo results for testing
print_r($prices);
```

### Complaints
```
$apiClient->complaint(
    '51d4b6a2-a095-1aa0-e100-80009459e03a',
    Complaint::WRONG_PRICE_E10,
    1.599
);
```

## Changelog

See [CHANGELOG](CHANGELOG.md) for further informations.

## Contributing

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue. Don't forget to give the project a star! Thanks again!

- Fork the Project
- Create your Feature Branch (git checkout -b feature/AmazingFeature)
- Commit your Changes (git commit -m 'Add some AmazingFeature')
- Push to the Branch (git push origin feature/AmazingFeature)
- Open a Pull Request

## License
(status: 2022-07-29)

Distributed under the MIT license.

```
Copyright: (c) 2017 Tobias Lang
           (c) 2022-present Daniel Seifert

This software is distributed under the MIT LICENSE.
```

For full copyright and licensing information, please see the [LICENSE](LICENSE.md) file distributed with this source code.