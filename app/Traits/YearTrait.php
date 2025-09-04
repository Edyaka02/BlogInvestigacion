<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait YearTrait
{
    /**
     * Aplica filtros de año a la consulta.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @param string $yearColumn
     * @param bool $isDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyYearFilters($query, Request $request, $yearColumn, $isDate = false)
    {
        if ($request->input('anio') === 'intervalo' && $request->has('anio_inicio') && $request->has('anio_fin') && !is_null($request->input('anio_inicio')) && !is_null($request->input('anio_fin'))) {
            $anio_inicio = $request->input('anio_inicio');
            $anio_fin = $request->input('anio_fin');

            // Validar que el año final no sea menor al año de inicio
            if ($anio_fin < $anio_inicio) {
                return redirect()->back()->with('error', 'El año final no puede ser menor que el año de inicio.');
            }

            if ($isDate) {
                $query->whereBetween(DB::raw("YEAR($yearColumn)"), [$anio_inicio, $anio_fin]);
            } else {
                $query->whereBetween($yearColumn, [$anio_inicio, $anio_fin]);
            }
        } elseif ($request->has('anio') && !empty($request->input('anio')) && $request->input('anio') !== 'todos') {
            $anios = $request->input('anio');
            if (!is_array($anios)) {
                $anios = [$anios];
            }

            if ($isDate) {
                $query->whereIn(DB::raw("YEAR($yearColumn)"), $anios);
            } else {
                $query->whereIn($yearColumn, $anios);
            }
        }

        return $query;
    }

    private function applyYears($num)
    {
        return range(date('Y'), date('Y') - $num);
    }
}