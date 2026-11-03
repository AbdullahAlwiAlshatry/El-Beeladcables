<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf; // استدعاء مكتبة الـ PDF
use Exception;
use Illuminate\Support\Facades\Config; // أمان أكثر للبيانات
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tag;

class MainController extends Controller
{
    public function index()
    {
        $products = Product::with('tags')->get();
        return view("Main", compact('products'));
    }

    public function message(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $token = "env(TeleToken)";
        $chatId = "env(chatID)";

        $allMessage =
            "📩 رسالة جديدة من العميل\n\n" .
            "📧 البريد: " . $data['email'] . "\n" .
            "📌 الموضوع: " . $data['subject'] . "\n\n" .
            "💬 الرسالة:\n" . $data['message'];

        $response = Http::timeout(10)->post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text' => $allMessage,
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال الرسالة'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح'
        ]);
    }


    public function order(Request $request)
    {
        // 1️⃣ قراءة الحقول المرسلة من كائن الـ FormData بنجاح وتوافق تام
        $customerName = $request->input('customerName') ?? 'عميل غير معروف';
        $customerPhone = $request->input('customerPhone') ?? 'بدون هاتف';
        $customerAddress = $request->input('customerAddress') ?? 'بدون عنوان';
        $customerShop = $request->input('customerShop') ?? '';
        $orderNo = $request->input('orderNo') ?? '0000';
        $date = $request->input('date') ?? date('Y-m-d');
        $totalItems = $request->input('totalItems') ?? 0;
        $pdfHtml = $request->input('pdfHtml') ?? ''; // كود الـ HTML الأنيق المرسل من الجافا سكريبت

        preg_match_all(
            '/<img[^>]+src="([^"]+)"[^>]*>/i',
            $pdfHtml,
            $matches
        );

        foreach ($matches[1] as $src) {

            if (strpos($src, '/assets/uploads/') !== 0) {
                continue;
            }

            $imagePath = public_path(ltrim($src, '/'));

            if (!file_exists($imagePath)) {
                continue;
            }

            $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            switch ($extension) {
                case 'png':
                    $mime = 'image/png';
                    break;

                case 'gif':
                    $mime = 'image/gif';
                    break;

                case 'webp':
                    $mime = 'image/webp';
                    break;

                default:
                    $mime = 'image/jpeg';
                    break;
            }

            $base64 = base64_encode(
                file_get_contents($imagePath)
            );

            $dataUri = 'data:' . $mime . ';base64,' . $base64;

            $pdfHtml = str_replace(
                'src="' . $src . '"',
                'src="' . $dataUri . '"',
                $pdfHtml
            );
        }

        // ضع قيم البوت والـ Chat ID الخاصة بك يدوياً هنا لتخطي كاش الـ env
        $token = "env(TeleToken)";
        $chatId = "env(chatID)";

        try {
            // 2️⃣ هندسة الفاتورة: إجبار التشفير على UTF-8 لضمان قراءة اللغة العربية والإنجليزية معاً بوضوح كامل
            // نرسل النص مباشرة كملف ويب مستقل (.html) ليعرض التصميم الأصلي والـ Flexbox بكفاءة خيالية
            $htmlInvoiceContent = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $pdfHtml;

            // صياغة رسالة التنبيه النصية المرافقة للملف داخل محادثة تليجرام
            $captionMessage =
                "📩 طلب وفاتورة رقمية جديدة\n\n" .
                "👤 العميل: " . $customerName . "\n" .
                "📞 الهاتف: " . $customerPhone . "\n" .
                ($customerShop ? "🏪 المحل: " . $customerShop . "\n" : "") .
                "📍 العنوان: " . $customerAddress . "\n\n" .
                "🔢 رقم الطلب: " . $orderNo . "\n" .
                "📅 التاريخ: " . $date . "\n" .
                "📦 إجمالي المنتجات: " . $totalItems . " قطع";

            // 3️⃣ قذف دفق بايتات الفاتورة مباشرة إلى تليجرام كـ Document في جزء من الثانية
            $response = Http::timeout(25)
                ->attach(
                    'document',             // المعامل المعتمد من تليجرام لاستقبال المستندات
                    $htmlInvoiceContent,    // نمرر نص الـ HTML مباشرة كـ Stream دون حفظه على الهارد ديسك
                    "Invoice-{$orderNo}.html" // سيصلك كملف فاتورة ويب خفيف وقابل للفتح بضغطة واحدة وبخط عربي ممتاز
                )
                ->post("https://api.telegram.org/bot{$token}/sendDocument", [
                    'chat_id' => $chatId,
                    'caption' => $captionMessage,
                    'parse_mode' => 'HTML'
                ]);

            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'تليجرام رفض استقبال مستند الفاتورة'], 400);
            }

            return response()->json(['success' => true, 'message' => 'تم استقبال طلبك وتوليد الفاتورة وإرسالها بنجاح']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث استثناء: ' . $e->getMessage()], 500);
        }
    }

}
