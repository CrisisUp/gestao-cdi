<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Exibe a listagem de logs de auditoria (Acesso apenas para admins).
     */
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->model, function($q, $model) {
                return $q->where('model_type', 'like', "%{$model}%");
            })
            ->when($request->action, function($q, $action) {
                return $q->where('action', $action);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Exporta os logs de auditoria em CSV.
     */
    public function exportCsv(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->model, function($q, $model) {
                return $q->where('model_type', 'like', "%{$model}%");
            })
            ->when($request->action, function($q, $action) {
                return $q->where('action', $action);
            })
            ->latest()
            ->get();

        $handle = fopen('php://temp', 'r+');
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['Data', 'Usuário', 'Ação', 'Modelo', 'ID', 'IP', 'Detalhes']);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->created_at->format('d/m/Y H:i:s'),
                $log->user?->name ?? 'Sistema',
                $log->action,
                class_basename($log->model_type),
                $log->model_id,
                $log->ip_address ?? '-',
                json_encode($log->new_values ?? $log->old_values ?? []),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="logs-auditoria-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
