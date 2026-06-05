<?php

namespace App\Http\Controllers\MoonShine;

use App\Http\Controllers\Controller;

use App\Modules\Transaction\Services\TransactionExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionExportController extends Controller
{
    public function __construct(
        private readonly TransactionExportService $service,
    ) {
    }

    public function download(Request $request, string $format): StreamedResponse
    {
        return match ($format) {
            'csv' => $this->service->downloadCsv(),
            'xls' => $this->service->downloadExcelCompatible(),
            default => abort(404),
        };
    }
}
