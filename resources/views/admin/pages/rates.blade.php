@extends('layouts.app', ['page' => __('Rate'), 'pageSlug' => 'rates'])

@section('content')
  <style>
    .table-sticky-container {
      max-height: 75vh;
      overflow: auto;
    }
    .table-sticky {
      border-collapse: separate;
      border-spacing: 0;
    }
    /* Sticky Headers */
    .table-sticky thead tr:nth-child(1) th {
      position: sticky;
      top: 0;
      z-index: 10;
      background-color: #27293d !important; /* Match card background */
    }
    .table-sticky thead tr:nth-child(2) th {
      position: sticky;
      top: 42px; /* Height of the first header row */
      z-index: 10;
      background-color: #27293d !important;
    }
    /* Sticky First Column (Room Name) */
    .table-sticky th:first-child,
    .table-sticky td:first-child {
      position: sticky;
      left: 0;
      background-color: #27293d !important;
      z-index: 11;
      min-width: 200px;
    }
    /* Highest priority for the top-left intersection cell */
    .table-sticky thead tr:nth-child(1) th:first-child {
      z-index: 12;
    }
  </style>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Room Rates & Availability (Next 30 Days)</h4>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bulkUpdateRateModal">Bulk Update</button>
          </div>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success">
              <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
              </button>
              <span>{{ session('success') }}</span>
            </div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger">
              <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
              </button>
              <span>{{ session('error') }}</span>
            </div>
          @endif
          <div class="table-responsive table-sticky-container">
            <table class="table table-bordered table-hover table-sticky">
              <thead class="text-primary">
                <tr>
                  <th rowspan="2" class="align-middle">Room Name</th>
                  @foreach($dates as $date)
                    <th colspan="2" class="text-center">{{ $date->format('d/m') }}</th>
                  @endforeach
                </tr>
                <tr>
                  @foreach($dates as $date)
                    <th class="text-center">Price</th>
                    <th class="text-center">Stock</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($rooms as $room)
                  <tr>
                    <td><strong>{{ $room->title }}</strong> <br><small>({{ $room->code }})</small></td>
                    @foreach($dates as $date)
                      @php
                        $currentRate = $rates->where('tgl', $date->format('Y-m-d'))->where('kode_kamar', $room->code)->first();
                      @endphp
                      <td class="text-center" style="font-size: 0.85rem; white-space: nowrap;">
                        @if($currentRate)
                          Rp {{ number_format((float)$currentRate->harga, 0, ',', '.') }}
                        @else
                          -
                        @endif
                        <button type="button" class="btn btn-link btn-icon btn-sm edit-rate-btn p-0" 
                          data-date="{{ $date->format('Y-m-d') }}"
                          data-date-display="{{ $date->format('D, d M Y') }}"
                          data-room-code="{{ $room->code }}"
                          data-room-name="{{ $room->title }}"
                          data-price="{{ $currentRate->harga ?? '' }}"
                          data-stock="{{ $currentRate->stok ?? 0 }}">
                          <i class="tim-icons icon-pencil text-info"></i>
                        </button>
                      </td>
                      <td class="text-center {{ $currentRate && $currentRate->stok <= 0 ? 'text-danger fw-bold' : '' }}">
                        {{ $currentRate ? $currentRate->stok : '0' }}
                      </td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Updating Rate and Stock -->
  <div class="modal fade" id="updateRateModal" tabindex="-1" role="dialog" aria-labelledby="updateRateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateRateModalLabel">Update Rate & Availability</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="tim-icons icon-simple-remove"></i>
          </button>
        </div>
        <form action="{{ route('rates.update') }}" method="POST">
          @csrf
          <div class="modal-body">
            <input type="hidden" name="tgl" id="modal_tgl">
            <input type="hidden" name="kode_kamar" id="modal_kode_kamar">
            
            <div class="form-group">
              <label>Room</label>
              <input type="text" id="modal_room_name" class="form-control text-white" readonly>
            </div>
            <div class="form-group">
              <label>Date</label>
              <input type="text" id="modal_date_display" class="form-control text-white" readonly>
            </div>
            <div class="form-group">
              <label>Price (Rp)</label>
              <input type="number" name="harga" id="modal_harga" class="form-control text-info" required placeholder="Enter price">
            </div>
            <div class="form-group">
              <label>Stock (Allotment)</label>
              <input type="number" name="stok" id="modal_stok" class="form-control text-info" required placeholder="Enter available rooms">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Rate</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Bulk Updating Rate and Stock -->
  <div class="modal fade" id="bulkUpdateRateModal" tabindex="-1" role="dialog" aria-labelledby="bulkUpdateRateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bulkUpdateRateModalLabel">Bulk Update Rates & Availability</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="tim-icons icon-simple-remove"></i>
          </button>
        </div>
        <form action="{{ route('rates.bulkUpdate') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Select Room</label>
              <select name="kode_kamar" class="form-control text-info" required>
                <option value="" disabled selected>Choose a room</option>
                @foreach($rooms as $room)
                  <option value="{{ $room->code }}">{{ $room->title }} ({{ $room->code }})</option>
                @endforeach
              </select>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="date" name="start_date" class="form-control text-info" required min="{{ date('Y-m-d') }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="date" name="end_date" class="form-control text-info" required min="{{ date('Y-m-d') }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Price (Rp)</label>
              <input type="number" name="harga" class="form-control text-info" required placeholder="Enter price">
            </div>
            <div class="form-group">
              <label>Stock (Allotment)</label>
              <input type="number" name="stok" class="form-control text-info" required placeholder="Enter available rooms">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Apply Bulk Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('js')
  <script>
    $(document).ready(function() {
      $('.edit-rate-btn').on('click', function() {
        const btn = $(this);
        $('#modal_tgl').val(btn.data('date'));
        $('#modal_kode_kamar').val(btn.data('room-code'));
        $('#modal_room_name').val(btn.data('room-name'));
        $('#modal_date_display').val(btn.data('date-display'));
        $('#modal_harga').val(btn.data('price'));
        $('#modal_stok').val(btn.data('stock'));

        $('#updateRateModal').modal('show');
      });
    });
  </script>
  @endpush
@endsection
