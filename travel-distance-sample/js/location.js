Location = {

};
(()=>{
    let Locations = [];

    const loadLocations = (callback) => {
        $.getJSON("/data/locations.json", (data) => {
            Locations = data;
            updateLocations();
            callback();
        });
    }

    const clearLocations = () => {
        Locations = [];
        $("#locations").removeClass("section").empty();
    };

    const updateLocations = () => {
        $("#locations").removeClass("section").empty();
        Locations.sort((a,b) => {
            if (a.travel && b.travel) {
                return a.travel.duration.value - b.travel.duration.value;
            }
            return a.name.localeCompare(b.name);
        });
        Locations.forEach((location) => {
            $("#locations").addClass("section");
            const locationElement = $("<div>", {class: "item location", id: location.id, address: location.address}).appendTo("#locations");
            let text = location.name;
            if (location.travel) {
                text += ": " + location.travel.duration.text + " " + location.travel.distance.text;
            }
            locationElement.text(text);
        });
    };

    const calculateTravel = () => {
        if (!Locations || Locations.length == 0) {
            console.log("You must load locations before you can calculate.");
            return;
        }
        const currentAddress = $("#current_address").val();

        const transport = $("input[name='transport']:checked").val();

        Travel.getData(transport, currentAddress, Locations, updateLocations);
    };

    Location.load = loadLocations;
    Location.clear = clearLocations;
    Location.calculate = calculateTravel;
})();