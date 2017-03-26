Location = {

};
(()=>{
    let Locations = [];

    const loadLocations = () => {
        $.getJSON("/data/locations.json", (data) => {
            clearLocations();
            Locations = data;
            updateLocations(true);
        });
    }

    const clearLocations = () => {
        Locations = [];
        $("#locations").removeClass("section").empty();
    };

    const updateLocations = (create = false) => {
        Locations.forEach((location) => {
            if (create) {
                $("#locations").addClass("section");
                $("<div>", {class: "item location", id: location.id, address: location.address}).appendTo("#locations");
            }
            let text = location.name;
            if (location.travel) {
                text += ": " + location.travel.duration + " " + location.travel.distance;
            }
            $("#"+location.id).text(text);
        });
    };

    const calculateTravel = () => {
        if (!Locations || Locations.length == 0) {
            alert("You must load locations before you can calculate.");
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