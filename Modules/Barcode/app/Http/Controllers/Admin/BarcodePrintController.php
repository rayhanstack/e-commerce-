<?php

namespace Modules\Barcode\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Barcode\Services\BarcodeGeneratorService;
use Modules\Catalog\Models\Product;

class BarcodePrintController extends Controller
{
    public function index(Request $request, BarcodeGeneratorService $barcodeService): View
    {
        $products = Product::with('variants')->where('is_active', true)->get();
        $selectedProduct = null;
        $labelItems = [];

        if ($request->filled('product_id')) {
            $selectedProduct = Product::with('variants')->find($request->product_id);
            $quantity = max(1, (int) $request->get('quantity', 1));
            $stickerFormat = $request->get('format', '50x25'); // 50x25mm thermal sticker

            $itemsToPrint = [];
            if ($selectedProduct->type === 'variant' && $selectedProduct->variants->count() > 0) {
                foreach ($selectedProduct->variants as $variant) {
                    $itemsToPrint[] = [
                        'title' => $selectedProduct->name,
                        'variant' => implode(', ', $variant->attribute_values ?? []),
                        'sku' => $variant->sku,
                        'barcode' => $variant->barcode ?: $variant->sku,
                        'price' => number_format($variant->active_price, 2),
                    ];
                }
            } else {
                $itemsToPrint[] = [
                    'title' => $selectedProduct->name,
                    'variant' => '',
                    'sku' => $selectedProduct->sku,
                    'barcode' => $selectedProduct->barcode ?: $selectedProduct->sku,
                    'price' => number_format($selectedProduct->active_price, 2),
                ];
            }

            foreach ($itemsToPrint as $item) {
                $barcodeSvg = $barcodeService->generateCode128Svg($item['barcode'], 40, 2);
                $qrSvg = $barcodeService->generateQrSvg($item['barcode'], 80);

                for ($i = 0; $i < $quantity; $i++) {
                    $labelItems[] = array_merge($item, [
                        'barcodeSvg' => $barcodeSvg,
                        'qrSvg' => $qrSvg,
                    ]);
                }
            }
        }

        return view('barcode::admin.labels.print', compact('products', 'selectedProduct', 'labelItems'));
    }
}
