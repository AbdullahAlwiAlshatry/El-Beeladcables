<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tag;


class ManageController extends Controller
{
    public function index()
    {
        $products = Product::with('tags')->get();
        return view("Manage", compact('products'));
    }

    public function create()
    {
        return view('ManageAddView', ['product' => null]);
    }

    public function show()
    {
        $products = Product::with('tags')->get();
        return view("Manage", compact('products'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('manage.index')->with('success', 'تم حذف المنتج بنجاح');
    }


    public function store(Request $request)
    {
        // رفع الصورة إلى مجلد uploads داخل public
        $imageName = 'default.jpg'; // اسم الصورة الافتراضية

        if ($request->hasFile('image')) {
            // إذا المستخدم رفع صورة جديدة
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads'), $imageName);
        }

        // إنشاء المنتج
        $product = Product::create([
            'ProductName' => $request->input('ProductName'),
            'ProductTitle' => $request->input('ProductTitle'),
            'ProductPicture' => $imageName,
            'ProductType' => $request->mainNumber,
        ]);

        // الكلمات المفتاحية (نفترض أنها مفصولة بفواصل)
        $tags = explode(',', $request->input('tags'));
        foreach ($tags as $tag) {
            Tag::create([
                'ProductID' => $product->ProductID,
                'Tag' => trim($tag),
            ]);
        }

        return redirect()->route('manage.index')->with('success', 'تمت إضافة المنتج بنجاح');
    }






    public function edit($id)
    {
        $product = Product::with('tags')->findOrFail($id);

        return view("ManageAddView", compact('product'));
    }

    public function update(Request $request, $id)
    {

        // جلب المنتج المطلوب
        $product = Product::findOrFail($id);

        // تحديث الصورة إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads'), $imageName);
            $product->ProductPicture = $imageName;
        }
        // إذا لم يرفع صورة جديدة، نترك الصورة القديمة كما هي

        // تحديث بيانات المنتج الأخرى
        $product->ProductName = $request->input('ProductName');
        $product->ProductTitle = $request->input('ProductTitle');
        $product->ProductType = $request->input('mainNumber');

        $product->save();

        // تحديث الكلمات المفتاحية
        // أولاً نحذف القديمة
        Tag::where('ProductID', $product->ProductID)->delete();

        // ثم نضيف الجديدة
        $tags = explode(',', $request->input('tags'));
        foreach ($tags as $tag) {
            Tag::create([
                'ProductID' => $product->ProductID,
                'Tag' => trim($tag),
            ]);
        }

        return redirect()->route('manage.index')->with('success', 'تم تعديل المنتج بنجاح');
    }

}
