<?php

use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\PresencaEquipeController;
use App\Http\Controllers\EncaminhamentoController;
use App\Http\Controllers\FrequenciaController;
use App\Http\Controllers\IdosoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RelatorioMovimentacaoController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'welcome'])->name('dashboard');

// Rota padrão do Breeze para o Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Agrupamento de rotas protegidas
Route::middleware('auth')->group(function () {
    // Relatórios de Movimentação (Controle Social)
    Route::get('/relatorios/movimentacao', [RelatorioMovimentacaoController::class, 'index'])->name('relatorios.movimentacao');
    Route::get('/relatorios/movimentacao/pdf', [RelatorioMovimentacaoController::class, 'exportarPdf'])->name('relatorios.movimentacao.pdf');

    // Rotas de Idosos (Novo Módulo)
    Route::get('/idosos/exportar-csv', [IdosoController::class, 'exportarCsv'])->name('idoso.exportar-csv');
    Route::get('/idosos', [IdosoController::class, 'index'])->name('idoso.index');
    Route::get('/idosos/create', [IdosoController::class, 'create'])->name('idoso.create');
    Route::post('/idosos/store', [IdosoController::class, 'store'])->name('idoso.store');
    Route::get('/idosos/{idoso}/relatorio-preview', [IdosoController::class, 'relatorioPreview'])->name('idoso.relatorio-preview');
    Route::get('/idosos/{idoso}/relatorio-pdf', [IdosoController::class, 'gerarRelatorio'])->name('idoso.relatorio-pdf');
    Route::get('/idosos/{idoso}', [IdosoController::class, 'show'])->name('idoso.show');
    Route::get('/idosos/{idoso}/edit', [IdosoController::class, 'edit'])->name('idoso.edit');
    Route::put('/idosos/{idoso}/update', [IdosoController::class, 'update'])->name('idoso.update');
    Route::delete('/idosos/{idoso}/destroy', [IdosoController::class, 'destroy'])->name('idoso.destroy');

    // Rotas de Frequência (Novo Módulo)
    Route::get('/frequencia', [FrequenciaController::class, 'index'])->name('frequencia.index');
    Route::post('/frequencia', [FrequenciaController::class, 'store'])->name('frequencia.store');

    // Rotas de Encaminhamento (Novo Módulo)
    Route::get('/encaminhamentos', [EncaminhamentoController::class, 'index'])->name('encaminhamento.index');
    Route::get('/encaminhamentos/create', [EncaminhamentoController::class, 'create'])->name('encaminhamento.create');
    Route::post('/encaminhamentos', [EncaminhamentoController::class, 'store'])->name('encaminhamento.store');
    Route::delete('/encaminhamentos/{encaminhamento}', [EncaminhamentoController::class, 'destroy'])->name('encaminhamento.destroy');

    // Rotas de Atividades (Novo Módulo)
    Route::get('/atividades', [AtividadeController::class, 'index'])->name('atividade.index');
    Route::get('/atividades/create', [AtividadeController::class, 'create'])->name('atividade.create');
    Route::post('/atividades', [AtividadeController::class, 'store'])->name('atividade.store');
    Route::get('/atividades/{atividade}', [AtividadeController::class, 'show'])->name('atividade.show');
    Route::post('/atividades/{atividade}/vincular', [AtividadeController::class, 'vincularIdoso'])->name('atividade.vincular');
    Route::delete('/atividades/{atividade}/desvincular/{idoso}', [AtividadeController::class, 'desvincularIdoso'])->name('atividade.desvincular');
    Route::delete('/atividades/{atividade}', [AtividadeController::class, 'destroy'])->name('atividade.destroy');

    // Rotas de Ponto (Equipe)
    Route::post('/ponto/entrada', [PresencaEquipeController::class, 'registrarEntrada'])->name('ponto.entrada');
    Route::post('/ponto/saida', [PresencaEquipeController::class, 'registrarSaida'])->name('ponto.saida');
    Route::get('/ponto/{user}/historico', [PresencaEquipeController::class, 'relatorioPonto'])->name('ponto.historico');
    Route::get('/ponto/{user}/exportar', [PresencaEquipeController::class, 'exportarRelatorioPonto'])->name('ponto.exportar');

    // Rotas de Equipe (Apenas Administradores)
    Route::middleware('can:admin-access')->group(function () {
        Route::get('/equipe', [UserController::class, 'index'])->name('user.index');
        Route::get('/equipe/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/equipe/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/equipe/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/equipe/{user}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/equipe/{user}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

        // Logs de Auditoria
        Route::get('/admin/logs', [ActivityLogController::class, 'index'])->name('admin.logs.index');
        Route::get('/admin/logs/exportar-csv', [ActivityLogController::class, 'exportCsv'])->name('admin.logs.export-csv');
    });

    // Rotas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
