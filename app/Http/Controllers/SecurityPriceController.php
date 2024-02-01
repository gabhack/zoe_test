<?php

namespace App\Http\Controllers;

use App\Jobs\SyncPrices;
use App\Models\SecurityType;
use Illuminate\Http\Request;

class SecurityPriceController extends Controller
{

public function syncPrices($typeSlug)
{
    $securityType = SecurityType::where('slug', $typeSlug)->first();

    if (!$securityType) {
        return response()->json(['message' => 'Tipo de seguridad no encontrado'], 404);
    }

    SyncPrices::dispatch($securityType->slug);

    return response()->json(['message' => 'Sincronización iniciada para ' . $securityType->name]);
}
}
