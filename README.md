# Tankstellenkönig API client

Simple Client for tankstellenkoenig.de json api. 

Inspired by https://github.com/tankerkoenig/tankerkoenig-php-client/


## Install

```
composer require lxlang/tankerkoenig-php-client
```
## API-KEY
The free Tankerkönig-Spritpreis-API is under creative commons.

Get your own API-Key here: 
https://creativecommons.tankerkoenig.de

## Usage
### Init API client
```
use Lang\Tankerkoenig\ApiClient;
$apiClient = new ApiClient("your-api-key-here");
```

### search for gas stations by location
``` 
// get over
$gasStations = $apiClient->search(
	50.538472, //lat
	8.649647, //lng
	$apiClient::TYPE_E10 //your type of fuel
);

//echo results for testing
print_r($gasStations);
```

### Gas Station details
```  
//unique id of a gas station
$gasStationUuid = '51d4b6a2-a095-1aa0-e100-80009459e03a';

// returns an object of type \Lang\Tankerkoenig\GasStatsion
$gasStation = $apiClient->detail($gasStationUuid);
 
print_r($gasStation);
```

