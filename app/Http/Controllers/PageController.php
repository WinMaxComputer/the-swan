<?php

namespace App\Http\Controllers;
use App\Events\PropertyCalendarChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PageController extends Controller
{
    /**
     * Display icons page
     *
     * @return \Illuminate\View\View
     */
    public function icons()
    {
        return view('admin.pages.icons');
    }

    public function properti(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $days = min(max((int) $request->query('days', 30), 7), 60);
        try {
            $startDate = Carbon::parse($request->query('start', Carbon::now()->format('Y-m-d')))->startOfDay();
        } catch (\Exception $exception) {
            $startDate = Carbon::now()->startOfDay();
        }
        $endDate = $startDate->copy()->addDays($days - 1);

        $dates = collect(CarbonPeriod::create($startDate, $endDate))->values();

        $rooms = DB::table('bookings')
            ->whereRaw('LOWER(lang) = ?', [strtolower($lang)])
            ->select('code', 'title', 'real_name', 'alotment')
            ->orderBy('title')
            ->get();

        $roomCodes = $rooms->pluck('code');

        $rates = DB::table('rates')
            ->whereIn('kode_kamar', $roomCodes)
            ->whereBetween('tgl', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function ($rate) {
                return $rate->kode_kamar . '|' . $rate->tgl;
            });

        $reservationDetails = DB::table('reservation_room_detail as detail')
            ->leftJoin('reservations as reservation', 'reservation.no_reservasi', '=', 'detail.no_reservasi')
            ->whereIn('detail.kode_unit', $roomCodes)
            ->whereBetween('detail.tgl', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->select(
                'detail.no_reservasi',
                'detail.kode_unit',
                'detail.tgl',
                'detail.no_room',
                'detail.harga',
                'detail.status as room_status',
                'reservation.id as reservation_id',
                'reservation.guest_name',
                'reservation.guest_email',
                'reservation.cek_in',
                'reservation.cek_out',
                'reservation.status as payment_status',
                'reservation.book_status',
                'reservation.room_no as reservation_room_no'
            )
            ->orderBy('detail.tgl')
            ->orderBy('detail.no_room')
            ->get();

        $reservationsByRoomDate = $reservationDetails->groupBy(function ($reservation) {
            return $reservation->kode_unit . '|' . $reservation->tgl;
        });

        $reservationBlocksByRoom = $reservationDetails
            ->groupBy('kode_unit')
            ->map(function ($roomReservations, $roomCode) use ($rooms, $startDate, $endDate) {
                $roomAllotment = (int) optional($rooms->firstWhere('code', $roomCode))->alotment;
                $roomAllotment = max(1, $roomAllotment);

                $blocks = $roomReservations
                    ->groupBy('no_reservasi')
                    ->map(function ($reservationRows) use ($startDate, $endDate) {
                        $firstRow = $reservationRows->sortBy('tgl')->first();
                        $lastRow = $reservationRows->sortByDesc('tgl')->first();
                        $checkInDate = !empty($firstRow->cek_in)
                            ? Carbon::parse($firstRow->cek_in)->startOfDay()
                            : Carbon::parse($reservationRows->min('tgl'));
                        $checkOutDate = !empty($lastRow->cek_out)
                            ? Carbon::parse($lastRow->cek_out)->startOfDay()
                            : Carbon::parse($reservationRows->max('tgl'));
                        $checkoutRow = $reservationRows->firstWhere('room_status', 'cekout');
                        if ($checkoutRow) {
                            $checkOutDate = Carbon::parse($checkoutRow->tgl)->startOfDay();
                        }

                        $visibleStartUnit = 0;
                        $visibleEndUnit = ($startDate->diffInDays($endDate) * 2) + 1;
                        $startUnit = ($startDate->diffInDays($checkInDate, false) * 2) + 1;
                        $endUnit = $startDate->diffInDays($checkOutDate, false) * 2;
                        $blockStartUnit = max($startUnit, $visibleStartUnit);
                        $blockEndUnit = min($endUnit, $visibleEndUnit);

                        if ($blockEndUnit < $blockStartUnit) {
                            return null;
                        }

                        $blockStart = $startDate->copy()->addDays((int) floor($blockStartUnit / 2));
                        $blockEnd = $startDate->copy()->addDays((int) floor($blockEndUnit / 2));

                        return (object) [
                            'no_reservasi' => $firstRow->no_reservasi,
                            'reservation_id' => $firstRow->reservation_id,
                            'room_no' => $firstRow->reservation_room_no ?: $firstRow->no_room,
                            'guest_name' => $firstRow->guest_name,
                            'guest_email' => $firstRow->guest_email,
                            'payment_status' => $firstRow->payment_status,
                            'book_status' => $firstRow->book_status,
                            'start_date' => $blockStart->format('Y-m-d'),
                            'end_date' => $blockEnd->format('Y-m-d'),
                            'start_unit' => $blockStartUnit,
                            'end_unit' => $blockEndUnit,
                            'start_index' => $blockStartUnit,
                            'end_index' => $blockEndUnit,
                            'check_in' => $firstRow->cek_in ?: $firstRow->tgl,
                            'check_out' => $lastRow->cek_out ?: $lastRow->tgl,
                            'span_units' => $blockEndUnit - $blockStartUnit + 1,
                            'span_days' => $blockStart->diffInDays($blockEnd) + 1,
                        ];
                    })
                    ->filter()
                    ->sortBy([
                        ['room_no', 'asc'],
                        ['start_date', 'asc'],
                    ])
                    ->values();

                $occupiedRangesByRoom = [];
                $assignedBlocks = $blocks->map(function ($block) use ($roomAllotment, &$occupiedRangesByRoom) {
                    $preferredRoomNo = (int) $block->room_no;
                    $candidateRooms = collect([$preferredRoomNo])
                        ->merge(range(1, $roomAllotment))
                        ->filter(function ($roomNo) use ($roomAllotment) {
                            return $roomNo >= 1 && $roomNo <= $roomAllotment;
                        })
                        ->unique()
                        ->values();

                    $assignedRoomNo = $candidateRooms->first(function ($roomNo) use ($block, $occupiedRangesByRoom) {
                        $ranges = $occupiedRangesByRoom[$roomNo] ?? [];

                        foreach ($ranges as $range) {
                            $overlaps = $block->start_index <= $range['end'] && $block->end_index >= $range['start'];
                            if ($overlaps) {
                                return false;
                            }
                        }

                        return true;
                    });

                    $assignedRoomNo = $assignedRoomNo ?: $preferredRoomNo;
                    $block->visual_room_no = (string) $assignedRoomNo;

                    $occupiedRangesByRoom[$assignedRoomNo][] = [
                        'start' => $block->start_index,
                        'end' => $block->end_index,
                    ];

                    return $block;
                });

                return $assignedBlocks->groupBy('visual_room_no');
            });

        $activeReservations = $reservationDetails->where('room_status', '!=', 'cekout');

        $summary = [
            'rooms' => $rooms->count(),
            'booked_nights' => $activeReservations->count(),
            'arrivals' => $reservationDetails->where('room_status', 'cekin')->count(),
            'departures' => $reservationDetails->where('room_status', 'cekout')->count(),
        ];

        return view('admin.pages.properti', compact(
            'dates',
            'rooms',
            'rates',
            'reservationsByRoomDate',
            'reservationBlocksByRoom',
            'summary',
            'startDate',
            'endDate',
            'days',
            'lang'
        ));
    }

    public function updateReservationRoom(Request $request)
    {
        $request->validate([
            'no_reservasi' => 'required|string',
            'kode_unit' => 'required|string',
            'room_no' => 'required|integer|min:1',
        ]);

        $room = DB::table('bookings')
            ->where('code', $request->kode_unit)
            ->orderByDesc('id')
            ->first();

        if (!$room || (int) $request->room_no > (int) $room->alotment) {
            return response()->json([
                'message' => 'Target room is outside this property allotment.',
            ], 422);
        }

        $occupiedDates = DB::table('reservation_room_detail')
            ->where('no_reservasi', $request->no_reservasi)
            ->where('kode_unit', $request->kode_unit)
            ->where('status', '!=', 'cekout')
            ->pluck('tgl');

        if ($occupiedDates->isEmpty()) {
            return response()->json([
                'message' => 'Reservation detail was not found.',
            ], 404);
        }

        $hasConflict = DB::table('reservation_room_detail')
            ->where('kode_unit', $request->kode_unit)
            ->where('no_room', (string) $request->room_no)
            ->where('no_reservasi', '!=', $request->no_reservasi)
            ->where('status', '!=', 'cekout')
            ->whereIn('tgl', $occupiedDates)
            ->exists();

        if ($hasConflict) {
            return response()->json([
                'message' => 'Target room already has a reservation on one of these dates.',
            ], 422);
        }

        DB::transaction(function () use ($request) {
            DB::table('reservation_room_detail')
                ->where('no_reservasi', $request->no_reservasi)
                ->where('kode_unit', $request->kode_unit)
                ->update([
                    'no_room' => (string) $request->room_no,
                ]);

            DB::table('reservations')
                ->where('no_reservasi', $request->no_reservasi)
                ->where('code_service', $request->kode_unit)
                ->update([
                    'room_no' => (string) $request->room_no,
                    'updated_at' => Carbon::now(),
                ]);
        });

        $this->broadcastPropertyCalendarChanged('reservation-room-moved');

        return response()->json([
            'message' => 'Reservation room updated.',
        ]);
    }

    public function propertyCalendarDigest()
    {
        $reservationDigest = DB::table('reservations')
            ->selectRaw('COUNT(*) as total_rows, MAX(id) as last_id, MAX(updated_at) as last_updated')
            ->first();

        $detailDigest = DB::table('reservation_room_detail')
            ->selectRaw('COUNT(*) as total_rows, MAX(id) as last_id')
            ->first();

        $rateDigest = DB::table('rates')
            ->selectRaw('COUNT(*) as total_rows, MAX(id) as last_id, MAX(updated_at) as last_updated')
            ->first();

        $payload = [
            'reservations' => $reservationDigest,
            'reservation_room_detail' => $detailDigest,
            'rates' => $rateDigest,
        ];

        return response()->json([
            'digest' => sha1(json_encode($payload)),
            'checked_at' => now()->toIso8601String(),
        ]);
    }

    private function broadcastPropertyCalendarChanged(string $reason): void
    {
        try {
            broadcast(new PropertyCalendarChanged($reason));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Display maps page
     *
     * @return \Illuminate\View\View
     */
    public function maps()
    {
        return view('admin.pages.maps');
    }

    /**
     * Display tables page
     *
     * @return \Illuminate\View\View
     */
    public function tables()
    {
        return view('admin.pages.tables');
    }

    /**
     * Display notifications page
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        return view('admin.pages.notifications');
    }

    /**
     * Display rtl page
     *
     * @return \Illuminate\View\View
     */
    public function rtl()
    {
        return view('admin.pages.rtl');
    }

    /**
     * Display typography page
     *
     * @return \Illuminate\View\View
     */
    public function typography()
    {
        return view('admin.pages.typography');
    }

    public function rooms(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $room = DB::table('bookings')
            ->where('lang', $lang)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('real_name', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.rooms', ['room' => $room]);
    }
    public function roomAdd()
    {
        // $defaultLocale = config('app.locale');
        $fasilitas = DB::table('facilities')->get();
        return view('admin.pages.room_add', compact('fasilitas'));
    }

    public function rates()
    {
        $defaultLocale = config('app.locale');

        // Generate a collection of dates for the next 30 days
        $dates = collect();
        for ($i = 0; $i < 30; $i++) {
            $dates->push(Carbon::now()->addDays($i));
        }

        $rates = DB::table('rates')->get();

        // Fetch unique room types from the bookings table for rate management
        $rooms = DB::table('bookings')
            ->where('lang', $defaultLocale)
            ->get();

        return view('admin.pages.rates', compact('rates', 'rooms', 'dates'));
    }

    public function tour(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $tur = DB::table('tour_packages')
            ->where('lang', $lang)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('tour_name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.tour', ['tour' => $tur]);
    }
    public function tourAdd()
    {
        // $defaultLocale = config('app.locale');
        $destinasi = DB::table('destinations')->get();
        $areas = DB::table('travel_area')->get();
        return view('admin.pages.tour_add', compact('destinasi', 'areas'));
    }
    /**
     * Display upgrade page
     *
     * @return \Illuminate\View\View
     */
    public function destinasi(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $destinasi = DB::table('destinations')
            ->leftJoin('travel_area', 'travel_area.id', '=', 'destinations.type')
            ->select('destinations.*', 'travel_area.name as area_name')
            ->when($lang, function ($query, $lang) {
                return $query->where('destinations.lang', $lang);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('destinations.name', 'like', "%{$search}%")
                      ->orWhere('destinations.code', 'like', "%{$search}%")
                      ->orWhere('destinations.deskripsi', 'like', "%{$search}%")
                      ->orWhere('travel_area.name', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.destinasi', ['destinasi' => $destinasi]);
    }
    public function destinasiAdd()
    {
        $areas = DB::table('travel_area')->get();

        return view('admin.pages.destinasi_add', compact('areas'));
    }
    public function activity(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $activity = DB::table('activities')
            ->when($lang, function ($query, $lang) {
                return $query->where('lang', $lang);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('area', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        $areas = DB::table('travel_area')->pluck('name', 'id');

        return view('admin.pages.activity', compact('activity', 'areas'));
    }
    public function activityAdd()
    {
        $areas = DB::table('travel_area')->get();

        return view('admin.pages.activity_add', compact('areas'));
    }
    public function products(Request $request){
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $products = DB::table('products')
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('product_code', 'like', "%{$search}%")
                      ->orWhere('product_name', 'like', "%{$search}%")
                      ->orWhere('product_des', 'like', "%{$search}%")
                      ->orWhere('parent_type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        $defaultLocale = config('app.locale');
        $activities = DB::table('activities')
            ->where('lang', $defaultLocale)
            ->pluck('name', 'code');

        foreach ($products as $product) {
            $codes = array_unique(array_filter(explode(';', $product->parent_type)));
            $names = array_map(function($code) use ($activities) {
                return $activities[$code] ?? $code;
            }, $codes);
            $product->activity_names = implode(', ', $names);
        }

        return view('admin.pages.products', ['products' => $products]);
    }
    public function productsAdd(){

        $activities = DB::table('activities')->get();
        return view('admin.pages.products_add', ['activities' => $activities]);
    }
    public function news(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $news = DB::table('artikels')
            ->where('lang', $lang)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.news', ['news' => $news]);
    }
    public function newsAdd()
    {
        // $defaultLocale = config('app.locale');
        return view('admin.pages.news_add', ['newsDetail' => null]);
    }
    
}
