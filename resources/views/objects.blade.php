@extends('layouts.landing')

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14"
        style="background-image: url({{ asset('assets-landing/images/bg/bg1.jpg') }});">
        <div class="section-shape section-shape1 top-inherit bottom-0"
            style="background-image: url({{ asset('assets-landing/images/shape8.png') }});"></div>
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3">Objek Pendukung</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Objek Pendukung</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <!-- Filter and Table Section -->
    <section class="trending pb-0 pt-6 mb-3">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="typeFilter">Filter by Type:</label>
                    <select id="typeFilter" class="form-control">
                        <option value="" selected>All</option>
                        <option value="1">Hotel</option>
                        <option value="2">Restoran/Wisata Kuliner</option>
                        <option value="3">Tempat Wisata Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive mb-3">
                <table id="storesTable" class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Jarak (km)</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div id="map" style="height: 500px;"></div>
        </div>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.0/dist/leaflet-routing-machine.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var defaultLat = '-7.07672301030441';
            var defaultLng = '113.60830950818035';
            var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            var map = L.map('map').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 50,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Add marker for current location
            var currentLocationMarker = L.marker([defaultLat, defaultLng]).addTo(map).bindPopup(
                "<b>You are here!</b>").openPopup();

            var currentMarkers = [];
            var routeControl = null;
            var destinationMarker = null;

            // Define custom icons
            var icons = {
                hotel: L.icon({
                    iconUrl: '{{ asset('assets-landing/images/icons/hotel.png') }}',
                    iconSize: [40, 40],
                    iconAnchor: [19, 42],
                    popupAnchor: [0, -32]
                }),
                restaurant: L.icon({
                    iconUrl: '{{ asset('assets-landing/images/icons/restaurant.png') }}',
                    iconSize: [40, 40],
                    iconAnchor: [19, 42],
                    popupAnchor: [0, -32]
                }),
                attraction: L.icon({
                    iconUrl: '{{ asset('assets-landing/images/icons/other.png') }}',
                    iconSize: [40, 40],
                    iconAnchor: [19, 42],
                    popupAnchor: [0, -32]
                })
            };

            var table = $('#storesTable').DataTable({
                lengthChange: false,
                searching: false,
                pageLength: 5,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Nomor urut
                        }
                    },
                    {
                        data: 'image',
                        render: function(data) {
                            if (data) {
                                return `<img src="/storage/objek/${data}" alt="" class="w-100 rounded" style="width: 50px; height: 50px; object-fit: cover;">`;
                            } else {
                                return '<i>No Image</i>';
                            }
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'tipe',
                        render: function(data) {
                            let typeLabel;
                            switch (data) {
                                case '1':
                                    typeLabel = 'Hotel';
                                    break;
                                case '2':
                                    typeLabel = 'Restaurant';
                                    break;
                                case '3':
                                    typeLabel = 'Attraction';
                                    break;
                                default:
                                    typeLabel = 'Other';
                                    break;
                            }
                            return typeLabel;
                        }
                    },
                    {
                        data: 'distance',
                        render: function(data) {
                            return `${data.toFixed(2)} km`; // Format jarak dengan dua angka desimal
                        }
                    },
                    {
                        data: 'description',
                        render: function(data) {
                            return data.length > 50 ? data.substring(0, 50) + '...' :
                                data; // Deskripsi singkat
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            const route = `/objects/${data.id}`;
                            return `
                    <button class="btn btn-primary btn-sm" 
                        onclick="getRoute('${defaultLat}', '${defaultLng}', '${data.latitude}', '${data.longitude}', '${data.name}', '${data.tipe}')">
                        Get Route
                    </button>
                    <a href="${route}" class="btn btn-success btn-sm">Detail</a>`;
                        }
                    }
                ]
            });

            function clearRoute() {
                if (routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }
                if (destinationMarker) {
                    map.removeLayer(destinationMarker);
                    destinationMarker = null;
                }
            }

            function fetchStores(type = '') {
                fetch('/api/find-nearest-stores', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            latitude: defaultLat,
                            longitude: defaultLng,
                            radius: 20,
                            type: type
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        table.clear().rows.add(data).draw();

                        currentMarkers.forEach(marker => map.removeLayer(marker));
                        currentMarkers = data.map(store => {
                            var icon;
                            switch (store.tipe) {
                                case '1':
                                    icon = icons.hotel;
                                    break;
                                case '2':
                                    icon = icons.restaurant;
                                    break;
                                case '3':
                                    icon = icons.attraction;
                                    break;
                                default:
                                    icon = icons.hotel;
                                    break;
                            }
                            return L.marker([store.latitude, store.longitude], {
                                    icon: icon
                                })
                                .addTo(map)
                                .bindPopup(`<b>${store.name}</b><br>Distance: ${store.distance} km`);
                        });

                        // Reset route and destination marker
                        clearRoute();
                    })
                    .catch(error => console.error('Error fetching stores:', error));
            }

            $('#typeFilter').on('change', function() {
                fetchStores($(this).val());
            });

            fetchStores();

            window.getRoute = function(userLat, userLng, storeLat, storeLng, storeName, storeType) {
                clearRoute();

                routeControl = L.Routing.control({
                    waypoints: [L.latLng(userLat, userLng), L.latLng(storeLat, storeLng)],
                    routeWhileDragging: true,
                    createMarker: function() {
                        return null;
                    },
                    fitSelectedRoutes: true,
                    collapsible: true
                }).addTo(map);

                // Sembunyikan leaflet-routing-container
                $('.leaflet-routing-container').hide();

                var icon;
                switch (storeType) {
                    case '1':
                        icon = icons.hotel;
                        break;
                    case '2':
                        icon = icons.restaurant;
                        break;
                    case '3':
                        icon = icons.attraction;
                        break;
                    default:
                        icon = icons.hotel;
                        break;
                }

                destinationMarker = L.marker([storeLat, storeLng], {
                    icon: icon
                }).addTo(map);
                destinationMarker.bindPopup(
                    `<b>${storeName}</b><br>Distance: ${(L.latLng(userLat, userLng).distanceTo(L.latLng(storeLat, storeLng)) / 1000).toFixed(2)} km`
                ).openPopup();
            };
        });
    </script>
@endsection
