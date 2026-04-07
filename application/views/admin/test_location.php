<!DOCTYPE html>
<html>
<head>
    <title>Google Maps Location Dropdown Test</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Google Maps Location Dropdown (Test Page)
        </div>

        <div class="card-body">
            <label>Enter Location</label>
            <input type="text" id="location" class="form-control"
                   placeholder="Start typing location...">

            <small class="text-muted">
                Example: Bangalore, Vijayapura, Hubli
            </small>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Google Maps Places API -->
<script src="https://maps.googleapis.com/maps/api/js?key=API_KEY&libraries=places"></script>

<script>
    // connect google autocomplete to input
    var input = document.getElementById('location');
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        console.log("Selected Location:", place.formatted_address);
    });
</script>

</body>
</html>
