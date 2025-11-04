<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calculate Shipping - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">

    <!-- Page CSS -->
    <style>
        .shipping-service {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .shipping-service:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .service-cost {
            font-size: 1.3em;
            font-weight: bold;
            color: #28a745;
        }
        .api-status {
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.85em;
        }
        .customer-address-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #7367f0;
        }
        .route-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Header -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">
                                            <i class="bx bx-truck me-2"></i>
                                            Calculate Shipping Cost
                                        </h4>
                                        <div class="api-status alert alert-info mb-0" id="apiStatus">
                                            <i class="bx bx-sync bx-spin me-2"></i>Checking API status...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Calculation Form -->
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bx bx-calculator me-2"></i>
                                            Shipping Calculator
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="shippingForm">
                                            @csrf
                                            
                                            <!-- Customer Selection -->
                                            <div class="mb-3">
                                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                                <select name="customer_id" id="customer_id" class="form-control select2" required>
                                                    <option value="">Select Customer</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id_customer }}" 
                                                                data-address="{{ $customer->alamat }}"
                                                                data-has-location="{{ $customer->hasLocationData() ? '1' : '0' }}"
                                                                data-street-address="{{ $customer->street_address }}">
                                                            {{ $customer->nama }} - {{ $customer->email }}
                                                            @if(!$customer->hasLocationData())
                                                                <span class="text-danger">(No Location Data)</span>
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text" id="customerAddress"></div>
                                                <div class="alert alert-warning mt-2" id="customerLocationWarning" style="display: none;">
                                                    <i class="bx bx-error-alt me-2"></i>
                                                    This customer address doesn't have location data. 
                                                    <a href="#" id="updateAddressBtn" class="alert-link">Update address</a>
                                                </div>
                                            </div>

                                            <!-- Weight Input -->
                                            <div class="mb-3">
                                                <label for="weight" class="form-label">Weight (grams) <span class="text-danger">*</span></label>
                                                <input type="number" name="weight" id="weight" class="form-control" 
                                                       value="1000" min="1" max="30000" required>
                                                <div class="form-text">Maximum weight: 30,000 grams (30kg)</div>
                                            </div>

                                            <!-- Courier Selection -->
                                            <div class="mb-3">
                                                <label for="courier" class="form-label">Courier <span class="text-danger">*</span></label>
                                                <select name="courier" id="courier" class="form-control" required>
                                                    <option value="">Select Courier</option>
                                                    @foreach($couriers as $key => $name)
                                                        <option value="{{ $key }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Origin City -->
                                            <div class="mb-4">
                                                <label for="origin_city_id" class="form-label">Origin City ID <span class="text-danger">*</span></label>
                                                <input type="number" name="origin_city_id" id="origin_city_id" 
                                                       class="form-control" value="{{ $defaultOriginCityId }}" required>
                                                <div class="form-text">
                                                    @if($defaultOriginCity)
                                                        Your store location: {{ $defaultOriginCity['city_name'] ?? 'Unknown' }}, {{ $defaultOriginCity['province'] ?? 'Unknown' }}
                                                    @else
                                                        Your store's city ID
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button type="button" class="btn btn-secondary me-md-2" id="resetBtn">
                                                    <i class="bx bx-reset me-2"></i>Reset
                                                </button>
                                                <button type="submit" class="btn btn-primary" id="calculateBtn">
                                                    <i class="bx bx-calculator me-2"></i>Calculate Shipping
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Update Address Form (Hidden by Default) -->
                                <div class="card" id="updateAddressCard" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bx bx-edit me-2"></i>
                                            Update Customer Address
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="updateAddressForm">
                                            @csrf
                                            <input type="hidden" name="customer_id" id="update_customer_id">
                                            
                                            <div class="mb-3">
                                                <label for="street_address" class="form-label">Street Address</label>
                                                <textarea name="street_address" id="street_address" class="form-control" rows="3" required></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="update_province_id" class="form-label">Province</label>
                                                        <select name="province_id" id="update_province_id" class="form-control select2">
                                                            <option value="">Select Province</option>
                                                            @foreach($provinces as $province)
                                                                <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="update_city_id" class="form-label">City</label>
                                                        <select name="city_id" id="update_city_id" class="form-control select2" disabled>
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="province_name" id="province_name">
                                            <input type="hidden" name="city_name" id="city_name">

                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary" id="cancelUpdateBtn">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    Update Address
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Section -->
                            <div class="col-lg-6">
                                <div id="shippingResults" style="display: none;">
                                    <!-- Route Information -->
                                    <div class="card route-info mb-4">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-white mb-3">
                                                <i class="bx bx-map me-2"></i>Shipping Route
                                            </h5>
                                            <div class="row">
                                                <div class="col-5">
                                                    <h6 class="text-white">Origin</h6>
                                                    <p class="mb-0" id="originCity"></p>
                                                    <small id="originProvince"></small>
                                                </div>
                                                <div class="col-2">
                                                    <i class="bx bx-right-arrow-alt bx-lg text-white"></i>
                                                </div>
                                                <div class="col-5">
                                                    <h6 class="text-white">Destination</h6>
                                                    <p class="mb-0" id="destinationCity"></p>
                                                    <small id="destinationProvince"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Information -->
                                    <div class="card customer-address-box mb-4">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bx bx-user me-2"></i>Customer Information
                                            </h6>
                                            <p class="mb-1"><strong>Name:</strong> <span id="customerName"></span></p>
                                            <p class="mb-1"><strong>Address:</strong> <span id="customerFullAddress"></span></p>
                                            <p class="mb-0"><strong>Location:</strong> <span id="customerLocation"></span></p>
                                        </div>
                                    </div>

                                    <!-- Shipping Results -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="bx bx-list-ul me-2"></i>
                                                Available Shipping Services
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="resultsContent"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div id="emptyState" class="text-center">
                                    <div class="card">
                                        <div class="card-body py-5">
                                            <i class="bx bx-calculator bx-lg text-muted mb-3"></i>
                                            <h5 class="text-muted">No Calculation Yet</h5>
                                            <p class="text-muted">Fill out the form and calculate shipping costs to see results here.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout container -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();

        // Check API status on page load
        checkApiStatus();

        // Customer selection change
        $('#customer_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const address = selectedOption.data('address');
            const hasLocation = selectedOption.data('has-location');
            const streetAddress = selectedOption.data('street-address');
            
            $('#customerAddress').html('<strong>Current Address:</strong> ' + (streetAddress || address || 'No address available'));
            
            if (hasLocation == '0') {
                $('#customerLocationWarning').show();
                $('#calculateBtn').prop('disabled', true);
                $('#update_customer_id').val($(this).val());
                $('#street_address').val(streetAddress || '');
            } else {
                $('#customerLocationWarning').hide();
                $('#calculateBtn').prop('disabled', false);
            }
        });

        // Update address button
        $('#updateAddressBtn').on('click', function(e) {
            e.preventDefault();
            $('#updateAddressCard').show();
        });

        // Cancel update address
        $('#cancelUpdateBtn').on('click', function() {
            $('#updateAddressCard').hide();
        });

        // Province change for city dropdown
        $('#update_province_id').on('change', function() {
            const provinceId = $(this).val();
            const provinceName = $(this).find('option:selected').text();
            $('#province_name').val(provinceName);
            
            if (provinceId) {
                $('#update_city_id').prop('disabled', false);
                
                // Load cities for selected province
                $.get('{{ route("calculate.cities") }}', { province_id: provinceId }, function(data) {
                    if (data.success) {
                        $('#update_city_id').empty().append('<option value="">Select City</option>');
                        data.data.forEach(function(city) {
                            $('#update_city_id').append(
                                $('<option>', {
                                    value: city.city_id,
                                    text: city.type + ' ' + city.city_name,
                                    'data-province': city.province
                                })
                            );
                        });
                    }
                });
            } else {
                $('#update_city_id').prop('disabled', true).empty().append('<option value="">Select City</option>');
            }
        });

        // City selection
        $('#update_city_id').on('change', function() {
            const cityName = $(this).find('option:selected').text();
            $('#city_name').val(cityName);
        });

        // Update address form
        $('#updateAddressForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("calculate.update-customer-address") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        alert('Address updated successfully!');
                        $('#updateAddressCard').hide();
                        $('#customerLocationWarning').hide();
                        $('#calculateBtn').prop('disabled', false);
                        
                        // Reload page to refresh customer data
                        location.reload();
                    } else {
                        alert('Error: ' + response.error);
                    }
                },
                error: function(xhr) {
                    alert('Error updating address');
                }
            });
        });

        // Reset button
        $('#resetBtn').on('click', function() {
            $('#shippingForm')[0].reset();
            $('#customer_id').val(null).trigger('change');
            $('#shippingResults').hide();
            $('#emptyState').show();
            $('#customerAddress').text('');
            $('#customerLocationWarning').hide();
            $('#calculateBtn').prop('disabled', false);
            $('#updateAddressCard').hide();
        });

        // Form submission
        $('#shippingForm').on('submit', function(e) {
            e.preventDefault();
            
            const calculateBtn = $('#calculateBtn');
            const originalText = calculateBtn.html();
            
            calculateBtn.prop('disabled', true).html('<i class="bx bx-loader bx-spin me-2"></i>Calculating...');
            
            $.ajax({
                url: '{{ route("calculate.shipping-cost") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        displayShippingResults(response);
                        $('#shippingResults').show();
                        $('#emptyState').hide();
                    } else {
                        showError(response.error);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Something went wrong';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    showError(errorMessage);
                },
                complete: function() {
                    calculateBtn.prop('disabled', false).html('<i class="bx bx-calculator me-2"></i>Calculate Shipping');
                }
            });
        });
        
        function checkApiStatus() {
            $.ajax({
                url: '{{ route("calculate.api-status") }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#apiStatus').removeClass('alert-info').addClass('alert-success')
                            .html('<i class="bx bx-check-circle me-2"></i>RajaOngkir API is working properly');
                    } else {
                        $('#apiStatus').removeClass('alert-info').addClass('alert-danger')
                            .html('<i class="bx bx-error-alt me-2"></i>RajaOngkir API is not responding');
                    }
                },
                error: function() {
                    $('#apiStatus').removeClass('alert-info').addClass('alert-danger')
                        .html('<i class="bx bx-error-alt me-2"></i>Failed to check API status');
                }
            });
        }
        
        function displayShippingResults(data) {
            // Update route information
            $('#originCity').text(data.origin.city_name);
            $('#originProvince').text(data.origin.province);
            $('#destinationCity').text(data.destination.city_name);
            $('#destinationProvince').text(data.destination.province);
            
            // Update customer information
            $('#customerName').text(data.customer.nama);
            $('#customerFullAddress').text(data.customer.street_address);
            $('#customerLocation').text(data.customer.city_name + ', ' + data.customer.province_name);
            
            // Display shipping services
            let html = '';
            
            if (data.shipping_costs && data.shipping_costs.length > 0) {
                data.shipping_costs.forEach(service => {
                    const cost = service.cost[0];
                    const formattedCost = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(cost.value);
                    
                    html += `
                        <div class="card shipping-service mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title mb-1">${service.service}</h6>
                                        <p class="card-text text-muted small mb-2">${service.description}</p>
                                        <p class="service-cost mb-0">${formattedCost}</p>
                                        <small class="text-muted">Estimation: ${cost.etd} days</small>
                                    </div>
                                    <button class="btn btn-success btn-sm select-service" 
                                            data-service="${service.service}"
                                            data-cost="${cost.value}"
                                            data-description="${service.description}"
                                            data-courier="${data.courier}">
                                        <i class="bx bx-check me-1"></i>Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = `
                    <div class="alert alert-warning">
                        <i class="bx bx-error-alt me-2"></i> No shipping services available for this route.
                    </div>
                `;
            }
            
            $('#resultsContent').html(html);
            
            // Add event listeners for select buttons
            $('.select-service').on('click', function() {
                const service = $(this).data('service');
                const cost = $(this).data('cost');
                const description = $(this).data('description');
                const courier = $(this).data('courier');
                
                const formattedCost = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(cost);
                
                if (confirm(`Select ${service} - ${formattedCost}?`)) {
                    onServiceSelected(service, cost, description, courier);
                }
            });
        }
        
        function onServiceSelected(service, cost, description, courier) {
            // Implementasi ketika service dipilih
            // Bisa digunakan untuk mengisi form pemesanan atau pengiriman
            alert(`Service selected!\n\nCourier: ${courier.toUpperCase()}\nService: ${service}\nCost: Rp ${cost.toLocaleString('id-ID')}\nDescription: ${description}`);
            
            // Contoh: Simpan ke session atau redirect ke halaman pemesanan
            // window.location.href = '/pemesanan/create?courier=' + courier + '&service=' + service + '&cost=' + cost;
        }
        
        function showError(message) {
            $('#shippingResults').hide();
            $('#emptyState').show();
            alert('Error: ' + message);
        }
    });
    </script>
</body>
</html>