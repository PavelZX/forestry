<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CuttingArea\BulkDestroyCuttingArea;
use App\Http\Requests\Admin\CuttingArea\DestroyCuttingArea;
use App\Http\Requests\Admin\CuttingArea\IndexCuttingArea;
use App\Http\Requests\Admin\CuttingArea\StoreCuttingArea;
use App\Http\Requests\Admin\CuttingArea\UpdateCuttingArea;
use App\Models\CuttingArea;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CuttingAreaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCuttingArea $request
     * @return array|Factory|View
     */
    public function index(IndexCuttingArea $request)
    {
        DB::statement('truncate table cutting_area');
        DB::statement('
insert into cutting_area (wood_specie_id, ripeness, first_age, second_age, avrg_increase, substance, cutting_turnover)
select ws.id,
       (-- Лесосека по спелости
           select sum(f.forest_fund) / ws.calculation_period
           from forest_resources f
                    join timber_class tc on tc.id = f.timber_class_id
           where f.wood_specie_id = ws.id
             and tc.code * ws.calculation_period >= ws.timber_harvesting_age
       ) as ripeness,
       (-- Первая возрастная
           select sum(f.forest_fund) / (ws.calculation_period * 2)
           from forest_resources f
                    join timber_class tc on tc.id = f.timber_class_id
           where f.wood_specie_id = ws.id
             and (tc.code + 1) * ws.calculation_period >= ws.timber_harvesting_age
       ) as first_age,
       (-- Вторая возрастная
           select sum(f.forest_fund) / (ws.calculation_period * 3)
           from forest_resources f
                    join timber_class tc on tc.id = f.timber_class_id
           where f.wood_specie_id = ws.id
             and (tc.code + 2) * ws.calculation_period >= ws.timber_harvesting_age
       ) as second_age,
       (-- По среднему приросту
           select ((sum(f.wood_stock) / sum(f.forest_fund)) -- Средний запас
               / (sum(f.forest_fund * tc.code)
                      / sum(f.forest_fund) * ws.calculation_period)-- Средний возраст класс древесины
                      )-- Средний прирост
                      / (select sum(fi.forest_fund)
                         from forest_resources fi
                                  join bonitet bi on bi.id = fi.bonitet_id
                                  join timber_class ti on ti.id = fi.timber_class_id
                         where fi.wood_specie_id = ws.id
                           and bi.code between 1 and 3
                           and ti.code * ws.calculation_period >= ws.timber_harvesting_age
                  )-- Эксплуатационный фонд
           from forest_resources f
                    join timber_class tc on tc.id = f.timber_class_id
           where f.wood_specie_id = ws.id
       ) as avrg_increase,
       (-- По состоянию
           select sum(f.forest_fund) / ws.calculation_period
           from forest_resources f
                    join timber_class tc on tc.id = f.timber_class_id
           where f.wood_specie_id = ws.id
             and tc.code = (select max(code) from timber_class)
       ) as substance,
       (-- По обороту рубки
           select sum(f.forest_fund) / ws.main_harvesting_age
           from forest_resources f
                    join bonitet b on b.id = f.bonitet_id
           where f.wood_specie_id = ws.id
             and b.code between 1 and 3
       ) as cutting_turnover
from wood_specie ws
');
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CuttingArea::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['avrg_increase', 'cutting_turnover', 'first_age', 'id', 'ripeness', 'second_age', 'substance', 'wood_specie_id'],

            // set columns to searchIn
            ['id']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.cutting-area.index', ['data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param CuttingArea $cuttingArea
     * @throws AuthorizationException
     * @return void
     */
    public function show(CuttingArea $cuttingArea)
    {
        $this->authorize('admin.cutting-area.show', $cuttingArea);

        // TODO your code goes here
    }
}
