<!DOCTYPE html>
<html>
<head>
    <title>Parking Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
</head>

<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Login</h5>
                </div>
                <div class="card-body">
                    <div id="msg"></div>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" id="password" class="form-control" required>
                        </div>
                        <input type="hidden" id="latitude">
                        <input type="hidden" id="longitude">
                        <button type="submit" class="btn btn-success w-100">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function (pos) {
            latitude.value = pos.coords.latitude;
            longitude.value = pos.coords.longitude;
        },
        function () {
            console.log("Location permission denied");
        }
    );
}

document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

fetch("<?= site_url('parking/parking_login') ?>", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
        email: email.value,
        password: password.value,
        latitude: latitude.value,
        longitude: longitude.value
    })
})
.then(response => response.json())
.then(data => {
    console.log("Login response:", data);
    if (data.status === true && data.redirect) {
        window.location.href = data.redirect;
    } else {
        document.getElementById('msg').innerHTML =
            `<div class="alert alert-danger">${data.message}</div>`;
    }
})
.catch(err => {
    console.error("Login error:", err);
});

});
</script>
</body>
</html>
