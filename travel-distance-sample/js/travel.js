const Travel = {
};
(()=>{
    const useDummyData = false;
    let DistanceApi;

    const getDummyData = (transport, origin, destinations, success) => {
        $.getJSON("/data/dummy-travel.json", (data) => {
            destinations.forEach((destination, i) => {
                const travel = data.rows[0].elements[i];
                destination.travel = {
                    distance: travel.distance.text,
                    duration: travel.duration.text
                };
            });
            success();
        });
    };
    const getData = (transport, originAddress, destinations, success) => {
        if (!DistanceApi) {
            // Lazy load and cache
            DistanceApi = new google.maps.DistanceMatrixService;
        }
        const destinationAddresses = [];
        destinations.forEach((destination, i) => {
            // Results are returned in the same order as the request
            destinationAddresses[i] = destination.address;
        });
        const request = {
            origins: [originAddress],
            destinations: destinationAddresses,
            travelMode: convertTransportToTravelMode(transport),
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        };
        DistanceApi.getDistanceMatrix(
            request,
            (response, status) => {
                if (status !== google.maps.DistanceMatrixStatus.OK) {
                    console.error("Travel.getData failed:" + status);
                } else {
                    updateDestinations(destinations, response);
                    success();
                }
            }
        );
    };
    const updateDestinations = (destinations, distanceResult) => {
        destinations.forEach((destination, i) => {
            const travel = distanceResult.rows[0].elements[i];
            destination.travel = {
                distance: travel.distance.text,
                duration: travel.duration.text
            };
        });
    };
    const convertTransportToTravelMode = (transport) => {
        const travelMode = google.maps.TravelMode;
        switch (transport) {
            case "walking":
                return travelMode.WALKING;
            case "bus":
                return travelMode.TRANSIT;
            case "car":
                return travelMode.DRIVING;
        }
        throw "Unknown transport:" + transport;
    };
    if (useDummyData) {
        Travel.getData = getDummyData;
    } else {
        Travel.getData = getData;
    }
})();