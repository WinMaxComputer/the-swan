@extends('layouts.app', ['page' => __('Properti'), 'pageSlug' => 'properti'])

@section('content')
  <style>
    .booking-calendar-wrap {
      max-height: 72vh;
      overflow: auto;
    }

    .booking-calendar {
      border-collapse: separate;
      border-spacing: 0;
      min-width: 1100px;
    }

    .booking-calendar thead th {
      position: sticky;
      top: 0;
      z-index: 10;
      background-color: #27293d !important;
      border-bottom: 1px solid #344675 !important;
      white-space: nowrap;
    }

    .booking-calendar th:first-child,
    .booking-calendar td:first-child {
      position: sticky;
      left: 0;
      z-index: 11;
      width: 260px;
      min-width: 260px;
      background-color: #27293d !important;
      border-right: 1px solid #344675 !important;
    }

    .booking-calendar th.unit-column,
    .booking-calendar td.unit-column {
      position: sticky;
      left: 260px;
      z-index: 11;
      width: 92px;
      min-width: 92px;
      background-color: #27293d !important;
      border-right: 1px solid #344675 !important;
      white-space: nowrap;
    }

    .booking-calendar thead th:first-child {
      z-index: 12;
    }

    .booking-calendar thead th.unit-column {
      z-index: 12;
    }

    .calendar-day {
      min-width: 145px;
      height: 64px;
      vertical-align: top !important;
    }

    .calendar-booking-day {
      min-width: 72px;
      height: 58px;
      padding: 6px !important;
      vertical-align: middle !important;
    }

    .date-band-0 {
      background-color: rgba(29, 140, 248, 0.08) !important;
    }

    .date-band-1 {
      background-color: rgba(0, 242, 195, 0.07) !important;
    }

    .date-band-2 {
      background-color: rgba(255, 141, 114, 0.08) !important;
    }

    .date-band-3 {
      background-color: rgba(253, 93, 147, 0.07) !important;
    }

    .calendar-past-date {
      background-color: rgba(127, 138, 168, 0.18) !important;
      color: #8d96ae !important;
    }

    .booking-calendar thead th.calendar-past-date {
      background-color: rgba(127, 138, 168, 0.28) !important;
    }

    .calendar-past-date .availability-line {
      color: #8d96ae;
    }

    .calendar-past-date .calendar-reservation::before {
      background: rgba(127, 138, 168, 0.28);
      box-shadow: inset 4px 0 0 #7f8aa8;
    }

    .calendar-gap {
      background-color: rgba(30, 30, 47, 0.25);
    }

    .calendar-day.is-weekend {
      box-shadow: inset 0 0 0 9999px rgba(52, 70, 117, 0.12);
    }

    .calendar-cell-empty {
      color: #7f8aa8;
      font-size: 0.78rem;
    }

    .calendar-reservation {
      position: relative;
      background: transparent;
      padding: 7px 18px;
      font-size: 0.78rem;
      line-height: 1.25;
      min-height: 44px;
      overflow: visible;
      white-space: nowrap;
      cursor: grab;
    }

    .calendar-reservation:active {
      cursor: grabbing;
    }

    .calendar-reservation.is-dragging {
      opacity: 0.45;
    }

    .calendar-room-row.is-drop-target td:not(.unit-column) {
      box-shadow: inset 0 0 0 2px rgba(0, 242, 195, 0.35);
    }

    .calendar-reservation::before {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 0;
      background: linear-gradient(90deg, rgba(29, 140, 248, 0.32), rgba(0, 242, 195, 0.18));
      box-shadow: inset 4px 0 0 #1d8cf8;
      clip-path: polygon(14px 0, 100% 0, calc(100% - 14px) 100%, 0 100%);
    }

    .calendar-reservation strong,
    .calendar-reservation small {
      position: relative;
      z-index: 1;
    }

    .calendar-reservation small {
      display: block;
      color: #cad1e8;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .booking-dates {
      color: #ffdf8e;
      font-style: italic;
    }

    .room-meta,
    .day-meta {
      color: #9a9a9a;
      font-size: 0.75rem;
    }

    .availability-line {
      display: flex;
      justify-content: space-between;
      gap: 8px;
      color: #cad1e8;
      font-size: 0.76rem;
    }

    .summary-tile {
      border: 1px solid #344675;
      border-radius: 6px;
      padding: 14px 16px;
      min-height: 90px;
      background: rgba(30, 30, 47, 0.45);
    }

    .summary-tile .value {
      color: #ffffff;
      font-size: 1.45rem;
      font-weight: 600;
      margin-bottom: 2px;
    }

    .summary-tile .label {
      color: #9a9a9a;
      font-size: 0.78rem;
      text-transform: uppercase;
    }

    @media (max-width: 767.98px) {
      .booking-calendar-wrap {
        max-height: 68vh;
      }

      .booking-calendar th:first-child,
      .booking-calendar td:first-child {
        width: 210px;
        min-width: 210px;
      }

      .booking-calendar th.unit-column,
      .booking-calendar td.unit-column {
        left: 210px;
        width: 82px;
        min-width: 82px;
      }
    }
  </style>

  <div class="row">
    <div class="col-md-3">
      <div class="summary-tile mb-3">
        <div class="value">{{ $summary['rooms'] }}</div>
        <div class="label">Rooms</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-tile mb-3">
        <div class="value">{{ $summary['booked_nights'] }}</div>
        <div class="label">Booked Nights</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-tile mb-3">
        <div class="value">{{ $summary['arrivals'] }}</div>
        <div class="label">Arrivals</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-tile mb-3">
        <div class="value">{{ $summary['departures'] }}</div>
        <div class="label">Departures</div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
            <div>
              <h4 class="card-title mb-1">Room Booking Calendar</h4>
              <p class="card-category mb-0">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
            </div>
            <form action="{{ route('pages.properti') }}" method="GET" class="mt-3 mt-lg-0">
              <div class="form-row align-items-end">
                <div class="col-auto">
                  <label class="text-muted mb-1">Start</label>
                  <input type="date" name="start" class="form-control text-info" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-auto">
                  <label class="text-muted mb-1">Days</label>
                  <select name="days" class="form-control text-info">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7</option>
                    <option value="14" {{ $days == 14 ? 'selected' : '' }}>14</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30</option>
                    <option value="60" {{ $days == 60 ? 'selected' : '' }}>60</option>
                  </select>
                </div>
                <div class="col-auto">
                  <label class="text-muted mb-1">Lang</label>
                  <select name="lang" class="form-control text-info">
                    <option value="en" {{ $lang == 'en' ? 'selected' : '' }}>English</option>
                    <option value="id" {{ $lang == 'id' ? 'selected' : '' }}>Indonesia</option>
                  </select>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary btn-sm mb-0">Apply</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card-body">
          <div class="booking-calendar-wrap">
            <table class="table table-bordered booking-calendar">
              <thead class="text-primary">
                <tr>
                  <th>Room</th>
                  <th class="unit-column text-center">Unit</th>
                  @foreach($dates as $date)
                    <th colspan="2" class="text-center date-band-{{ $loop->index % 4 }} {{ $date->lt(today()) ? 'calendar-past-date' : '' }}">
                      {{ $date->format('d M') }}
                      <div class="day-meta">{{ $date->format('D') }}</div>
                    </th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @forelse($rooms as $room)
                  @php
                    $roomBlocks = $reservationBlocksByRoom->get($room->code, collect());
                    $allotmentRows = range(1, max(1, (int) ($room->alotment ?? 0)));
                    $rowspan = count($allotmentRows) + 1;
                  @endphp
                  <tr>
                    <td rowspan="{{ $rowspan }}">
                      <strong>{{ $room->title }}</strong>
                      <div class="room-meta">
                        {{ $room->code }}
                        @if(!empty($room->real_name))
                          &middot; {{ $room->real_name }}
                        @endif
                      </div>
                      <div class="room-meta">Allotment: {{ $room->alotment ?? 0 }}</div>
                    </td>
                    <td class="unit-column text-center">
                      <span class="badge badge-info">Stock</span>
                    </td>
                    @foreach($dates as $date)
                      @php
                        $dateKey = $date->format('Y-m-d');
                        $lookupKey = $room->code . '|' . $dateKey;
                        $dailyReservations = $reservationsByRoomDate->get($lookupKey, collect());
                        $occupiedReservations = $dailyReservations->where('room_status', '!=', 'cekout');
                        $dailyRate = $rates->get($lookupKey);
                        $availableRooms = $dailyRate->stok ?? max((int) ($room->alotment ?? 0) - $occupiedReservations->count(), 0);
                      @endphp
                      <td colspan="2" class="calendar-day date-band-{{ $loop->index % 4 }} {{ $date->lt(today()) ? 'calendar-past-date' : '' }} {{ $date->isWeekend() ? 'is-weekend' : '' }}">
                        <div class="availability-line">
                          <span>{{ $occupiedReservations->count() }} booked</span>
                          <span>{{ $availableRooms }} left</span>
                        </div>
                      </td>
                    @endforeach
                  </tr>
                  @foreach($allotmentRows as $roomNo)
                    @php
                      $blocks = $roomBlocks->get((string) $roomNo, collect());
                      $cursorUnit = 0;
                      $totalUnits = $dates->count() * 2;
                    @endphp
                    <tr class="calendar-room-row"
                        data-room-code="{{ $room->code }}"
                        data-room-no="{{ $roomNo }}">
                      <td class="unit-column text-center">
                        <strong>Room {{ $roomNo }}</strong>
                      </td>
                      @foreach($blocks as $block)
                        @php
                          $renderStartUnit = max($block->start_unit, $cursorUnit);
                          $gapUnits = max($renderStartUnit - $cursorUnit, 0);
                          $renderSpanUnits = $block->end_unit - $renderStartUnit + 1;
                        @endphp

                        @if($gapUnits > 0)
                          @for($gapUnit = $cursorUnit; $gapUnit < $renderStartUnit; $gapUnit++)
                            <td class="calendar-booking-day calendar-gap date-band-{{ (int) floor($gapUnit / 2) % 4 }} {{ $dates->get((int) floor($gapUnit / 2))->lt(today()) ? 'calendar-past-date' : '' }}"></td>
                          @endfor
                        @endif

                        @if($renderSpanUnits > 0)
                          <td colspan="{{ $renderSpanUnits }}" class="calendar-booking-day date-band-{{ (int) floor($renderStartUnit / 2) % 4 }} {{ \Carbon\Carbon::parse($block->check_out)->lt(today()) ? 'calendar-past-date' : '' }}">
                            <div class="calendar-reservation"
                                 draggable="true"
                                 data-no-reservasi="{{ $block->no_reservasi }}"
                                 data-room-code="{{ $room->code }}"
                                 data-room-no="{{ $roomNo }}"
                                 data-guest-name="{{ $block->guest_name ?? 'Guest' }}"
                                 data-guest-email="{{ $block->guest_email ?? '-' }}"
                                 data-check-in="{{ \Carbon\Carbon::parse($block->check_in)->format('d M Y') }}"
                                 data-check-out="{{ \Carbon\Carbon::parse($block->check_out)->format('d M Y') }}"
                                 data-payment-status="{{ $block->payment_status ?? 'No payment status' }}"
                                 data-book-status="{{ $block->book_status ?? '-' }}"
                                 title="{{ $block->no_reservasi }} | {{ $block->guest_email }}">
                              <strong>{{ $block->guest_name ?? 'Guest' }}</strong>
                              <small>
                                <span class="booking-dates">
                                  {{ \Carbon\Carbon::parse($block->check_in)->format('d M') }}
                                  -
                                  {{ \Carbon\Carbon::parse($block->check_out)->format('d M') }}
                                </span>
                                &middot; {{ $block->payment_status ?? 'No payment status' }}
                              </small>
                            </div>
                          </td>
                        @endif

                        @php
                          $cursorUnit = $block->end_unit + 1;
                        @endphp
                      @endforeach

                      @php
                        $remainingUnits = $totalUnits - $cursorUnit;
                      @endphp

                      @if($remainingUnits > 0)
                        @for($remainingUnit = $cursorUnit; $remainingUnit < $totalUnits; $remainingUnit++)
                          <td class="calendar-booking-day calendar-gap date-band-{{ (int) floor($remainingUnit / 2) % 4 }} {{ $dates->get((int) floor($remainingUnit / 2))->lt(today()) ? 'calendar-past-date' : '' }}"></td>
                        @endfor
                      @endif
                    </tr>
                  @endforeach
                @empty
                  <tr>
                    <td colspan="{{ ($dates->count() * 2) + 2 }}" class="text-center text-muted py-5">
                      No rooms found for this language.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="reservationDetailModal" tabindex="-1" role="dialog" aria-labelledby="reservationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reservationDetailModalLabel">Reservation Detail</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="tim-icons icon-simple-remove"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <small class="text-muted d-block">Reservation No</small>
            <strong id="modal_reservation_no">-</strong>
          </div>
          <div class="mb-3">
            <small class="text-muted d-block">Guest</small>
            <strong id="modal_guest_name">-</strong>
            <div class="room-meta" id="modal_guest_email">-</div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <small class="text-muted d-block">Check In</small>
              <strong id="modal_check_in">-</strong>
            </div>
            <div class="col-md-6 mb-3">
              <small class="text-muted d-block">Check Out</small>
              <strong id="modal_check_out">-</strong>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <small class="text-muted d-block">Room</small>
              <strong id="modal_room">-</strong>
            </div>
            <div class="col-md-6 mb-3">
              <small class="text-muted d-block">Payment Status</small>
              <strong id="modal_payment_status">-</strong>
            </div>
          </div>
          <div>
            <small class="text-muted d-block">Booking Status</small>
            <strong id="modal_book_status">-</strong>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('js')
    @if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
      <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @endif
    <script>
      $(document).ready(function() {
        let draggedReservation = null;
        let draggedFromRoom = null;
        let dragStartedAt = 0;
        let calendarDigest = null;
        let reloadScheduled = false;

        function scheduleCalendarReload() {
          if (reloadScheduled) {
            return;
          }

          reloadScheduled = true;
          setTimeout(function() {
            window.location.reload();
          }, 500);
        }

        $('.calendar-reservation').on('click', function(event) {
          const justDragged = Date.now() - dragStartedAt < 350;

          if (justDragged) {
            event.preventDefault();
            return;
          }

          $('#modal_reservation_no').text($(this).data('no-reservasi') || '-');
          $('#modal_guest_name').text($(this).data('guest-name') || '-');
          $('#modal_guest_email').text($(this).data('guest-email') || '-');
          $('#modal_check_in').text($(this).data('check-in') || '-');
          $('#modal_check_out').text($(this).data('check-out') || '-');
          $('#modal_room').text('Room ' + ($(this).data('room-no') || '-'));
          $('#modal_payment_status').text($(this).data('payment-status') || '-');
          $('#modal_book_status').text($(this).data('book-status') || '-');
          $('#reservationDetailModal').modal('show');
        });

        $('.calendar-reservation').on('dragstart', function(event) {
          const originalEvent = event.originalEvent;
          draggedReservation = {
            noReservasi: $(this).data('no-reservasi'),
            roomCode: $(this).data('room-code')
          };
          draggedFromRoom = String($(this).data('room-no'));
          dragStartedAt = Date.now();

          $(this).addClass('is-dragging');
          originalEvent.dataTransfer.effectAllowed = 'move';
          originalEvent.dataTransfer.setData('text/plain', draggedReservation.noReservasi);
        });

        $('.calendar-reservation').on('dragend', function() {
          $(this).removeClass('is-dragging');
          $('.calendar-room-row').removeClass('is-drop-target');
          draggedReservation = null;
          draggedFromRoom = null;
        });

        $('.calendar-room-row').on('dragover', function(event) {
          if (!draggedReservation || $(this).data('room-code') !== draggedReservation.roomCode) {
            return;
          }

          event.preventDefault();
          event.originalEvent.dataTransfer.dropEffect = 'move';
          $(this).addClass('is-drop-target');
        });

        $('.calendar-room-row').on('dragleave', function() {
          $(this).removeClass('is-drop-target');
        });

        $('.calendar-room-row').on('drop', function(event) {
          event.preventDefault();
          $(this).removeClass('is-drop-target');

          if (!draggedReservation) {
            return;
          }

          const targetRoomNo = String($(this).data('room-no'));
          if (targetRoomNo === draggedFromRoom) {
            return;
          }

          $.ajax({
            type: 'POST',
            url: '{{ route('pages.properti.updateReservationRoom') }}',
            data: {
              _token: '{{ csrf_token() }}',
              no_reservasi: draggedReservation.noReservasi,
              kode_unit: draggedReservation.roomCode,
              room_no: targetRoomNo
            },
            success: function() {
              window.location.reload();
            },
            error: function(xhr) {
              const message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Reservation could not be moved.';
              alert(message);
            }
          });
        });

        function checkCalendarDigest() {
          $.get('{{ route('pages.properti.digest') }}', function(result) {
            if (!result || !result.digest) {
              return;
            }

            if (calendarDigest && calendarDigest !== result.digest) {
              scheduleCalendarReload();
              return;
            }

            calendarDigest = result.digest;
          });
        }

        checkCalendarDigest();
        setInterval(checkCalendarDigest, 15000);

        @if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
          const propertyCalendarPusherOptions = {
            cluster: @json(env('PUSHER_APP_CLUSTER', 'mt1')),
            forceTLS: @json(env('PUSHER_SCHEME', 'https') === 'https'),
            enabledTransports: ['ws', 'wss']
          };

          @if(env('PUSHER_HOST'))
            propertyCalendarPusherOptions.wsHost = @json(env('PUSHER_HOST'));
            propertyCalendarPusherOptions.wsPort = Number(@json(env('PUSHER_PORT', 443)));
            propertyCalendarPusherOptions.wssPort = Number(@json(env('PUSHER_PORT', 443)));
          @endif

          const propertyCalendarPusher = new Pusher(@json(config('broadcasting.connections.pusher.key')), propertyCalendarPusherOptions);

          propertyCalendarPusher
            .subscribe('property-calendar')
            .bind('property-calendar.changed', scheduleCalendarReload);
        @endif
      });
    </script>
  @endpush
@endsection
