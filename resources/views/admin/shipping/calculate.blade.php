@extends('layouts.app')

@section('title', 'Calculate Shipping Cost')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shipping-fast"></i>
                        Calculate Shipping Cost
                    </h3>
                </div>
                <div class="card-body">
                    <!-- API Status Indicator -->
                    <div class="alert alert-info" id="apiStatus">
                        <i class="fas fa-sync fa-spin"></i> Checking API status...
                    </div>

                    <form id="shippingForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer *</label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id_customer }}" 
                                                    data-address="{{ $customer->alamat }}"
                                                    data-has-location="{{ $customer->hasLocationData() ? '1' : '0' }}">
                                                {{ $customer->nama }} - {{ $customer->email }}
                                                @if(!$customer->hasLocationData())
                                                    <span class="text-danger">(No Location Data)</span>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted" id="customerAddress"></small>
                                    <small class="form-text text-danger" id="customerLocationWarning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i> This customer address doesn't have location data
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="weight">Weight (gram) *</label>
                                    <input type="number" name="weight" id="weight" class="form-control" 
                                           value="1000" min="1" max="30000" required>
                                    <small class="form-text text-muted">Max 30,000 grams (30kg)</small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="origin_city_id">Origin City ID *</label>
                                    <input type="number" name="origin_city_id" id="origin_city_id" 
                                           class="form-control" value="{{ $defaultOriginCityId }}" required>
                                    <small class="form-text text-muted">
                                        Your store's city ID
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="courier">Courier *</label>
                                    <select name="courier" id="courier" class="form-control" required>
                                        <option value="">Select Courier</option>
                                        @foreach($couriers as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary" id="calculateBtn">
                                            <i class="fas fa-calculator"></i> Calculate Shipping Cost
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="resetBtn">
                                            <i class="fas fa-redo"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="shippingResults" class="mt-4" style="display: none;">
                        <h4>
                            <i class="fas fa-list-alt"></i>
                            Shipping Results
                        </h4>
                        <div id="resultsContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    padding: 6px 12px;
}
.shipping-service {
    border-left: 4px solid #007bff;
    margin-bottom: 10px;
}
.service-cost {
    font-size: 1.2em;
    font-weight: bold;
    color: #28a745;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        
        $('#customerAddress').text(address || 'No address available');
        
        if (hasLocation == '0') {
            $('#customerLocationWarning').show();
            $('#calculateBtn').prop('disabled', true);
        } else {
            $('#customerLocationWarning').hide();
            $('#calculateBtn').prop('disabled', false);
        }
    });

    // Reset button
    $('#resetBtn').on('click', function() {
        $('#shippingForm')[0].reset();
        $('#customer_id').val(null).trigger('change');
        $('#shippingResults').hide();
        $('#customerAddress').text('');
        $('#customerLocationWarning').hide();
        $('#calculateBtn').prop('disabled', false);
    });

    // Form submission
    $('#shippingForm').on('submit', function(e) {
        e.preventDefault();
        
        const calculateBtn = $('#calculateBtn');
        const originalText = calculateBtn.html();
        
        calculateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Calculating...');
        
        $.ajax({
            url: '{{ route("shipping.calculate-cost") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    displayShippingResults(response);
                    $('#shippingResults').show();
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
                calculateBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    function checkApiStatus() {
        $.ajax({
            url: '{{ route("shipping.api-status") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#apiStatus').removeClass('alert-info').addClass('alert-success')
                        .html('<i class="fas fa-check-circle"></i> RajaOngkir API is working properly');
                } else {
                    $('#apiStatus').removeClass('alert-info').addClass('alert-danger')
                        .html('<i class="fas fa-exclamation-triangle"></i> RajaOngkir API is not responding');
                }
            },
            error: function() {
                $('#apiStatus').removeClass('alert-info').addClass('alert-danger')
                    .html('<i class="fas fa-exclamation-triangle"></i> Failed to check API status');
            }
        });
    }
    
    function displayShippingResults(data) {
        let html = `
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> ${data.customer.nama}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-map-marker-alt"></i> Address:</strong><br>
                            ${data.customer.street_address}<br>
                            ${data.customer.city_name}, ${data.customer.province_name}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-info-circle"></i> Shipment Details:</strong><br>
                            Weight: ${data.weight} gram<br>
                            Courier: ${data.courier.toUpperCase()}<br>
                            Destination City ID: ${data.destination_city_id}</p>
                        </div>
                    </div>
                    
                    <h5><i class="fas fa-truck"></i> Available Services:</h5>
                    <div class="row">
        `;
        
        if (data.shipping_costs && data.shipping_costs.length > 0) {
            data.shipping_costs.forEach(service => {
                const cost = service.cost[0];
                const formattedCost = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(cost.value);
                
                html += `
                    <div class="col-md-6 mb-3">
                        <div class="card shipping-service">
                            <div class="card-body">
                                <h6 class="card-title">${service.service}</h6>
                                <p class="card-text">${service.description}</p>
                                <p class="service-cost">${formattedCost}</p>
                                <p class="card-text"><small class="text-muted">Estimation: ${cost.etd} days</small></p>
                                <button class="btn btn-sm btn-success select-service" 
                                        data-service="${service.service}"
                                        data-cost="${cost.value}"
                                        data-description="${service.description}"
                                        data-courier="${data.courier}">
                                    <i class="fas fa-check"></i> Select This Service
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> No shipping services available for this route.
                    </div>
                </div>
            `;
        }
        
        html += `
                    </div>
                </div>
            </div>
        `;
        
        $('#resultsContent').html(html);
        
        // Add event listeners for select buttons
        $('.select-service').on('click', function() {
            const service = $(this).data('service');
            const cost = $(this).data('cost');
            const description = $(this).data('description');
            const courier = $(this).data('courier');
            
            // Tampilkan konfirmasi
            const formattedCost = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(cost);
            
            if (confirm(`Select ${service} - ${formattedCost}?`)) {
                // Di sini Anda bisa mengisi form pengiriman atau melakukan action lainnya
                onServiceSelected(service, cost, description, courier);
            }
        });
    }
    
    function onServiceSelected(service, cost, description, courier) {
        // Implementasi ketika service dipilih
        // Contoh: isi form pengiriman atau redirect ke halaman pemesanan
        alert(`Service selected: ${service}\nCost: Rp ${cost.toLocaleString('id-ID')}\nCourier: ${courier.toUpperCase()}`);
        
        // Contoh: Auto-fill form pengiriman (jika ada)
        // $('#biaya_ongkir').val(cost);
        // $('#layanan').val(service + ' - ' + description);
        // $('#ekspedisi').val(courier.toUpperCase());
    }
    
    function showError(message) {
        $('#shippingResults').hide();
        alert('Error: ' + message);
    }
});
</script>
@endpush