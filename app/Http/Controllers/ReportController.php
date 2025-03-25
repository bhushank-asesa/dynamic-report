<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function getReport(Request $request)
    {
        try {
            $rules = [
                'expenseTypeId' => 'nullable|array',
                'expenseCategoryId' => 'nullable|array',
                'mainLocationId' => 'nullable|array',
                'subLocationId' => 'nullable|array',
                'userId' => 'nullable|array',
                'expenseTypeId.*' => 'nullable|exists:expense_types,id',
                'expenseCategoryId.*' => 'nullable|exists:expense_categories,id',
                'mainLocationId.*' => 'nullable|exists:main_locations,id',
                'subLocationId.*' => 'nullable|exists:sub_locations,id',
                'userId.*' => 'nullable|exists:users,id',
                'fromDate' => 'nullable|date|date_format:Y-m-d',
                'toDate' => 'nullable|date|date_format:Y-m-d',
                'topN' => 'nullable|numeric|gt:0',
                'monthWise' => 'required|string|in:yes,no',
                'groupBy' => [
                    'nullable',
                    'array',
                    // function ($attribute, $value, $fail) use ($request) {
                    //     if ($request->input('topN') !== null && empty($value)) {
                    //         $fail('The groupBy field is required when topN is provided.');
                    //     }
                    // },
                ],
                'groupBy.*' => 'nullable|in:expenseTypeId,expenseCategoryId,mainLocationId,subLocationId,userId,monthWise',
            ];
            $groupBySelectColumns = [
                "expenseTypeId" => "expenseTypeName",
                "expenseCategoryId" => "expenseCategoryName",
                "mainLocationId" => "mainLocationName",
                "subLocationId" => "subLocationName",
                "userId" => "expenseCategoryName",
                "monthWise" => "YEAR(dateOfExpense) as txnYear, MONTH(dateOfExpense) as txnMonth",
            ];
            $errorMessage = [];

            $validator = Validator::make($request->all(), $rules, $errorMessage);

            if ($validator->fails()) {
                $data = $validator->messages();
                throw new Exception($validator->errors()->first(), 404);
            }


            $reportQuery = Expense::where("expenses.status", "1");
            if (!empty($request->fromDate)) {
                $reportQuery->where("dateOfExpense", ">=", $request->fromDate);
            }
            if (!empty($request->toDate)) {
                $reportQuery->where("dateOfExpense", "<=", $request->toDate);
            }
            $groupByColumns = [
                "expenseTypeId" => "expenses.expenseTypeId",
                "expenseCategoryId" => "expenses.expenseCategoryId",
                "mainLocationId" => "expenses.mainLocationId",
                "subLocationId" => "expenses.subLocationId",
                "userId" => "expenses.userId",
                "monthWise" => "YEAR(dateOfExpense), MONTH(dateOfExpense)",
            ];
            if (!empty($request->topN)) {
                $reportQuery->limit($request->topN)->orderBy(DB::raw("amount"), "desc");
            }
            if (!empty($request->groupBy)) {
                $groupBy = $request->groupBy;
                foreach ($groupBy as &$item) {
                    $item = $groupByColumns[$item];
                }
                $reportQuery->orderBy(DB::raw("sum(amount)"), "desc")->groupByRaw(implode(",", $groupBy));
            }
            if (!empty($request->expenseTypeId)) {
                $reportQuery->whereIn("expenses.expenseTypeId", $request->expenseTypeId);
            }
            if (!empty($request->expenseCategoryId)) {
                $reportQuery->whereIn("expenses.expenseCategoryId", $request->expenseCategoryId);
            }
            if (!empty($request->mainLocationId)) {
                $reportQuery->whereIn("expenses.mainLocationId", $request->mainLocationId);
            }
            if (!empty($request->subLocationId)) {
                $reportQuery->whereIn("expenses.subLocationId", $request->subLocationId);
            }
            if (!empty($request->userId)) {
                $reportQuery->whereIn("userId", $request->userId);
            }
            $reportQuery->leftJoin("expense_types", "expenseTypeId", "=", "expense_types.id");
            $reportQuery->leftJoin("expense_categories", "expenseCategoryId", "=", "expense_categories.id");
            $reportQuery->leftJoin("main_locations", "mainLocationId", "=", "main_locations.id");
            $reportQuery->leftJoin("sub_locations", "subLocationId", "=", "sub_locations.id");
            $reportQuery->leftJoin("users", "userId", "=", "users.id");

            $groupBySelectColumnsRaw = "";
            $groupBySelectColumnsIdsRaw = "";
            if (!empty($request->groupBy)) {
                $tempGroupBySelectColumnsRaw = [];
                $tempGroupBySelectColumnsIdsRaw = [];
                foreach ($request->groupBy as $item) {
                    $tempGroupBySelectColumnsRaw[] = $item == "monthWise" ? "YEAR(dateOfExpense)  as txnYear, MONTH(dateOfExpense) as txnMonth" : $groupBySelectColumns[$item];
                    if (in_array($item, ['mainLocationId'])) {
                        $tempGroupBySelectColumnsIdsRaw[] = "expenses.$item";
                    } else if (in_array($item, ['monthWise'])) {
                    } else {
                        $tempGroupBySelectColumnsIdsRaw[] = $item;
                    }
                }
                $groupBySelectColumnsRaw = implode(",", $tempGroupBySelectColumnsRaw);
                $groupBySelectColumnsIdsRaw = implode(",", $tempGroupBySelectColumnsIdsRaw);
            }


            if (!empty($request->groupBy)) {
                $rawSelect = $groupBySelectColumnsIdsRaw ? "sum(amount) as amount,$groupBySelectColumnsRaw,$groupBySelectColumnsIdsRaw" : "sum(amount) as amount,$groupBySelectColumnsRaw";
            } else {
                $rawSelect = "expenses.id,transactionId,amount,dateOfExpense,title,expenseCategoryName,expenseTypeName,users.name as userName,mainLocationName,subLocationName";
                if (!empty($request->monthWise) && $request->montWise == "yes") {
                    $rawSelect .= ",Month(dateOfExpense),Year(dateOfExpense)";
                }
            }
            $data = $reportQuery->selectRaw($rawSelect)->get();

            $conditionalTotalAmount = 0;

            if ($data->isEmpty()) {
                throw new Exception("No Data found");
            }
            $totalAmount = Expense::where("status", "1")->sum("amount");
            $conditionalTotalAmount = $data->reduce(function ($carry, $item) {
                return $carry += $item->amount;
            }, 0);
            $rawQuery = $reportQuery->toRawSql();

            if (!empty($request->groupBy)) {
                $data = $data->toArray();
                foreach ($data as &$groupRowItem) {
                    $tempCondition = [];
                    foreach ($request->groupBy as $item) {
                        if (in_array($item, ['mainLocationId'])) {
                            $tempCondition["expenses.$item"] = $groupRowItem[$item];
                        } else if (in_array($item, ['monthWise'])) {
                        } else {
                            $tempCondition[$item] = $groupRowItem[$item];

                        }
                    }
                    $subQuery = Expense::where($tempCondition);

                    if (in_array("monthWise", $request->groupBy)) {
                        $subQuery->whereRaw("Month(dateOfExpense)=" . $groupRowItem["txnMonth"]);
                        $subQuery->whereRaw("Year(dateOfExpense)=" . $groupRowItem["txnYear"]);
                    }
                    if (!empty($request->expenseTypeId)) {
                        $subQuery->whereIn("expenses.expenseTypeId", $request->expenseTypeId);
                    }
                    if (!empty($request->expenseCategoryId)) {
                        $subQuery->whereIn("expenses.expenseCategoryId", $request->expenseCategoryId);
                    }
                    if (!empty($request->mainLocationId)) {
                        $subQuery->whereIn("expenses.mainLocationId", $request->mainLocationId);
                    }
                    if (!empty($request->subLocationId)) {
                        $subQuery->whereIn("expenses.subLocationId", $request->subLocationId);
                    }
                    if (!empty($request->userId)) {
                        $subQuery->whereIn("expenses.userId", $request->userId);
                    }
                    if (!empty($request->fromDate)) {
                        $subQuery->where("dateOfExpense", ">=", $request->fromDate);
                    }
                    if (!empty($request->toDate)) {
                        $subQuery->where("dateOfExpense", "<=", $request->toDate);
                    }
                    $groupRowItem['subRecords'] = $subQuery->selectRaw("*,Month(dateOfExpense) as txnMonth,Year(dateOfExpense) as txnYear")->get();
                    $groupRowItem['subRecordsCount'] = $subQuery->count();
                    $groupRowItem['conditionalPercentage'] = $conditionalTotalAmount ? number_format($groupRowItem['amount'] * 100 / $conditionalTotalAmount, 2) : null;
                    $groupRowItem['percentage'] = $totalAmount ? number_format($groupRowItem['amount'] * 100 / $totalAmount, 2) : null;
                }
            }

            return response()->json(['status' => true, "message" => "Reports data found", "data" => $data, "count" => count($data), "rawQuery" => $rawQuery, "conditionalTotalAmount" => $conditionalTotalAmount, "totalAmount" => $totalAmount]);
        } catch (Exception $e) {
            info('error in ' . __METHOD__ . ' ' . $e->getMessage() . ' in file ' . $e->getFile() . ' at line no ' . $e->getLine());
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
}
