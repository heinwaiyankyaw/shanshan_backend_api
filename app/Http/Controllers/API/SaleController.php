<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function lists(Request $request)
    {
        try {
            $page = $request['page'] ?? 1;
            $perPage = 10;

            $currentMonthSales = Sale::whereYear('updated_at', Carbon::now()->year)
                ->whereMonth('updated_at', Carbon::now()->month)
                ->orderBy('updated_at', 'desc')
                ->with(['paymentType', 'menu', 'spicyLevel', 'ahtoneLevel', 'remark', 'saleItems'])
                ->paginate($perPage, ['*'], 'page', $page);

            if ($currentMonthSales->count() < 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Not Found',
                    'data' => [],
                ]);
            }
            $data = [];
            foreach ($currentMonthSales->items() as $currentMonthSale) {
                $saleItems = SaleItem::where('sale_id', $currentMonthSale->id)->get();
                $products = [];
                foreach ($saleItems as $saleItem) {
                    $products[] = [
                        'product_id' => $saleItem->product->name,
                        'qty' => $saleItem->qty,
                        'is_gram' => $saleItem->product->is_gram,
                        'price' => $saleItem->price,
                        'total_price' => $saleItem->total_price,
                    ];
                }
                $data[] = [
                    'id' => $currentMonthSale->id,
                    'order_no' => $currentMonthSale->order_no,
                    'payment_type_id' => $currentMonthSale->payement_type_id,
                    'table_number' => $currentMonthSale->table_number,
                    'dine_in_or_percel' => $currentMonthSale->dine_in_or_percel,
                    'sub_total' => $currentMonthSale->sub_total,
                    'tax' => $currentMonthSale->tax,
                    'discount' => $currentMonthSale->discount,
                    'grand_total' => $currentMonthSale->grand_total,
                    'paid_cash' => $currentMonthSale->paid_cash,
                    'paid_online' => $currentMonthSale->paid_online,
                    'created_at' => $currentMonthSale->created_at,
                    'updated_at' => $currentMonthSale->updated_at,
                    'payment_type' => [
                        'id' => $currentMonthSale->paymentType->id,
                        'name' => $currentMonthSale->paymentType->name,
                    ],
                    'menu' => [
                        'id' => $currentMonthSale->menu->id,
                        'name' => $currentMonthSale->menu->name,
                    ],
                    'spicy_level' => [
                        'id' => $currentMonthSale->spicyLevel->id,
                        'name' => $currentMonthSale->spicyLevel->name,
                    ],
                    'ahtone_level' => [
                        'id' => $currentMonthSale->ahtoneLevel->id,
                        'name' => $currentMonthSale->ahtoneLevel->name,
                    ],
                    'remark' => [
                        'id' => $currentMonthSale->remark->id ?? null,
                        'name' => $currentMonthSale->remark->name ?? null,
                    ],
                    'products' => $products,
                ];
            }

            return response()->json([
                'status' => 200,
                'message' => 'Sale datas were fetched.',
                'data' => $data,
            ]);

        } catch (Exception $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Unknown Error.',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $userID = Auth::user()->id;
            $validatedData = $this->checkValidation($request);
            $validatedData['user_id'] = $userID;
            $products = $validatedData['products'];
            unset($validatedData['products']);
            $sale = Sale::create($validatedData);
            foreach ($products as $product) {
                $saleItem = [
                    'sale_id' => $sale->id,
                    'product_id' => $product["product_id"],
                    'qty' => $product["qty"],
                    'price' => $product["price"],
                    'total_price' => $product["total_price"],
                ];
                SaleItem::create($saleItem);
            }
            return response()->json([
                'status' => 201,
                'message' => 'Sale was saled successfully.',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Unknown Error.',
                'error' => $th->getMessage(),
            ]);
        }

    }

    // Report
    public function daily()
    {
        $date = Carbon::now()->format('Y-m-d');
        $sales = Sale::whereDate('created_at', $date)->get();

        $totalSales = $sales->count();
        $totalPaidCash = $sales->sum('paid_cash');
        $totalPaidOnline = $sales->sum('paid_online');
        $totalGrand = $sales->sum('grand_total');
        $data = [
            'total_sales' => $totalSales,
            'total_paid_cash' => $totalPaidCash,
            'total_paid_online' => $totalPaidOnline,
            'daily_date' => $date,
            'total_Grands' => $totalGrand,
        ];

        if ($sales->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'There is not Daily Sale',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Daily Sale was fetched.',
            'data' => $data,
        ]);
    }

    public function weekly()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        $sales = Sale::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();

        $totalSales = $sales->count();
        $totalPaidCash = $sales->sum('paid_cash');
        $totalPaidOnline = $sales->sum('paid_online');
        $totalGrand = $sales->sum('grand_total');

        $data = [
            'total_sales' => $totalSales,
            'total_paid_cash' => $totalPaidCash,
            'total_paid_online' => $totalPaidOnline,
            'start_of_week' => $startOfWeek,
            'end_of_week' => $endOfWeek,
            'total_Grands' => $totalGrand,
        ];

        if ($sales->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'There is not Weekly Sale.',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Weekly Sale was fetched.',
            'data' => $data,
        ]);

    }

    public function pastMonth()
    {
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $sales = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $totalSales = $sales->count();
        $totalPaidCash = $sales->sum('paid_cash');
        $totalPaidOnline = $sales->sum('paid_online');
        $totalGrand = $sales->sum('grand_total');

        $data = [
            'total_sales' => $totalSales,
            'total_paid_cash' => $totalPaidCash,
            'total_paid_online' => $totalPaidOnline,
            'past_month' => Carbon::now()->subMonth()->format('Y-m'),
            'total_Grands' => $totalGrand,
        ];

        if ($sales->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'There is not Past Month Sale',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Past Month Sale was fetched.',
            'data' => $data,
        ]);
    }

    public function currentMonth()
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        $sales = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $totalSales = $sales->count();
        $totalPaidCash = $sales->sum('paid_cash');
        $totalPaidOnline = $sales->sum('paid_online');
        $totalGrand = $sales->sum('grand_total');

        $data = [
            'total_sales' => $totalSales,
            'total_paid_cash' => $totalPaidCash,
            'total_paid_online' => $totalPaidOnline,
            'current_month' => Carbon::now()->format('Y-m'),
            'total_Grands' => $totalGrand,
        ];

        if ($sales->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'There is not Current Month Sale',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Current Month Sale was fetched.',
            'data' => $data,
        ]);
    }

    protected function checkValidation($request)
    {
        return Validator::make($request->all(), [
            'payment_type_id' => 'nullable',
            'menu_id' => 'required',
            'spicy_level_id' => 'required',
            'ahtone_level_id' => 'required',
            'remark_id' => 'nullable',
            'order_no' => 'required',
            'table_number' => 'required',
            'dine_in_or_percel' => 'required|boolean',
            'sub_total' => 'required',
            'tax' => 'required',
            'discount' => 'nullable',
            'grand_total' => 'required',
            'paid_cash' => 'required_without:paid_online',
            'paid_online' => 'required_without:paid_cash',
            'refund' => 'required',
            'products' => 'required|array',
        ])->validate();
    }
}